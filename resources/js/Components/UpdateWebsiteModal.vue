<script setup>

import { watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import FormValidationError from '@/Components/FormValidationError.vue';


const emit = defineEmits(['close']);

let props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    website: {
        default: true,
    }
});

const form = useForm({
    id: '',
    website_link: '',
    website_credentials: ''
});

const close = () => {
    emit('close');
};

const submitUpdates = (id) => {
    form.id = id;
    form.put(`/task/update`, {
        onSuccess: () => {
            close();
        }
    });
};

watch(() => props.website, (newVal) => {
    form.website_link = newVal.website_link;
    form.website_credentials = newVal.website_credentials;
});

</script>

<template>

    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <form @submit.prevent="submitUpdates(website.id)">

            <div class="px-6 py-4 space-y-4">

                <h3 class="text-lg font-medium text-gray-900">
                    Update Website
                </h3>

                <div>
                    <InputLabel for="website_link">Website Link :</InputLabel>
                    <TextInput class="w-full" id="website_link" v-model="form.website_link" />
                    <FormValidationError v-if="form.errors.website_link">{{ form.errors.website_link }}</FormValidationError>
                </div>

                <div>
                    <InputLabel for="website_credentials">Website Credentials :</InputLabel>
                    <TextareaInput class="w-full" id="website_credentials" v-model="form.website_credentials" />
                    <FormValidationError v-if="form.errors.website_credentials">{{ form.errors.website_credentials }}</FormValidationError>
                </div>

            </div>

            <div class="flex flex-row justify-end gap-2 px-6 py-4 bg-gray-100 text-right">
                <PrimaryButton @click="close()" type="button">cancel</PrimaryButton>
                <PrimaryButton type="submit" :disabled="form.processing">Update</PrimaryButton>
            </div>
        </form>

    </Modal>

</template>
