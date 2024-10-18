<script setup>

import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AddUserModal from './AddUserModal.vue';
import Paginator from '@/components/Paginator.vue';
import UpdateUserModal from './UpdateUserModal.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import ConfirmationModal from '@/components/ConfirmationModal.vue';

defineProps({
    users: {
        type: Object,
        required: true
    }
});

const form = useForm({
    id: 0,
});

let user = ref(null);

let showAddUserModal = ref(false);

let showUpdateUserModal = ref(false);

let showDeleteUserModal = ref(false);

const showUpdateModal = (editUser) => {
    user.value = editUser;
    showUpdateUserModal.value = true;
}

const showDeleteModal = (deleteUser) => {
    user.value = deleteUser;
    showDeleteUserModal.value = true;
}

const deleteUser = (deleteUser) => {
    form.id = deleteUser.id;
    form.delete('/user/destroy');
    showDeleteUserModal.value = false;
}

</script>

<template>

    <div class="p-4 space-y-4">

        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-black text-blue-900">Users</h1>
            <button
                @click="showAddUserModal = true" 
                class="px-3 py-2 border border-blue-900 text-blue-900 rounded-md text-sm hover:bg-blue-900 hover:text-white" 
                type="button"
            >
                Add User
            </button>
        </div>

        <table class="w-full border-2 border-gray-300">
            <thead>
                <tr class="bg-blue-900 text-white font-black">
                    <td class="p-2 text-center capitalize">Name</td>
                    <td class="p-2 text-center capitalize">Email</td>
                    <td class="p-2 text-center capitalize">Role</td>
                    <td></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <tr class="even:bg-gray-100 text-xs" v-for="user in users.data" :key="user.id">
                    <td class="p-2 text-center capitalize">{{ user.name }}</td>
                    <td class="p-2 text-center max-w-fit">{{ user.email }}</td>
                    <td class="p-2 text-center text-blue-900 font-black capitalize">{{ user.role.split('_').join(' ') }}</td>
                    <td  class="p-2 text-center">
                        <button @click="showUpdateModal(user)" class="px-2 py-1 border border-blue-900 text-blue-900 rounded-md text-sm hover:bg-blue-900 hover:text-white" type="button">
                            Edit
                        </button>
                    </td>
                    <td  class="p-2 text-center">
                        <button @click="showDeleteModal(user)" class="px-2 py-1 border border-blue-900 text-blue-900 rounded-md text-sm hover:bg-blue-900 hover:text-white" type="button">
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <Paginator :links="users.links" />

    </div>

    <AddUserModal :show="showAddUserModal" @close="showAddUserModal = false" />

    <UpdateUserModal :show="showUpdateUserModal" :user="user" @close="showUpdateUserModal = false" />

    <ConfirmationModal :show="showDeleteUserModal" @close="showDeleteUserModal = false">

        <template #title>
            Delete User Confirmation
        </template>

        <template #content>
            <p class="text-base font-bold">
                Are you sure you want to delete this User ?
            </p>
            <ul class="pl-5 mt-2 list-disc text-red-500">
                <li>
                    Once deleted you won't be able to recover it !
                </li>
            </ul>
        </template>

        <template #footer>
            <div class="flex flex-row justify-end gap-2 bg-gray-100 text-right">
                <PrimaryButton @click="showDeleteUserModal = false" type="button">cancel</PrimaryButton>
                <PrimaryButton @click="deleteUser(user)" type="submit">delete</PrimaryButton>
            </div>
        </template>

    </ConfirmationModal>

</template>
