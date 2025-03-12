<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Accordion from 'primevue/accordion';
import AccordionPanel from 'primevue/accordionpanel';
import AccordionHeader from 'primevue/accordionheader';
import AccordionContent from 'primevue/accordioncontent';
import {ref} from 'vue';
const active = ref('0');
</script>

<template>
    <Head title="About"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                O Nama
            </h2>
        </template>
    <main class="mt-2 w-10/12 mx-auto">
        <div class="bg-white dark:bg-gray-800 dark:text-gray-200">
            <div class="flex mb-4 gap-2 justify-end">
                <Button @click="active = '0'" rounded label="1" class="w-8 h-8 p-0" :outlined="active !== '0'" />
                <Button @click="active = '1'" rounded label="2" class="w-8 h-8 p-0" :outlined="active !== '1'" />
                <Button @click="active = '2'" rounded label="3" class="w-8 h-8 p-0" :outlined="active !== '2'" />
                <Button @click="active = '3'" rounded label="3" class="w-8 h-8 p-0" :outlined="active !== '3'" />
            </div>

            <Accordion v-model:value="active">
                <AccordionPanel value="0">
                    <AccordionHeader>Koja je svrha aplikacije?</AccordionHeader>
                    <AccordionContent>
                        <p class="m-0">
                            Svrha Elaboratora je da automatski pretvori kompleksne elaborat fajlove u jasan idiot i tabelarni prikaz, kako bi se ideje i planovi akcije brže i efikasnije formirale.
                        </p>
                    </AccordionContent>
                </AccordionPanel>
                <AccordionPanel value="1">
                    <AccordionHeader>Kome služi aplikacija?</AccordionHeader>
                    <AccordionContent>
                        <p class="m-0">
                            Aplikacija prvenstveno služi pripadnicima radnih grupa koji učestvuju u pisanju predloga tokom studentskih blokada. One su trenutno zadužene za ubrzavanje izrade idiot fajlova i tabela.najviše olakšava posao.
                        </p>
                    </AccordionContent>
                </AccordionPanel>
                <AccordionPanel value="2">
                    <AccordionHeader>Kako je nastala aplikacija?</AccordionHeader>
                    <AccordionContent>
                        <p class="m-0">
                            Aplikacija je nastala na blokadnom hakatonu koji se održao na FON-u 11-12. marta 2025. godine, u toku studentskih blokada. Ideja je potekla iz razgovora sa pripadnicima radnih grupa koji su nama podelili svoj način rada.
                        </p>
                    </AccordionContent>
                </AccordionPanel>
                <AccordionPanel value="3">
                    <AccordionHeader>Da li neko može da dođe u posed mojim fajlovima?</AccordionHeader>
                    <AccordionContent>
                        <p class="m-0">
                            Ne, niko ne može doći u posed vaših fajlova. Fajlovi se ne čuvaju se nigde. Nakon generisanja sažetka i tabele, svi podaci se brišu automatski. Privatnost korisnika, sigurnost budućih predloga i radnih akcija su nam prioritet od početka.
                        </p>
                    </AccordionContent>
                </AccordionPanel>
            </Accordion>

        </div>
    </main>

    </AuthenticatedLayout>
</template>

<style scoped>

</style>
