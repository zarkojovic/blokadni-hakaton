<script setup>
import {ref} from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const form = useForm({
    elaboratFile: null,
});

const fileInput = ref(null);

const allowedTypes = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
];

const validate = () => {
    var isValid = true;
    if (!form.elaboratFile) {
        form.errors.elaboratFile = 'The file field is required.';
        isValid = false;
    }
    if (form.elaboratFile && form.elaboratFile.size > 10 * 1024 * 1024) {
        form.errors.elaboratFile = 'The file may not be greater than 2MB.';
        isValid = false;
    }
    if (form.elaboratFile && !allowedTypes.includes(form.elaboratFile.type)) {
        form.errors.elaboratFile = 'The file must be a file of type: pdf, doc, docx.';
        isValid = false;
    }
    return isValid;
};

const responseText = ref('');

const handleFileChange = () => {
    form.elaboratFile = fileInput.value.files[0];
};

const idiotLink = ref('');
const tabelarView = ref('');

const submit = () => {
    if (!validate()) {
        return;
    }
    form.post(route('gemini.documents'), {
        preserveScroll: true,
        onSuccess: (page) => {
            const {fileIdiot, fileTabular} = page.props;

            idiotLink.value = fileIdiot;
            tabelarView.value = fileTabular;
            form.reset();
            fileInput.value = '';
        },
        onError: (errors) => {
            form.errors = errors;
            console.log(errors);
        },
    });
};
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-4">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">Generisi dokumente potrebne za
                            Krovnu radnu grupu</h1>

                        <form @submit.prevent="submit" class="mt-6 space-y-6" enctype="multipart/form-data">
                            <div>
                                <InputLabel for="file" value="Dokument"/>

                                <input type="file" name="elaboratFile" id="elaboratFile" ref="fileInput"
                                       @change="handleFileChange"/>

                                <InputError class="mt-2" :message="form.errors.elaboratFile"/>
                            </div>

                            <PrimaryButton class="mt-6" @click="submit" :disabled="form.processing">
                                Generisi dokumente
                            </PrimaryButton>
                        </form>


                        <a v-if="idiotLink" :href="idiotLink" target="_blank"
                           class="mt-6 block text-blue-500 underline">Idiot
                            link</a>
                        <a v-if="tabelarView" :href="tabelarView" target="_blank"
                           class="mt-6 block text-blue-500 underline">Tabelar
                            view</a>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
