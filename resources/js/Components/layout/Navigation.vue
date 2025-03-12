<script setup>
import Stepper from 'primevue/stepper';
import StepList from 'primevue/steplist';
import StepPanels from 'primevue/steppanels';
import {PhUpload, PhEye, PhTable} from "@phosphor-icons/vue";
import Button from 'primevue/button';
import StepItem from 'primevue/stepitem';
import Step from 'primevue/step';
import StepPanel from 'primevue/steppanel';
</script>

<template>
    <div className="card flex justify-center">
        <StepList>
            <Step value="1">
                <PhUpload :size="32"/>
                <p>Otpremi Dokument</p></Step>
            <Step value="2">
                <PhEye :size="32"/>
                Pregled Idiota
            </Step>
            <Step value="3">
                <PhTable :size="32"/>
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
