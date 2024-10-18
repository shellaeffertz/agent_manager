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
    drop: {
        required: true
    }
});

const form = useForm({
    drop_id: 0,
    company_name: '',
    company_number: '',
    company_address: '',
    company_activity: '',
    company_credentials: '',
});

const close = () => {
    form.reset();
    emit('close');
};

const submitInfo = () => {
    form.post(`/task/store`, {
        onSuccess: () => {
            close();
        }
    });
};

watch(() => props.drop, (newVal) => {
    form.drop_id = newVal.id;
});

</script>

<template>

    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <form @submit.prevent="submitInfo">

            <div class="px-6 py-4 space-y-4">

                <h3 class="text-lg font-medium text-gray-900">
                    Add Company
                </h3>

                <div>
                    <InputLabel for="company_name">Company Name :</InputLabel>
                    <TextInput class="w-full" id="company_name" v-model="form.company_name" />
                    <FormValidationError v-if="form.errors.company_name">{{ form.errors.company_name }}</FormValidationError>
                </div>

                <div>
                    <InputLabel for="company_address">Company Address :</InputLabel>
                    <TextInput class="w-full" id="company_address" v-model="form.company_address" />
                    <FormValidationError v-if="form.errors.company_address">{{ form.errors.company_address }}</FormValidationError>
                </div>

                <div>
                    <InputLabel for="company_number">Company Number :</InputLabel>
                    <TextInput class="w-full" id="company_number" v-model="form.company_number" />
                    <FormValidationError v-if="form.errors.company_number">{{ form.errors.company_number }}</FormValidationError>
                </div>

                <div>
                    <InputLabel for="company_activity">Company Activity :</InputLabel>
                    <TextInput class="w-full" id="company_activity" v-model="form.company_activity" />
                    <FormValidationError v-if="form.errors.company_activity">{{ form.errors.company_activity }}</FormValidationError>
                </div>

                <div>
                    <InputLabel for="company_credentials">Company Credentials :</InputLabel>
                    <TextareaInput class="w-full" id="company_credentials" v-model="form.company_credentials" />
                    <FormValidationError v-if="form.errors.company_credentials">{{ form.errors.company_credentials }}</FormValidationError>
                </div>

            </div>

            <div class="flex flex-row justify-end gap-2 px-6 py-4 bg-gray-100 text-right">
                <PrimaryButton @click="close()" type="button">cancel</PrimaryButton>
                <PrimaryButton type="submit" :disabled="form.processing">Add</PrimaryButton>
            </div>
        </form>

    </Modal>

</template>
