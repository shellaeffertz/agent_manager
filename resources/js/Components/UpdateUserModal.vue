<script setup>

import { watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
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
    user: {
        default: true,
    }
});

const form = useForm({
    id: 0,
    name: '',
    email: '',
    role: ''
});

const close = () => {
    emit('close');
};

const submitUpdates = () => {
    form.put(`/user/update`, {
        onSuccess: () => {
            close();
        }
    });
};

watch(() => props.user, (newVal) => {
    form.id = newVal.id;
    form.name = newVal.name;
    form.email = newVal.email;
    form.role = newVal.role;
});

</script>

<template>

    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <form @submit.prevent="submitUpdates()">

            <div class="px-6 py-4 space-y-4">

                <h3 class="text-lg font-medium text-gray-900">
                    Update User
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

            </div>

            <div class="flex flex-row justify-end gap-2 px-6 py-4 bg-gray-100 text-right">
                <PrimaryButton @click="close()" type="button">cancel</PrimaryButton>
                <PrimaryButton type="submit" :disabled="form.processing">Update</PrimaryButton>
            </div>

        </form>

    </Modal>

</template>
