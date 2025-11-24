<script setup>
import { ref, reactive, watch, computed } from 'vue';
// Imported usePage
import { Head, useForm, usePage, router } from '@inertiajs/vue3'; 
import axios from 'axios';

// Get the Inertia Page object
const page = usePage(); 

// Props passed from the Laravel controller
// Using the array-of-strings syntax to avoid parser issues reported in the environment.
const props = defineProps(['institutions', 'years']);

// --- Helper Initialization for Filters to avoid complex syntax issues ---
const initialInstitutionId = props.institutions && props.institutions.length > 0 ? props.institutions[0].id : null;
const initialYear = props.years && props.years.length > 0 ? props.years[0] : new Date().getFullYear();

// --- State for Filters ---
const filters = reactive({
    // Use the pre-calculated safe initial values
    institution_id: initialInstitutionId,
    year: initialYear,
    class: null,
});

const shouldShowClassFilter = ref(false);
const availableClasses = ref([]);
const subjects = ref([]);
const isLoading = ref(false);
// Fixed: Use page.props to get initial flash message
const successMessage = ref(page.props.flash?.success || null); 

// --- Computed Properties ---
const institutionSelected = computed(() => filters.institution_id !== null);
const yearSelected = computed(() => filters.year !== null);
const canFetchSubjects = computed(() => institutionSelected.value && yearSelected.value && (shouldShowClassFilter.value === false || filters.class !== null));

// --- Functions ---

/**
 * Fetches available classes when institution or year changes.
 */
const fetchClasses = async () => {
    // Reset subjects and class filter whenever institution/year changes
    subjects.value = [];
    filters.class = null;
    shouldShowClassFilter.value = false;
    availableClasses.value = [];
    successMessage.value = null;

    if (!filters.institution_id || !filters.year) return;

    try {
        const response = await axios.post('/api/subject-dates/classes', {
            institution_id: filters.institution_id,
            year: filters.year,
        });
        
        shouldShowClassFilter.value = response.data.shouldShowClassFilter;
        availableClasses.value = response.data.classes;

        if (!response.data.shouldShowClassFilter) {
            // If no classes exist for this filter set, fetch subjects immediately
            fetchSubjects();
        }
    } catch (error) {
        console.error("Error fetching classes:", error);
    }
};

/**
 * Fetches the list of subjects based on current filters.
 */
const fetchSubjects = async () => {
    if (!canFetchSubjects.value) {
        subjects.value = [];
        return;
    }

    isLoading.value = true;
    subjects.value = [];
    successMessage.value = null;

    try {
        const response = await axios.post('/api/subject-dates/subjects', filters);
        
        // Populate the subjects ref with the transformed data from the backend
        subjects.value = response.data.subjects.map(subject => ({
            ...subject,
            is_null_date: subject.exam_at === null,
            status: subject.status
        }));

        if (subjects.value.length === 0) {
            // Add a temporary, styled placeholder if no subjects are found
            subjects.value = [{ id: 'placeholder', name: 'No subjects found for this selection.', isPlaceholder: true }];
        }

    } catch (error) {
        console.error("Error fetching subjects:", error);
    } finally {
        isLoading.value = false;
    }
};

/**
 * Watches changes in institution and year to trigger class/subject fetch.
 */
watch([() => filters.institution_id, () => filters.year], fetchClasses, { immediate: true });

/**
 * Watches changes in class selection to trigger subject fetch.
 */
watch(() => filters.class, fetchSubjects);


// --- Form Submission for Bulk Update ---

const updateForm = useForm({
    // Dynamically compute the updates array from the reactive subjects list
    updates: computed(() => subjects.value.filter(s => !s.isPlaceholder).map(s => ({
        id: s.id,
        // Ensure exam_at is treated as null if the checkbox is checked, 
        // as the backend expects null for a missing date.
        exam_at: s.is_null_date ? null : s.exam_at, 
        description: s.description,
        is_null_date: s.is_null_date,
        status: s.status,
    }))),
});

const submitUpdates = () => {
    // Only submit if there are actual subjects to update
    if (updateForm.updates.length === 0) return;

    updateForm.post(route('subject-dates.update'), {
        preserveScroll: true,
        onSuccess: () => {
            // Re-fetch subjects to show the latest changes
            fetchSubjects();
            // Fixed: Use page.props to get the flash message after successful submission
            successMessage.value = page.props.flash?.success; 
            // Clear inertia success message after a few seconds
            setTimeout(() => successMessage.value = null, 5000);
        },
        onError: (errors) => {
            console.error("Update errors:", errors);
        }
    });
};

</script>

