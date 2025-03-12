<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Navigation from '@/Components/layout/Navigation.vue';
import StepperLayout from '@/Layouts/StepperLayout.vue';
import Form from '@/Pages/Steps/Form.vue';
// defineProps({
//     canLogin: {
//         type: Boolean,
//     },
//     canRegister: {
//         type: Boolean,
//     },
//     laravelVersion: {
//         type: String,
//         required: true,
//     },
//     phpVersion: {
//         type: String,
//         required: true,
//     },
// });

// function handleImageError() {
//     document.getElementById('screenshot-container')?.classList.add('!hidden');
//     document.getElementById('docs-card')?.classList.add('!row-span-1');
//     document.getElementById('docs-card-content')?.classList.add('!flex-row');
//     document.getElementById('background')?.classList.add('!hidden');
// }
</script>

<template>
    <Head title="Welcome" />
<!--    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">-->

<!--        <div-->
<!--            class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"-->
<!--        >-->
<!--            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">-->
<!--                <header-->
<!--                    class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3"-->
<!--                >-->
<!--                </header>-->

<!--                <main class="mt-6">-->
<!--                </main>-->

<!--                <footer-->
<!--                    class="py-16 text-center text-sm text-black dark:text-white/70"-->
<!--                >-->
<!--                </footer>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <div class="w-8/12 mx-auto mt-10">
        <StepperLayout>
<!--            <Form></Form>-->
        </StepperLayout>
    </div>
</template>
