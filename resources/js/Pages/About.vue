<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <Head title="About"/>
    <AuthenticatedLayout>
        <div class="container mx-auto p-6">
            <h2 class="font-semibold text-3xl leading-tight text-center mb-4">
                O Aplikaciji
            </h2>
            <div class=" p-6 rounded-lg shadow-md">
                <p class="text-lg">
                    This is a simple example of a Laravel Jetstream application with Inertia.js.
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
