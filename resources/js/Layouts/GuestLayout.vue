<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 px-4 pt-10"
    >
        <!-- Logo (UNCHANGED) -->
        <div class="mb-6">
            <a href="/">
                <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />
            </a>
        </div>

        <!-- Card -->
        <div
            class="w-full max-w-xl mx-auto overflow-hidden rounded-xl bg-white px-6 py-6 shadow-lg"
        >
            <slot />
        </div>
    </div>
</template>
