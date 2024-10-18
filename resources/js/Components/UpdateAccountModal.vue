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
    account: {
        required: true
    }
});

const form = useForm({
    id: '',
    account_name: '',
    account_credentials: ''
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

watch(() => props.account, (newVal) => {
    form.account_name = newVal.account_name;
    form.account_credentials = newVal.account_credentials;
});

</script>

<template>

    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <form @submit.prevent="submitUpdates(account.id)">
            
            <div class="px-6 py-4 space-y-4">

                <h3 class="text-lg font-medium text-gray-900">
                    Update Account
                </h3>

                <div>
                    <InputLabel for="account_name">Account Name :</InputLabel>
                    <TextInput class="w-full" id="account_name" v-model="form.account_name" />
                    <FormValidationError v-if="form.errors.account_name">{{ form.errors.account_name }}</FormValidationError>
                </div>

                <div>
                    <InputLabel for="account_credentials">Account Credentials :</InputLabel>
                    <TextareaInput class="w-full" id="account_credentials" v-model="form.account_credentials" />
                    <FormValidationError v-if="form.errors.account_credentials">{{ form.errors.account_credentials }}</FormValidationError>
                </div>

            </div>

            <div class="flex flex-row justify-end gap-2 px-6 py-4 bg-gray-100 text-right">
                <PrimaryButton @click="close()" type="button">cancel</PrimaryButton>
                <PrimaryButton type="submit" :disabled="form.processing">Update</PrimaryButton>
            </div>
        </form>

    </Modal>

</template>
