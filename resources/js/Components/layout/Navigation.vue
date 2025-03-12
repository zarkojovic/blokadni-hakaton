<script setup>
import StepList from 'primevue/steplist';
import {PhUpload, PhEye, PhTable} from '@phosphor-icons/vue';
import Step from 'primevue/step';
import {inject} from 'vue';

const activeTab = inject('activeTab');


</script>

<template>
    <div class="card flex justify-center items-center mb-3">
        <StepList>
            <Step value="1" :disabled="activeTab !== '1'">
                <PhUpload :size="24"/>
                <p>Otpremi Dokument</p>
            </Step>
            <Step value="2" :disabled="activeTab !== '2'">
                <PhEye :size="24"/>
                Pregled Idiota
            </Step>
            <Step value="3" :disabled="activeTab !== '3'">
                <PhTable :size="24"/>
                Pregled Tabele
            </Step>
        </StepList>
    </div>
</template>
<style>
.p-step-header .p-step-title {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
}
</style>
