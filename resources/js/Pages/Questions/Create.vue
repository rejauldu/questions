<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import axios from 'axios';
import 'mathlive';

const props = defineProps({
    institutions: Array,
    years: Array,
    classes: Array
});

// Form
const form = useForm({
    article: '',
    a: '',
    b: '',
    c: '',
    d: '',
    answer: '',
    institution_id: '', // store institution ID
    subject_id: '',     // store subject ID
    topic: '',
    sub_topic: '',
    section: '',
    sub_section: '',
    category: '',
    board: '',
    year: '',
    class: '',
    url: null,
}, { forceFormData: true });

// Dynamic subjects
const subjects = ref([]);

// Watch institution change to fetch subjects
watch(() => form.institution_id, async (newVal) => {
    if (!newVal) {
        subjects.value = [];
        form.subject_id = '';
        return;
    }

    try {
        const res = await axios.get('/api/posts/subjects-by-institution', {
            params: { institution_id: newVal }
        });
        subjects.value = res.data;
        form.subject_id = ''; // reset selected subject
    } catch (e) {
        console.error(e);
    }
});

// Math field
const showMath = ref(false);
const mathRef = ref(null);
const preview = ref(null);
const articleRef = ref(null);

const toggleMathKeyboard = () => showMath.value = !showMath.value;

const insertMath = () => {
    if (!mathRef.value || !articleRef.value) return;
    const raw = mathRef.value.getValue();
    if (!raw) return;

    const wrapped = `\\(${raw}\\)`;
    const textarea = articleRef.value;
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;

    form.article =
        form.article.substring(0, start) +
        wrapped +
        form.article.substring(end);

    mathRef.value.setValue('');
    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = start + wrapped.length;
};

const previewImage = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    form.url = file;
    preview.value = URL.createObjectURL(file);
};

const submit = () => {
    form.post(route('questions.store'), {
        onSuccess: page => {
            preview.value = null;
            form.reset();
            if (fileRef?.value) fileRef.value.value = null;
            if (page.props.redirect) window.location.href = page.props.redirect;
        }
    });
};
</script>

<template>
<AuthenticatedLayout>
    <Head title="Create Question" />

    <template #header>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Create New Question
        </h2>
    </template>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4" :class="{ 'opacity-50 pointer-events-none': form.processing }">

                    <!-- Toolbar -->
                    <div class="flex gap-2 mb-2">
                        <button @click="toggleMathKeyboard"
                            class="px-2 py-1 border rounded bg-white hover:bg-gray-100">
                            Math
                        </button>
                        <button @click="insertMath"
                            class="px-2 py-1 border rounded bg-white hover:bg-gray-100">
                            Insert
                        </button>
                    </div>

                    <!-- Math Keyboard -->
                    <div v-if="showMath" class="mb-2 border p-2">
                        <math-field ref="mathRef" style="width:100%; min-height:50px;"></math-field>
                    </div>

                    <!-- Article -->
                    <label class="block font-semibold">Article (উদ্দীপক)</label>
                    <textarea ref="articleRef" v-model="form.article"
                        class="w-full border p-2 h-32"
                        placeholder="Write article with math..."></textarea>

                    <!-- Options -->
                    <label class="font-semibold">ক)</label>
                    <input v-model="form.a" class="w-full border p-2" placeholder="ক)" />

                    <label class="font-semibold">খ)</label>
                    <input v-model="form.b" class="w-full border p-2" placeholder="খ)" />

                    <label class="font-semibold">গ)</label>
                    <input v-model="form.c" class="w-full border p-2" placeholder="গ)" />

                    <label class="font-semibold">ঘ)</label>
                    <input v-model="form.d" class="w-full border p-2" placeholder="ঘ)" />

                    <!-- Answer -->
                    <label class="font-semibold">Answer</label>
                    <input v-model="form.answer" class="w-full border p-2" placeholder="উত্তর" />

                    <!-- Metadata Dropdowns -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">

                        <!-- Institution -->
                        <label class="font-semibold">Institution</label>
                        <select v-model="form.institution_id" class="border p-2">
                            <option value="">Select Institution</option>
                            <option v-for="inst in institutions" :key="inst.id" :value="inst.id">
                                {{ inst.name }}
                            </option>
                        </select>

                        <!-- Subject -->
                        <label class="font-semibold">Subject</label>
                        <select v-model="form.subject_id" class="border p-2">
                            <option value="">Select Subject</option>
                            <option v-for="sub in subjects" :key="sub.id" :value="sub.id">
                                {{ sub.name }}
                            </option>
                        </select>

                        <!-- Topic -->
                        <input v-model="form.topic" placeholder="Topic" class="border p-2"/>
                        <input v-model="form.sub_topic" placeholder="Sub Topic" class="border p-2"/>
                        <input v-model="form.section" placeholder="Section" class="border p-2"/>
                        <input v-model="form.sub_section" placeholder="Sub Section" class="border p-2"/>
                        <input v-model="form.category" placeholder="Category" class="border p-2"/>
                        <input v-model="form.board" placeholder="Board" class="border p-2"/>

                        <!-- Year Dropdown -->
                        <label class="font-semibold">Year</label>
                        <select v-model="form.year" class="border p-2">
                            <option value="">Select Year</option>
                            <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
                        </select>

                        <!-- Class Dropdown -->
                        <label class="font-semibold">Class</label>
                        <select v-model="form.class" class="border p-2">
                            <option value="">Select Class</option>
                            <option v-for="c in classes" :key="c.value" :value="c.value">{{ c.text }}</option>
                        </select>
                    </div>

                    <!-- Upload Image -->
                    <label class="font-semibold mt-2">Upload Image</label>
                    <input type="file" @change="previewImage" class="w-full border p-2"/>
                    <img v-if="preview" :src="preview" class="h-32 mt-2 border" />

                    <!-- Submit -->
                    <button @click="submit"
                        class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
                        :disabled="form.processing">
                        Save Post
                    </button>

                </div>
            </div>
        </div>
    </div>
</AuthenticatedLayout>
</template>