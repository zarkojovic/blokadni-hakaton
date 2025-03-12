<script setup>
import Button from 'primevue/button';
import StepPanel from 'primevue/steppanel';
import {ref, defineEmits} from 'vue';
import FileUpload from 'primevue/fileupload';
import {useForm} from '@inertiajs/vue3';
import axios from 'axios';

const totalSize = ref(0);
const totalSizePercent = ref(0);
const filesMy = ref(null);
const activeTab = ref('1');
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

const uploadEvent = (callback) => {
    // Uveriti se da je samo jedan fajl izabran pre nego što se otprema
    if (filesMy.value != null) {
        totalSizePercent.value = totalSize.value / 10;

        // Pripremi FormData objekat
        const formData = new FormData();
        formData.append('elaboratFile', filesMy.value); // Dodajte fajl
        formData.append('type', 'content'); // Dodajte fajl

        // Pošaljite zahtev koristeći axios
        axios.post(route('gemini.documents'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data', // Postavljanje headera na multipart/form-data
            },
        }).then(response => {
            // activeTab.value = '2';
            emit('changeTab', '2');
            console.log(response.data); // Odgovor u JSON formatu, ako server odgovara sa JSON-om
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

const emit = defineEmits(['changeTab']);
const form = useForm({
    elaboratFile: null,
});
</script>

<template>
    <StepPanel v-slot="{ activateCallback }" value="1">
        <div class="flex flex-col h-48">
            <div class="card">
                <FileUpload :multiple="false" @upload="onTemplatedUpload" accept=".doc,.docx,.pdf"
                            :maxFileSize="10000000" @select="onSelectedFiles">
                    <template #header="{ chooseCallback, uploadCallback, clearCallback, files }">
                        <div class="flex flex-wrap justify-between items-center flex-1 gap-4">
                            <div class="flex gap-2">
                                <Button @click="chooseCallback()" icon="pi pi-images" rounded outlined
                                        severity="secondary"></Button>
                                <Button @click="uploadEvent(uploadCallback)" icon="pi pi-cloud-upload" rounded outlined
                                        severity="success" :disabled="!files.length"></Button>
                                <Button @click="clearCallback()" icon="pi pi-times" rounded outlined severity="danger"
                                        :disabled="!files || files.length === 0"></Button>
                            </div>
                        </div>
                    </template>
                    <template #content="{ files, uploadedFiles, removeUploadedFileCallback, removeFileCallback }">
                        <div class="flex flex-col gap-8 pt-4">
                            <div v-if="files.length > 0">
                                <h5>Pending</h5>
                                <div class="flex flex-wrap gap-4">
                                    <div :key="filesMy.name + filesMy.type + filesMy.size"
                                         class="p-8 rounded-border flex flex-col border border-surface items-center gap-4">
                                        <div>
                                            <img role="presentation" :alt="filesMy.name" src="/img/filebox-a.png"
                                                 width="100" height="50"/>
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
                                    <div :key="uploadedFiles[0].name + uploadedFiles[0].type + uploadedFiles[0].size"
                                         class="p-8 rounded-border flex flex-col border border-surface items-center gap-4">
                                        <div>
                                            <img role="presentation" :alt="uploadedFiles[0].name"
                                                 :src="uploadedFiles[0].objectURL || '/img/filebox.png'" width="100"
                                                 height="50"/>
                                        </div>
                                        <span
                                            class="font-semibold text-ellipsis max-w-60 whitespace-nowrap overflow-hidden">{{
                                                uploadedFiles[0].name
                                            }}</span>
                                        <div>{{ formatSize(uploadedFiles[0].size) }}</div>
                                        <Badge value="Completed" class="mt-4" severity="success"/>
                                        <Button icon="pi pi-times" @click="removeUploadedFileCallback(0)" outlined
                                                rounded severity="danger"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #empty>
                        <div class="flex items-center justify-center flex-col">
                            <i class="pi pi-cloud-upload !border-2 !rounded-full !p-8 !text-4xl !text-muted-color"/>
                            <p class="mt-6 mb-0">Drag and drop fajlove.</p>
                        </div>
                    </template>
                </FileUpload>
            </div>
        </div>
    </StepPanel>
    <StepPanel v-slot="{ activateCallback }" value="2">
        <h1>Preview content</h1>
    </StepPanel>
</template>
