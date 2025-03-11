<script setup>
import Button from 'primevue/button';
import StepPanel from 'primevue/steppanel';

</script>
<template>
    <StepPanel v-slot="{ activateCallback }" value="2">
        <div class="flex flex-col h-48">
            <div
                class="border-2 border-dashed border-surface-200 dark:border-surface-700 rounded bg-surface-50 dark:bg-surface-950 flex-auto flex justify-center items-center font-medium">Content
                I</div>
        </div>
        <div class="flex pt-6 justify-end">
            <Button label="Next" icon="pi pi-arrow-right" iconPos="right" @click="activateCallback('2')" />
        </div>
    </StepPanel>
</template>
