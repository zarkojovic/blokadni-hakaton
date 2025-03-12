<script setup>
import Stepper from 'primevue/stepper';
import StepPanels from 'primevue/steppanels';
import Button from 'primevue/button';
import StepPanel from 'primevue/steppanel';
import {provide, ref} from 'vue';
import FileUpload from 'primevue/fileupload';
import {useForm} from '@inertiajs/vue3';
import axios from 'axios';
import Navigation from '@/Components/layout/Navigation.vue';
import ProgressSpinner from 'primevue/progressspinner';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {PhDownload} from '@phosphor-icons/vue';

const totalSize = ref(0);
const totalSizePercent = ref(0);
const filesMy = ref(null);
const activeTab = ref('1');
const loader = ref(false);
const data = ref('');

const onRemoveTemplatingFile = (file, removeFileCallback, index) => {
    removeFileCallback(index);
    totalSize.value -= parseInt(formatSize(file.size));
    totalSizePercent.value = totalSize.value / 10;
};

const onClearTemplatingUpload = (clear) => {
    clear();
    totalSize.value = 0;
    totalSizePercent.value = 0;
};

const onSelectedFiles = (event) => {
    // Očistiti prethodno izabrane fajlove
    filesMy.value = null;
    // Dodati samo jedan fajl u listu
    if (event.files && event.files.length > 0) {
        form.elaboratFile = event.files[0];
        filesMy.value = event.files[0];
        totalSize.value = parseInt(formatSize(event.files[0].size));
        totalSizePercent.value = totalSize.value / 10;
    }

};

const tableData = ref('');
const tabularText = ref('');
const title = ref('');
const uploadEvent = (callback) => {
    emptyTableData.value = false;
    // Uveriti se da je samo jedan fajl izabran pre nego što se otprema
    if (filesMy.value != null) {
        totalSizePercent.value = totalSize.value / 10;

        // Pripremi FormData objekat
        const formData = new FormData();
        formData.append('elaboratFile', filesMy.value); // Dodajte fajl
        formData.append('type', 'content'); // Dodajte fajl
        console.log(filesMy.value);
        // Pošaljite zahtev koristeći axios
        loader.value = true;
        axios.post(route('gemini.documents'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data', // Postavljanje headera na multipart/form-data
            },
        }).then(response => {
            loader.value = false;
            activeTab.value = '2';

            title.value = response.data.content.split('###')[0].trim();
            data.value = response.data.content.split('###')[1].trim();
            tableData.value = response.data.tabular;
            tabularText.value = response.data.tabularText;

            if (tableData.value.length === 0) {
                emptyTableData.value = true;
                activeTab.value = '1';
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            }

            console.log(tableData.value); // Odgovor u JSON formatu, ako server odgovara sa JSON-om
            // Možete obraditi odgovor ovde, npr. spremiti linkove do fajlova
        }).catch(error => {
            console.log(error.response.data); // Obrada grešaka
        });
    }
};

const onTemplatedUpload = () => {
    // Zamenjujemo prethodni fajl sa novim fajlom
    if (filesMy.value) {
        uploadedFiles.value = null; // Održi samo jedan fajl u nizu
        filesMy.value = null; // Resetuj selektovani fajl
    }
};

provide('activeTab', activeTab);

const formatSize = (bytes) => {
    const k = 1024;
    const dm = 3;
    const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];

    if (bytes === 0) {
        return `0 ${sizes[0]}`;
    }

    const i = Math.floor(Math.log(bytes) / Math.log(k));
    const formattedSize = parseFloat((bytes / Math.pow(k, i)).toFixed(dm));

    return `${formattedSize} ${sizes[i]}`;
};

const form = useForm({
    elaboratFile: null,
});

const tableTabAction = () => {
    activeTab.value = '3';
};

const generateDocumentsForm = useForm({
    idiot: null,
    tabular: null,
});

const idiotLink = ref('');
const tabelarView = ref('');

const finishDocuments = () => {
    generateDocumentsForm.idiot = title.value + '###' + data.value;
    generateDocumentsForm.tabular = tabularText.value;

    generateDocumentsForm.post(route('gemini.text.documents'), {
        preserveScroll: true,
        onSuccess: (page) => {
            const {fileIdiot, fileTabular} = page.props;
            activeTab.value = '4';
            idiotLink.value = fileIdiot;
            tabelarView.value = fileTabular;
        },
        onError: (errors) => {
            generateDocumentsForm.errors = errors;
            console.log(errors);
        },
    });
};

const restartForm = () => {
    window.location.href = '/';
};

const emptyTableData = ref(false);

</script>

