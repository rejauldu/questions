<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref, watch, nextTick, onUnmounted } from 'vue'
import axios from 'axios'
import 'mathlive'

/* =======================
   PROPS
======================= */
const props = defineProps({
    institutions: Array,
    boards: Array,
    years: Array,
    classes: Array
})

/* =======================
   FORM INITIALIZATION
======================= */
const form = useForm({
    article: '',
    a: '',
    b: '',
    c: '',
    d: '',
    answer: '',
    institution_id: '',
    subject_id: '',
    board_id: '',
    topic: '',
    sub_topic: '',
    section: '',
    sub_section: '',
    category: '',
    year: '',
    class: '',
    url: null,
})

/* =======================
   DYNAMIC SUBJECTS
======================= */
const subjects = ref([])

watch(() => form.institution_id, async (newVal) => {
    if (!newVal) {
        subjects.value = []
        form.subject_id = ''
        return
    }

    try {
        const res = await axios.get(
            route('api.posts.subjects-by-institution'),
            { params: { institution_id: newVal } }
        )
        subjects.value = res.data
        form.subject_id = ''
    } catch (e) {
        console.error("Error fetching subjects:", e)
    }
})

/* =======================
   MATH & INPUT TRACKING
======================= */
const showMath = ref(false)
const mathRef = ref(null)
const lastFocusedInput = ref(null)

// Track which input/textarea was last clicked so math inserts there
const updateFocus = (e) => {
    lastFocusedInput.value = {
        name: e.target.name,
        selectionStart: e.target.selectionStart,
        selectionEnd: e.target.selectionEnd,
        ref: e.target
    }
}

const toggleMathKeyboard = () => {
    showMath.value = !showMath.value
}

const insertMath = () => {
    if (!mathRef.value || !lastFocusedInput.value) {
        alert("Please click inside an input field first.")
        return
    }

    const raw = mathRef.value.getValue()
    if (!raw) return

    const wrapped = `\\(${raw}\\)`
    const fieldName = lastFocusedInput.value.name
    const targetRef = lastFocusedInput.value.ref
    
    const start = targetRef.selectionStart
    const end = targetRef.selectionEnd

    // Update the form value at the cursor position
    form[fieldName] = 
        form[fieldName].substring(0, start) + 
        wrapped + 
        form[fieldName].substring(end)

    // Reset math field and return focus to the input
    mathRef.value.setValue('')
    nextTick(() => {
        targetRef.focus()
        const newPos = start + wrapped.length
        targetRef.setSelectionRange(newPos, newPos)
    })
    const inlineImageInput = ref(null);
    const uploadInlineImage = async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        // Optional: Show a loading state if you want
        const formData = new FormData();
        formData.append('image', file);

        try {
            const res = await axios.post(route('api.image.upload'), formData);
            const imageUrl = res.data.url;

            // Create the HTML Image Tag
            const imgTag = `<img src="${imageUrl}" alt="diagram" style="max-height:200px; display:block; margin:10px 0;" />`;

            // We use the article textarea specifically for this
            const targetRef = document.getElementsByName('article')[0];
            const start = targetRef.selectionStart || 0;
            const end = targetRef.selectionEnd || 0;

            // Insert at cursor position
            form.article = 
                form.article.substring(0, start) + 
                imgTag + 
                form.article.substring(end);

            // Reset the file input so the same image can be uploaded again if needed
            e.target.value = '';
            
            nextTick(() => {
                targetRef.focus();
                const newPos = start + imgTag.length;
                targetRef.setSelectionRange(newPos, newPos);
            });

        } catch (error) {
            console.error("Upload failed", error);
            alert("Failed to upload image. Make sure it's an image under 2MB.");
        }
    };
}

/* =======================
   IMAGE PREVIEW & CLEANUP
======================= */
const preview = ref(null)
const fileInput = ref(null)

const previewImage = (e) => {
    const file = e.target.files[0]
    if (!file) return

    form.url = file
    if (preview.value) URL.revokeObjectURL(preview.value)
    preview.value = URL.createObjectURL(file)
}

onUnmounted(() => {
    if (preview.value) URL.revokeObjectURL(preview.value)
})

