<script setup>
import Stepper from 'primevue/stepper';
import StepPanels from 'primevue/steppanels';
import Navigation from '@/Components/layout/Navigation.vue';
</script>

<template>
    <Stepper value="1" class="basis-[50rem]">
        <Navigation></Navigation>
<!--        <main>-->
            <StepPanels class="w-full">
                <slot/>
            </StepPanels>
<!--        </main>-->
    </Stepper>
</template>
