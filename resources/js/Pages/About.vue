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
    <main class="w-10/12 mx-auto">
        <div class="bg-white dark:bg-gray-800 dark:text-gray-200">
            <div class="flex mb-4 gap-2 justify-end">
                <Button @click="active = '0'" rounded label="1" class="w-8 h-8 p-0" :outlined="active !== '0'" />
                <Button @click="active = '1'" rounded label="2" class="w-8 h-8 p-0" :outlined="active !== '1'" />
                <Button @click="active = '2'" rounded label="3" class="w-8 h-8 p-0" :outlined="active !== '2'" />
            </div>

            <Accordion v-model:value="active">
                <AccordionPanel value="0">
                    <AccordionHeader>Zašto baš mi?</AccordionHeader>
                    <AccordionContent>
                        <p class="m-0">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                    </AccordionContent>
                </AccordionPanel>
                <AccordionPanel value="1">
                    <AccordionHeader>Kome služi aplikacije?</AccordionHeader>
                    <AccordionContent>
                        <p class="m-0">
                            Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim
                            ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Consectetur, adipisci velit, sed quia non numquam eius modi.
                        </p>
                    </AccordionContent>
                </AccordionPanel>
                <AccordionPanel value="2">
                    <AccordionHeader>Kako je nastala aplikacija?</AccordionHeader>
                    <AccordionContent>
                        <p class="m-0">
                            At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa
                            qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus.
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
