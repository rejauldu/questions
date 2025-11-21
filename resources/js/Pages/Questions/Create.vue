<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import 'mathlive';

// Form
const form = useForm({
    article: '',
    a: '',
    b: '',
    c: '',
    d: '',
    answer: '',
    subject: '',
    topic: '',
    sub_topic: '',
    section: '',
    sub_section: '',
    category: '',
    board: '',
    year: '',
    class: '',
});

// Math field
const showMath = ref(false);
const mathRef = ref(null);

function toggleMathKeyboard() {
    showMath.value = !showMath.value;
}

function insertMath() {
    if (!mathRef.value) return;

    let raw = mathRef.value.getValue();
    if (!raw) return;

    // Ensure wrapper always added
    const wrapped = `\\(${raw}\\)`;

    const textarea = document.querySelector('#article-textarea');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;

    form.article = 
        form.article.substring(0, start) +
        wrapped +
        form.article.substring(end);

    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = start + wrapped.length;
}

function submit() {
    form.post(route('questions.store'), {
        onSuccess: page => {
            // The server can return redirect (like normal redirect)
            // Or you can manually visit the new page:
            if (page.props.redirect) {
                window.location.href = page.props.redirect;
            }
        }
    });
}
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
                <div class="p-6 text-gray-900 space-y-4">

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
                    <textarea
                        id="article-textarea"
                        v-model="form.article"
                        class="w-full border p-2 h-32"
                        placeholder="Write article with math..."
                    ></textarea>

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

                    <!-- Metadata -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <input v-model="form.subject" placeholder="Subject" class="border p-2"/>
                        <input v-model="form.topic" placeholder="Topic" class="border p-2"/>
                        <input v-model="form.sub_topic" placeholder="Sub Topic" class="border p-2"/>
                        <input v-model="form.section" placeholder="Section" class="border p-2"/>
                        <input v-model="form.sub_section" placeholder="Sub Section" class="border p-2"/>
                        <input v-model="form.category" placeholder="Category" class="border p-2"/>
                        <input v-model="form.board" placeholder="Board" class="border p-2"/>
                        <input v-model="form.year" placeholder="Year" class="border p-2"/>
                        <input v-model="form.class" placeholder="Class" class="border p-2"/>
                    </div>

                    <!-- Submit -->
                    <button
                        @click="submit"
                        class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
                        :disabled="form.processing"
                    >
                        Save Post
                    </button>

                </div>
            </div>
        </div>
    </div>
</AuthenticatedLayout>
</template>