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
    }
});

const form = useForm({
    name: '',
    email: '',
    role: '',
    password: '',
    password_confirmation: '',
});

const close = () => {
    form.reset();
    emit('close');
};

const submitInfo = () => {
    form.post(`/user/store`, {
        onSuccess: () => {
            close();
        }
    });
};


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
                    Add User
                </h3>

                <div>
                    <InputLabel for="name">Name :</InputLabel>
                    <TextInput class="w-full" id="name" v-model="form.name" />
                    <FormValidationError v-if="form.errors.name">{{ form.errors.name }}</FormValidationError>
                </div>

                <div>
                    <InputLabel for="email">Email :</InputLabel>
                    <TextInput class="w-full" id="email" v-model="form.email" />
                    <FormValidationError v-if="form.errors.email">{{ form.errors.email }}</FormValidationError>
                </div>

                <div>
                    <InputLabel for="role">Role :</InputLabel>
                     <select v-model="form.role" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" id="role">
                        <option disabled>Choose The Role</option>
                        <option value="admin">Admin</option>
                        <option value="drop_manager">Drop Manager</option>
                        <option value="company_creator">Company Creator</option>
                        <option value="website_creator">Website Creator</option>
                        <option value="support">Support</option>
                     </select>
                    <FormValidationError v-if="form.errors.role">{{ form.errors.role }}</FormValidationError>
                </div>

                <div>
                    <InputLabel for="password">Password :</InputLabel>
                    <TextInput class="w-full" id="password" type="password" v-model="form.password" />
                    <FormValidationError v-if="form.errors.password">{{ form.errors.password }}</FormValidationError>
                </div>

                <div>
                    <InputLabel for="password_confirmation">Password Confirmation :</InputLabel>
                    <TextInput class="w-full" id="password_confirmation" type="password" v-model="form.password_confirmation" />
                    <FormValidationError v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</FormValidationError>
                </div>

            </div>

            <div class="flex flex-row justify-end gap-2 px-6 py-4 bg-gray-100 text-right">
                <PrimaryButton @click="close()" type="button">cancel</PrimaryButton>
                <PrimaryButton type="submit" :disabled="form.processing">Add</PrimaryButton>
            </div>
        </form>

    </Modal>

</template>