<template>
    <Head title="Update Subject Exam Dates" />

    <div class="min-h-screen bg-gray-100 p-4 sm:p-8">
        <div class="max-w-6xl mx-auto bg-white shadow-xl rounded-xl p-6 sm:p-10">
            <h1 class="text-3xl font-extrabold text-indigo-800 mb-6 border-b pb-2">
                Exam Date & Description Editor
            </h1>

            <!-- Success Message -->
            <div v-if="successMessage" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg">
                <p class="font-bold">Success!</p>
                <p>{{ successMessage }}</p>
            </div>

            <!-- Filter Controls -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 bg-indigo-50 p-6 rounded-lg shadow-inner">
                
                <!-- Institution Dropdown -->
                <div>
                    <label for="institution" class="block text-sm font-medium text-gray-700 mb-1">Institution</label>
                    <select id="institution" v-model="filters.institution_id" @change="fetchClasses"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm">
                        <option v-for="inst in institutions" :key="inst.id" :value="inst.id">
                            {{ inst.name }}
                        </option>
                    </select>
                </div>

                <!-- Year Dropdown -->
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                    <select id="year" v-model="filters.year" @change="fetchClasses"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm">
                        <option v-for="y in years" :key="y" :value="y">
                            {{ y }}
                        </option>
                    </select>
                </div>

                <!-- Class Dropdown (Conditional) -->
                <div v-if="shouldShowClassFilter">
                    <label for="class" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                    <select id="class" v-model="filters.class" @change="fetchSubjects"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm">
                        <option :value="null" disabled>Select a Class</option>
                        <option v-for="c in availableClasses" :key="c" :value="c">
                            {{ c }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="isLoading" class="text-center py-10">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500 mx-auto"></div>
                <p class="mt-4 text-indigo-600 font-medium">Loading subjects...</p>
            </div>

            <!-- Subject List for Editing -->
            <form @submit.prevent="submitUpdates" v-else-if="subjects.length > 0 && !subjects[0].isPlaceholder">
                <p class="text-lg font-semibold text-gray-600 mb-4">
                    {{ filters.class ? `Editing Subjects for Class: ${filters.class}` : 'Editing Subjects (No Class Specified)' }}
                </p>

                <div class="space-y-6">
                    <div v-for="subject in subjects" :key="subject.id" 
                         class="border border-gray-200 p-4 rounded-lg bg-white shadow-sm hover:shadow-md transition duration-150">

                        <h3 class="text-xl font-bold text-gray-800 mb-3">{{ subject.name }}</h3>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            
                            <!-- Date Input -->
                            <div class="md:col-span-2">
                                <label :for="'date-' + subject.id" class="block text-xs font-medium text-gray-500 mb-1">Exam Date/Time</label>
                                <input :id="'date-' + subject.id" type="datetime-local" v-model="subject.exam_at"
                                    :disabled="subject.is_null_date"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:text-gray-500" />
                            </div>

                            <!-- Null Checkbox -->
                            <div class="flex items-center space-x-2">
                                <input :id="'null-' + subject.id" type="checkbox" v-model="subject.is_null_date"
                                    class="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label :for="'null-' + subject.id" class="text-sm font-medium text-gray-700">Set Date Null</label>
                            </div>

                            <!-- Status Checkbox -->
                            <div class="flex items-center space-x-2">
                                <input :id="'status-' + subject.id" type="checkbox" v-model="subject.status"
                                    :true-value="1" :false-value="0"
                                    class="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label :for="'status-' + subject.id" class="text-sm font-medium text-gray-700">Active Status</label>
                            </div>

                            <!-- Description Field -->
                            <div class="md:col-span-4">
                                <label :for="'desc-' + subject.id" class="block text-xs font-medium text-gray-500 mb-1">Description (e.g., "Tentative Date")</label>
                                <textarea :id="'desc-' + subject.id" v-model="subject.description" rows="2"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 pt-4 border-t flex justify-end">
                    <button type="submit" 
                            :disabled="updateForm.processing"
                            class="px-8 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:bg-indigo-700 transition duration-150 disabled:bg-indigo-300 disabled:cursor-not-allowed">
                        {{ updateForm.processing ? 'Saving...' : 'Save All Updates' }}
                    </button>
                </div>
            </form>

            <!-- No Subjects Found State -->
            <div v-else-if="subjects.length > 0 && subjects[0].isPlaceholder" class="text-center py-10 bg-yellow-50 rounded-lg border border-yellow-200">
                 <p class="text-lg text-yellow-700 font-medium">No subjects found for the selected filters.</p>
                 <p class="text-sm text-yellow-600 mt-2">Try selecting a different year, institution, or class.</p>
            </div>
            
            <!-- Instructions/Initial State -->
            <div v-else class="text-center py-10 text-gray-500 italic">
                <p>Select an Institution and Year to load subjects for editing exam dates.</p>
            </div>

        </div>
    </div>
</template>