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
    ans: '',
    answer: '', 
    institution_id: '',
    subject_id: '',
    board_id: '',
    chapter: '',
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
const inlineImageInput = ref(null)

const updateFocus = (e) => {
    lastFocusedInput.value = {
        name: e.target.name,
        selectionStart: e.target.selectionStart,
        selectionEnd: e.target.selectionEnd,
        ref: e.target
    }
}

const toggleMathKeyboard = () => { showMath.value = !showMath.value }

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

    form[fieldName] = form[fieldName].substring(0, start) + wrapped + form[fieldName].substring(end)

    mathRef.value.setValue('')
    nextTick(() => {
        targetRef.focus()
        const newPos = start + wrapped.length
        targetRef.setSelectionRange(newPos, newPos)
    })
}

const uploadInlineImage = async (e) => {
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('image', file);

    try {
        const res = await axios.post(route('api.image.upload'), formData);
        const imageUrl = res.data.url;
        const imgTag = `<img src="${imageUrl}" alt="diagram" class="inline-block align-middle" />`;

        const targetRef = document.getElementsByName('article')[0];
        const start = targetRef.selectionStart || 0;
        const end = targetRef.selectionEnd || 0;

        form.article = form.article.substring(0, start) + imgTag + form.article.substring(end);
        e.target.value = '';
        
        nextTick(() => {
            targetRef.focus();
            const newPos = start + imgTag.length;
            targetRef.setSelectionRange(newPos, newPos);
        });
    } catch (error) {
        alert("Upload failed.");
    }
};

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
            <h2 class="text-xl font-semibold text-gray-800 leading-tight">Create New Question</h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-4">
                
                <div class="sticky top-0 z-10 bg-white p-4 border rounded-lg shadow-sm flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase text-gray-500 tracking-wider">Editor Tools</span>
                        <div class="flex gap-2">
                            <button @click="toggleMathKeyboard" type="button" class="px-3 py-1 text-xs border rounded hover:bg-gray-50">
                                {{ showMath ? 'Hide Math' : 'Math Keyboard' }}
                            </button>
                            <button @click="insertMath" type="button" class="px-3 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                Insert Equation
                            </button>
                        </div>
                    </div>
                    <div v-show="showMath" class="border rounded p-2">
                        <math-field ref="mathRef" style="width:100%; min-height:50px; font-size: 1.1rem;" />
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg border shadow-sm space-y-6">
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 pb-4 border-b">
                        <div class="col-span-1">
                            <label class="text-xs font-bold text-gray-500 uppercase">Institution</label>
                            <select v-model="form.institution_id" class="w-full mt-1 border-gray-300 rounded-md text-sm">
                                <option value="">Select Institution</option>
                                <option v-for="i in institutions" :key="i.id" :value="i.id">{{ i.name }}</option>
                            </select>
                        </div>
                        <div class="col-span-1">
                            <label class="text-xs font-bold text-gray-500 uppercase">Subject</label>
                            <select v-model="form.subject_id" class="w-full mt-1 border-gray-300 rounded-md text-sm">
                                <option value="">Select Subject</option>
                                <option v-for="s in subjects" :key="s.id" :value="s.id">{{ s.name }}</option>
                            </select>
                        </div>
                        <div class="col-span-1">
                            <label class="text-xs font-bold text-gray-500 uppercase">Board</label>
                            <select v-model="form.board_id" class="w-full mt-1 border-gray-300 rounded-md text-sm">
                                <option value="">Board</option>
                                <option v-for="b in boards" :key="b.id" :value="b.id">{{ b.name }}</option>
                            </select>
                        </div>
                        <div class="col-span-1">
                            <label class="text-xs font-bold text-gray-500 uppercase">Year</label>
                            <select v-model="form.year" class="w-full mt-1 border-gray-300 rounded-md text-sm">
                                <option value="">Year</option>
                                <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
                            </select>
                        </div>
                        <div class="col-span-2 md:col-span-2 lg:col-span-2">
                            <label class="text-xs font-bold text-gray-500 uppercase">Chapter</label>
                            <input v-model="form.chapter" class="w-full mt-1 border-gray-300 rounded-md text-sm" placeholder="Chapter Name/No" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label class="font-bold text-gray-700">Article (উদ্দীপক / প্রশ্ন)</label>
                            <input type="file" ref="inlineImageInput" class="hidden" accept="image/*" @change="uploadInlineImage" />
                            <button type="button" @click="$refs.inlineImageInput.click()" class="text-[10px] uppercase font-bold bg-indigo-50 text-indigo-700 px-2 py-1 rounded border border-indigo-100 hover:bg-indigo-100">
                                + Add Inline Image
                            </button>
                        </div>
                        <textarea name="article" v-model="form.article" @click="updateFocus" @blur="updateFocus"
                            class="w-full border-gray-300 rounded-md shadow-sm min-h-[140px] text-sm font-mono" placeholder="Write question details..."></textarea>
                    </div>

                    <div v-if="form.category === 'MCQ'" class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div v-for="opt in ['a', 'b', 'c', 'd']" :key="opt" class="flex items-center gap-2">
                            <span class="font-bold text-gray-400 uppercase text-xs">{{ opt }}.</span>
                            <input :name="opt" v-model="form[opt]" @click="updateFocus" @blur="updateFocus"
                                class="w-full border-gray-300 rounded-md text-sm px-3 py-2 shadow-sm" :placeholder="'Option ' + opt" />
                        </div>
                    </div>

                    <div v-if="form.category === 'CQ'" class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">Solution / Answer Detail</label>
                        <textarea name="answer" v-model="form.answer" @click="updateFocus" @blur="updateFocus"
                            class="w-full border-gray-300 rounded-md text-sm min-h-[100px]" placeholder="Write the CQ solution here..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 items-end pt-4 border-t">
                        <div class="col-span-1">
                            <label class="text-xs font-bold text-gray-500 uppercase">Category</label>
                            <select v-model="form.category" class="w-full mt-1 border-gray-300 rounded-md text-sm">
                                <option value="">Select Category</option>
                                <option value="MCQ">MCQ</option>
                                <option value="CQ">CQ</option>
                            </select>
                        </div>
                        <div class="col-span-1">
                            <label class="text-xs font-bold text-gray-500 uppercase">Class</label>
                            <select v-model="form.class" class="w-full mt-1 border-gray-300 rounded-md text-sm">
                                <option value="">Select Class</option>
                                <option v-for="c in classes" :key="c.value" :value="c.value">{{ c.text }}</option>
                            </select>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="text-xs font-bold text-gray-500 uppercase">Correct Ans {{ form.category === 'MCQ' ? '(A/B/C/D)' : '' }}</label>
                            <input name="ans" v-model="form.ans" class="w-full mt-1 border-gray-300 rounded-md text-sm py-2 px-3 focus:ring-indigo-500" placeholder="e.g. A" />
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <button @click="submit" :disabled="form.processing" class="w-full py-2.5 bg-indigo-600 text-white font-bold rounded-md hover:bg-indigo-700 shadow transition-all text-sm uppercase tracking-wide">
                                {{ form.processing ? 'Saving...' : 'Save Question' }}
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col items-center pt-2">
                        <input type="file" ref="fileInput" @change="previewImage" class="hidden" />
                        <button type="button" @click="$refs.fileInput.click()" class="text-[11px] text-gray-400 hover:text-indigo-600 flex items-center gap-1">
                             📎 {{ form.url ? 'Change Attachment' : 'Attach Single Image (Alternative to Text)' }}
                        </button>
                        <div v-if="preview" class="mt-3 relative">
                            <img :src="preview" class="h-24 rounded border shadow-sm" />
                            <button @click="preview=null; form.url=null" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 text-xs">×</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>