/* =======================
   SUBMIT
======================= */
const submit = () => {
    form.post(route('questions.store'), {
        forceFormData: true,
        onSuccess: () => {
            if (preview.value) URL.revokeObjectURL(preview.value)
            preview.value = null
            if (fileInput.value) fileInput.value.value = null
            form.reset()
        }
    })
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Create Question" />

        <template #header>
            <h2 class="text-xl font-semibold text-gray-800 leading-tight">
                Create New Question
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg border">
                    <div class="p-6 space-y-6" :class="{ 'opacity-50 pointer-events-none': form.processing }">
                        
                        <div class="sticky top-0 z-10 bg-gray-50 p-4 border-b rounded flex flex-col gap-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Math Equation Editor</span>
                                <div class="flex gap-2">
                                    <button @click="toggleMathKeyboard" type="button"
                                        class="px-4 py-1.5 text-sm bg-white border rounded shadow-sm hover:bg-gray-50">
                                        {{ showMath ? 'Hide Keyboard' : 'Show Keyboard' }}
                                    </button>
                                    <button @click="insertMath" type="button"
                                        class="px-4 py-1.5 text-sm bg-indigo-600 text-white rounded shadow-sm hover:bg-indigo-700">
                                        Insert at Cursor
                                    </button>
                                </div>
                            </div>
                            <div v-show="showMath" class="bg-white border rounded shadow-inner p-2">
                                <math-field ref="mathRef" style="width:100%; min-height:60px; font-size: 1.2rem;" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div class="flex flex-col gap-1">
                                <div class="flex justify-between items-center">
                                    <label class="font-bold text-gray-700">Article (উদ্দীপক)</label>
                                    
                                    <input 
                                        type="file" 
                                        ref="inlineImageInput" 
                                        class="hidden" 
                                        accept="image/*" 
                                        @change="uploadInlineImage"
                                    />
                                    
                                    <button 
                                        type="button" 
                                        @click="$refs.inlineImageInput.click()"
                                        class="text-xs font-semibold bg-gray-100 text-indigo-600 px-3 py-1 rounded border border-indigo-200 hover:bg-indigo-50"
                                    >
                                        + Insert Image in Text
                                    </button>
                                </div>

                                <textarea 
                                    name="article"
                                    v-model="form.article" 
                                    @click="updateFocus" @blur="updateFocus"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 min-h-[150px] font-mono text-sm" 
                                    placeholder="Write article. Use the Math editor or 'Insert Image' button..."
                                ></textarea>
                                <div v-if="form.errors.article" class="text-red-600 text-xs">{{ form.errors.article }}</div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div v-for="opt in ['a', 'b', 'c', 'd']" :key="opt">
                                    <label class="block text-sm font-medium text-gray-700 capitalize">Option {{ opt }}</label>
                                    <input 
                                        :name="opt"
                                        v-model="form[opt]" 
                                        @click="updateFocus" @blur="updateFocus"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    />
                                    <div v-if="form.errors[opt]" class="text-red-600 text-xs mt-1">{{ form.errors[opt] }}</div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 text-red-600">Correct Answer</label>
                                <input 
                                    name="answer"
                                    v-model="form.answer" 
                                    @click="updateFocus" @blur="updateFocus"
                                    class="mt-1 block w-full border-red-300 rounded-md shadow-sm" 
                                    placeholder="e.g., A or B"
                                />
                            </div>

                            <hr />

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="text-xs font-semibold uppercase text-gray-500">Institution</label>
                                    <select v-model="form.institution_id" class="w-full mt-1 border-gray-300 rounded-md">
                                        <option value="">Select Institution</option>
                                        <option v-for="i in institutions" :key="i.id" :value="i.id">{{ i.name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold uppercase text-gray-500">Subject</label>
                                    <select v-model="form.subject_id" class="w-full mt-1 border-gray-300 rounded-md">
                                        <option value="">Select Subject</option>
                                        <option v-for="s in subjects" :key="s.id" :value="s.id">{{ s.name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold uppercase text-gray-500">Board</label>
                                    <select v-model="form.board_id" class="w-full mt-1 border-gray-300 rounded-md">
                                        <option value="">Select Board</option>
                                        <option v-for="b in boards" :key="b.id" :value="b.id">{{ b.name }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <input v-model="form.topic" placeholder="Topic" class="border-gray-300 rounded-md text-sm" />
                                <input v-model="form.sub_topic" placeholder="Sub Topic" class="border-gray-300 rounded-md text-sm" />
                                <div>
                                    <select v-model="form.category" class="w-full mt-1 border-gray-300 rounded-md text-sm">
                                        <option value="">Select Category</option>
                                        <option value="MCQ">MCQ (Multiple Choice)</option>
                                        <option value="CQ">CQ (Creative Question)</option>
                                    </select>
                                    <div v-if="form.errors.category" class="text-red-600 text-xs mt-1">{{ form.errors.category }}</div>
                                </div>
                                <div>
                                    <select v-model="form.class" class="w-full mt-1 border-gray-300 rounded-md text-sm">
                                        <option value="">Class</option>
                                        <option v-for="c in classes" :key="c.value" :value="c.value">{{ c.text }}</option>
                                    </select>
                                    <div v-if="form.errors.class" class="text-red-600 text-xs mt-1">{{ form.errors.class }}</div>
                                </div>
                            </div>

                            <div class="border-2 border-dashed border-gray-200 p-4 rounded-lg">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Attachment Image</label>
                                <input type="file" ref="fileInput" @change="previewImage" class="text-sm" />
                                <div v-if="preview" class="mt-4 relative inline-block">
                                    <img :src="preview" class="h-40 object-contain rounded border shadow-sm" />
                                    <button @click="preview=null; form.url=null" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 text-xs">X</button>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button
                                    @click="submit"
                                    :disabled="form.processing"
                                    class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-lg shadow-lg hover:bg-indigo-700 disabled:bg-gray-400 transition-colors"
                                >
                                    {{ form.processing ? 'Saving Question...' : 'Save Question' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>