<template>
    <AuthenticatedLayout>
        <Stepper :value="activeTab" class="basis-[50rem] mt-10">
            <Navigation></Navigation>
            <!--        <main>-->
            <StepPanels class="w-full">
                <StepPanel v-slot="{ activateCallback }" value="1">
                    <div class="flex flex-col h-48">
                        <div class="card">
                            <FileUpload :multiple="false" @upload="onTemplatedUpload" accept=".doc,.docx,.pdf"
                                        :maxFileSize="10000000" @select="onSelectedFiles">
                                <template #header="{ chooseCallback, uploadCallback, clearCallback, files }">
                                    <div class="flex justify-center items-center mx-auto flex-1 gap-4 w-full">

                                        <Button @click="chooseCallback()" icon="pi pi-file" rounded outlined
                                                v-if="!files.length"
                                                severity="secondary"></Button>
                                        <Button @click="uploadEvent(uploadCallback)" icon="pi pi-cloud-upload"
                                                v-if="files.length"
                                                rounded
                                                outlined severity="success" :disabled="!files.length"></Button>
                                        <Button @click="clearCallback()" v-if="files.length" icon="pi pi-times" rounded
                                                outlined
                                                severity="danger" :disabled="!files || files.length === 0"></Button>

                                    </div>
                                </template>
                                <template
                                    #content="{ files, uploadedFiles, removeUploadedFileCallback, removeFileCallback }">
                                    <div class="">
                                        <div v-if="files.length > 0">
                                            <div class="">
                                                <div :key="filesMy.name + filesMy.type + filesMy.size"
                                                     class="p-8 rounded-border flex flex-col border border-surface items-center gap-4">
                                                    <div>
                                                        <img role="presentation" :alt="filesMy.name"
                                                             src="/img/filebox.png"
                                                             class="w-20"/>
                                                    </div>
                                                    <span
                                                        class="font-semibold text-ellipsis max-w-60 whitespace-nowrap overflow-hidden">{{
                                                            filesMy.name
                                                        }}</span>
                                                    <div>{{ formatSize(filesMy.size) }}</div>
                                                    <Badge value="Pending" severity="warn"/>
                                                    <Button icon="pi pi-times"
                                                            @click="onRemoveTemplatingFile(filesMy, removeFileCallback, index)"
                                                            outlined rounded severity="danger"/>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Completed files (samo jedan fajl) -->
                                        <div v-if="uploadedFiles.length > 0">
                                            <h5>Completed</h5>
                                            <div class="flex flex-wrap gap-4">
                                                <div
                                                    :key="uploadedFiles[0].name + uploadedFiles[0].type + uploadedFiles[0].size"
                                                    class="p-8 rounded-border flex flex-col border border-surface items-center gap-4">
                                                    <div>
                                                        <img role="presentation" :alt="uploadedFiles[0].name"
                                                             :src="uploadedFiles[0].objectURL || '/img/filebox.png'"
                                                             width="100" height="50"/>
                                                    </div>
                                                    <span
                                                        class="font-semibold text-ellipsis max-w-60 whitespace-nowrap overflow-hidden">{{
                                                            uploadedFiles[0].name
                                                        }}</span>
                                                    <div>{{ formatSize(uploadedFiles[0].size) }}</div>
                                                    <Badge value="Completed" class="mt-4" severity="success"/>
                                                    <Button icon="pi pi-times" @click="removeUploadedFileCallback(0)"
                                                            outlined rounded severity="danger"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <template #empty>
                                    <div class="flex items-center justify-center flex-col">
                                        <i class="pi pi-cloud-upload !border-2 !rounded-full !p-8 !text-4xl !text-muted-color"/>
                                        <p class="my-7">Drag and drop fajlove.</p>
                                    </div>
                                </template>
                            </FileUpload>

                            <p v-if="emptyTableData" class="text-red-500 mt-4 text-center">
                                Error has occurred. Refreshing the page...
                            </p>
                        </div>
                    </div>
                </StepPanel>
                <StepPanel v-slot="{ activateCallback }" value="2">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 pt-4 ps-4">Pregled Idiota.
                            Imaš
                            mogućnost
                            za izmenu teksta, ako želiš.</h1>
                        <div class="flex flex-col gap-1 mt-4">
                        <textarea
                            v-model="data"
                            rows="30"
                            class="p-4 border rounded-lg bg-gray-100 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">{{data}}</textarea>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <Button @click="activeTab = '1'" label="Nazad" icon="pi pi-angle-left mx-auto"
                                class="mt-4"/>
                        <Button @click="activeTab = '3'" label="Dalje" icon="pi pi-angle-right mx-auto" class="mt-4"/>
                    </div>
                </StepPanel>
                <StepPanel v-slot="{ activateCallback }" value="3">
                    <div class="flex justify-between">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                            <tr>
                                <th colspan="2" class="px-4 py-2 bg-gray-200 text-center text-gray-600 font-semibold">
                                    ОПИС
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(value, key) in tableData" :key="key" class="border-t border-gray-200">
                                <td class="px-4 py-2 text-gray-800">{{ key }}</td>
                                <td class="px-4 py-2 text-gray-800">{{ value }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-between">
                        <Button @click="activeTab = '2'"
                                label="Previous"
                                icon="pi pi-angle-left mx-auto"
                                class="mt-4"/>
                        <Button @click="finishDocuments" label="Finish" icon="pi pi-check mx-auto"
                                class="mt-4"/>
                    </div>
                </StepPanel>
                <StepPanel v-slot="{ activateCallback }" value="4">
                    <div class="p-8">
                        <h1 class="text-xl mb-3 font-bold">Tvoji dokumenti su spremni! 🫡</h1>
                        <div class=" rounded-border grid grid-cols-2 items-center gap-4">
                            <a :href="idiotLink" target="_blank"
                               class="flex items-center justify-center rounded-lg text-lg py-10 border font-bold">Preuzmi
                                Idiota <PhDownload :size="32" />
                            </a>
                            <a :href="tabelarView" target="_blank"
                               class="flex items-center justify-center rounded-lg py-10 border font-bold">Preuzmi tablarni
                                prikaz <PhDownload :size="32" />

                            </a>
                        </div>
                        <Button label="Generiši opet" icon="pi pi-angle-left" class="mt-4"
                                @click="restartForm"/>
                    </div>
                </StepPanel>
            </StepPanels>
            <div v-if="loader">
                <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
                    <ProgressSpinner class="w-12 h-12" strokeWidth="8" fill="transparent" animationDuration=".5s"
                                     aria-label="Custom ProgressSpinner"/>
                </div>
            </div>
            <!--        </main>-->
        </Stepper>
    </AuthenticatedLayout>
</template>
