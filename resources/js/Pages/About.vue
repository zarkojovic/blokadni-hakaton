<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                About
            </h2>
        </template>

        <div class="p-6 bg-white dark:bg-gray-800 dark:text-gray-200">
            <p>
                This is a simple example of a Laravel Jetstream application with Inertia.js.
            </p>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
