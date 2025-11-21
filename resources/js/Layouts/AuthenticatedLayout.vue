<script setup>
import AppHeader from '@/Layouts/Header.vue'
import AppFooter from '@/Layouts/Footer.vue'
</script>

<template>
    <div class="min-h-screen bg-gray-100">

        <!-- Header -->
        <AppHeader />

        <!-- Page heading -->
        <header class="bg-white shadow" v-if="$slots.header">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Page content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <AppFooter />
    </div>
</template>