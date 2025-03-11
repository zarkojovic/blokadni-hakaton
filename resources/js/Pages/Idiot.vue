<script setup>
import { Head } from '@inertiajs/vue3';
import IdiotPreview from '@/Pages/Steps/IdiotPreview.vue';
import StepperLayout from '@/Layouts/StepperLayout.vue';

</script>

<template>
    <Head title="Welcome" />

    <StepperLayout>
        <IdiotPreview></IdiotPreview>
    </StepperLayout>
</template>
