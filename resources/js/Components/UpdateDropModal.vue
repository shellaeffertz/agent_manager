<script setup>

import { watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import TextInput from '@/Components/TextInput.vue';
import FileInput from '@/Components/FileInput.vue';
import DateInput from '@/Components/DateInput.vue';
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
    id: 0,
    fullname: '',
    address: '',
    country: '',
    contact: '',
    birthday: '',
    documents: [],
    _method: 'PUT'
});

const close = () => {
    emit('close');
};

const submitUpdates = () => {
    form.post(`/task/update`, {
        onSuccess: () => {
            close();
        }
    });
};

watch(() => props.drop, (newVal) => {
    form.id = newVal.id;
    form.fullname = newVal.fullname;
    form.address = newVal.address;
    form.country = newVal.country;
    form.contact = newVal.contact;
    form.birthday = newVal.birthday;
});

</script>

<template>

    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <form @submit.prevent="submitUpdates(drop.id)">

            <div class="px-6 py-4 space-y-4">

                <h3 class="text-lg font-medium text-gray-900">
                    Update Drop
                </h3>

                <div>
                    <InputLabel for="fullname">Full Name :</InputLabel>
                    <TextInput class="w-full" id="fullname" v-model="form.fullname" />
                    <FormValidationError v-if="form.errors.fullname">{{ form.errors.fullname }}</FormValidationError>
                </div>

                <div>
                    <InputLabel for="address">Address :</InputLabel>
                    <TextareaInput class="w-full" id="address" v-model="form.address" />
                    <FormValidationError v-if="form.errors.address">{{ form.errors.address }}</FormValidationError>
                </div>

                <div>
                    <InputLabel for="country">Country :</InputLabel>
                    <TextInput class="w-full" id="country" v-model="form.country" />
                    <FormValidationError v-if="form.errors.country">{{ form.errors.country }}</FormValidationError>
                </div>

                <div>
                    <InputLabel for="contact">Contact :</InputLabel>
                    <TextInput class="w-full" id="contact" v-model="form.contact" />
                    <FormValidationError v-if="form.errors.contact">{{ form.errors.contact }}</FormValidationError>
                </div>

                <div>
                    <InputLabel for="birthday">Birthday :</InputLabel>
                    <DateInput class="w-full" id="birthday" v-model="form.birthday" />
                    <FormValidationError v-if="form.errors.birthday">{{ form.errors.birthday }}</FormValidationError>
                </div>

                <div>
                    <InputLabel for="docs">Documents :</InputLabel>
                    <FileInput class="w-full" id="docs" v-model="form.documents" />
                    <FormValidationError v-if="form.errors.documents">{{ form.errors.documents }}</FormValidationError>
                </div>

            </div>

            <div class="flex flex-row justify-end gap-2 px-6 py-4 bg-gray-100 text-right">
                <PrimaryButton @click="close()" type="button">cancel</PrimaryButton>
                <PrimaryButton type="submit" :disabled="form.processing">Update</PrimaryButton>
            </div>
        </form>

    </Modal>

</template>
