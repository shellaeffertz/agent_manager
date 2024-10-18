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
    account: {
        required: true
    }
});

const form = useForm({
    id: 0,
    verification_link: ''
});

const close = () => {
    form.reset();
    emit('close');
};

const submitInfo = () => {
    form.post(`/link/store`, {
        onSuccess: () => {
            close();
        }
    });
};

watch(() => props.account, (newVal) => {
    form.id = newVal.id;
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
                    Add Verification Link
                </h3>

                <div>
                    <InputLabel for="verification_link">Verification Link :</InputLabel>
                    <TextInput class="w-full" id="verification_link" v-model="form.verification_link" />
                    <FormValidationError v-if="form.errors.verification_link">{{ form.errors.verification_link }}</FormValidationError>
                </div>

            </div>

            <div class="flex flex-row justify-end gap-2 px-6 py-4 bg-gray-100 text-right">
                <PrimaryButton @click="close()" type="button">cancel</PrimaryButton>
                <PrimaryButton type="submit" :disabled="form.processing">Add</PrimaryButton>
            </div>
        </form>

    </Modal>

</template>
