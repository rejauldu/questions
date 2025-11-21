<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

// Props passed from controller
defineProps({
  post: Object
});
</script>

<template>
<AuthenticatedLayout>
    <Head title="View Question" />

    <template #header>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            View Question
        </h2>
    </template>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">

                    <!-- Back button -->
                    <Link
                        href="{{ route('questions.index') }}"
                        class="inline-block px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300"
                    >
                        Back to List
                    </Link>

                    <!-- Article / Question -->
                    <div>
                        <h3 class="font-semibold">Article / Question:</h3>
                        <p class="mt-1 whitespace-pre-line">{{ post.article }}</p>
                    </div>

                    <!-- Options -->
                    <div>
                        <h3 class="font-semibold">Options:</h3>
                        <ul class="list-disc list-inside mt-1 space-y-1">
                            <li>ক) {{ post.a }}</li>
                            <li>খ) {{ post.b }}</li>
                            <li>গ) {{ post.c }}</li>
                            <li>ঘ) {{ post.d }}</li>
                        </ul>
                    </div>

                    <!-- Answer -->
                    <div>
                        <h3 class="font-semibold">Answer:</h3>
                        <p>{{ post.answer }}</p>
                    </div>

                    <!-- Metadata -->
                    <div class="grid grid-cols-2 gap-2 mt-4">
                        <div><strong>Subject:</strong> {{ post.subject }}</div>
                        <div><strong>Topic:</strong> {{ post.topic }}</div>
                        <div><strong>Sub Topic:</strong> {{ post.sub_topic }}</div>
                        <div><strong>Section:</strong> {{ post.section }}</div>
                        <div><strong>Sub Section:</strong> {{ post.sub_section }}</div>
                        <div><strong>Category:</strong> {{ post.category }}</div>
                        <div><strong>Board:</strong> {{ post.board }}</div>
                        <div><strong>Year:</strong> {{ post.year }}</div>
                        <div><strong>Class:</strong> {{ post.class }}</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</AuthenticatedLayout>
</template>