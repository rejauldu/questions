<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex flex-col gap-4">
                        <p>Welcome to your dashboard!</p>

                        <!-- Links -->
                        <Link
                            :href="route('questions.create')"
                            class="inline-block rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700"
                        >
                            Create New Post
                        </Link>

                        <Link
                            :href="route('questions.index')"
                            class="inline-block rounded-md bg-green-600 px-4 py-2 text-white hover:bg-green-700"
                        >
                            List All Posts
                        </Link>

                        <!-- Add more links here later -->
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>