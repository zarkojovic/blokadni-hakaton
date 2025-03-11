<script setup>
import {ref} from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const form = useForm({
    file: null,
});

const fileInput = ref(null);

const allowedTypes = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
];

const validate = () => {
    var isValid = true;
    if (!form.file) {
        form.errors.file = 'The file field is required.';
        isValid = false;
    }
    if (form.file && form.file.size > 2 * 1024 * 1024) {
        form.errors.file = 'The file may not be greater than 2MB.';
        isValid = false;
    }
    if (form.file && !allowedTypes.includes(form.file.type)) {
        form.errors.file = 'The file must be a file of type: pdf, doc, docx.';
        isValid = false;
    }
    return isValid;
};

const handleFileChange = () => {
    form.file = fileInput.value.files[0];
};

const submit = () => {
    if (!validate()) {
        return;
    }

    form.post(route('gemini.documents'), {
        preserveScroll: true,
        onSuccess: (page) => {
            console.log(page.props.response);
            form.reset();
            fileInput.value.value = '';
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

                        <form @submit.prevent="submit" class="mt-6 space-y-6">
                            <div>
                                <InputLabel for="file" value="Dokument"/>

                                <input type="file" name="file" id="file" ref="fileInput" @change="handleFileChange"/>

                                <InputError class="mt-2" :message="form.errors.file"/>
                            </div>

                            <PrimaryButton class="mt-6" @click="submit" :disabled="form.processing">
                                Generisi dokumente
                            </PrimaryButton>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
