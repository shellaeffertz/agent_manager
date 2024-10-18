<script setup>

import { ref } from 'vue';
import Paginator from './Paginator.vue';
import { useForm } from '@inertiajs/vue3';
import UpdateDropModal from './UpdateDropModal.vue';
import AddDropModal from '@/components/AddDropModal.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import ConfirmationModal from '@/components/ConfirmationModal.vue';

defineProps({
    todoTasks: {
        type: Object,
        required: true
    },
    tasks: {
        type: Object,
        required: true
    }
});

let showAddDropModal = ref(false);

let showUpdateDropModal = ref(false);

let showDeleteConfirmation = ref(false);

let showSendConfirmation = ref(false);

let showVerifyLinkModal = ref(false);

let showCopiedMessage = ref(false);

let modalTask = ref(null);

const form = useForm({
    id: ''
});

const verifyLinkForm = useForm({
    link: null
});

const showUpdateModal = (newTask) => {
    modalTask.value = newTask;
    showUpdateDropModal.value = true;
};

const showSendModal = (newTask) => {
    modalTask.value = newTask;
    showSendConfirmation.value = true;
};

const showDeleteModal = (newTask) => {
    modalTask.value = newTask;
    showDeleteConfirmation.value = true;
};

const showVerifyLink = (id, status) => {
    modalTask.value = {
        id, 
        status
    };
    showVerifyLinkModal.value = true;
}

const deleteDrop = (newTask) => {
    form.id = newTask.id;
    form.delete(`/task/destroy`);
    showDeleteConfirmation.value = false;
}

const sendTask = (newTask) => {
    form.id = newTask.id;
    form.put(`/task/send`);
    showSendConfirmation.value = false;
}

const verifyLink = (link) => {
    verifyLinkForm.link = link;
    verifyLinkForm.put(`/link/verify`);
    showVerifyLinkModal.value = false;
}

const copyLink = (linkText) => {
    navigator.clipboard.writeText(linkText)
    .then(() => {
        showCopiedMessage.value = true;
        setTimeout(() => {
            showCopiedMessage.value = false;
        }, 3000)
    })
    .catch(err => {
        console.error("Failed to copy text: ", err);
    });
}

</script>

<template>
        
    <div class="space-y-4">
        <h1 class="text-3xl font-black text-blue-900">Verification Links</h1>

        <table class="w-full border-2 border-gray-300">

            <thead>
                <tr class="bg-blue-900 text-white font-black">
                    <td class="p-2 text-center capitalize">Drop</td>
                    <td class="p-2 text-center capitalize">Documents</td>
                    <td class="p-2 text-center capitalize">Link</td>
                    <td class="p-2 text-center capitalize">Status</td>
                    <td></td>
                    <td></td>
                </tr>
            </thead>

            <tbody>
                <template v-for="task in todoTasks.data" :key="tasks.id">
                    <tr class="even:bg-gray-100 text-xs">
                        <td class="p-2 text-center capitalize">{{ task.drop.fullname }}</td>
                        <td class="p-2 text-center capitalize space-x-1">
                            <a v-for="(document, index) in task.drop.documents" class="hover:underline text-blue-500" target="_blank" :href="`https://agent-manager.com/storage/${document}`">Doc {{ index + 1 }}</a>
                        </td>
                        <td class="p-2 text-center capitalize max-w-fit">
                            <span v-if="task.verification_links.length == 0">No Links Added</span>
                            <p class="w-fit mx-auto min-w-20" v-else>
                                <span class="flex items-center gap-2" v-for="link, index in task.verification_links" :key="new Date().getTime() * Math.random()">
                                    <button @click="copyLink(link.link)" class="text-gray-500 hover:text-gray-600" type="button">
                                        <i class="fa-solid fa-copy"></i>
                                    </button>
                                    <span>Link {{ index + 1 }}</span>
                                    <i v-if="link.status == 'verified'" class="fa-solid fa-check text-green-500"></i>
                                    <i v-if="link.status == 'expired'" class="fa-solid fa-xmark text-red-500"></i>
                                </span>
                            </p>
                        </td>
                        <td class="p-2 text-center capitalize">
                            <span v-if="task.status == 'Done'" class="text-green-500 font-bold">Done</span>
                            <span v-if="task.status == 'Rejected'" class="text-red-500 font-bold">Rejected</span>
                            <span v-else-if="task.status == 'On Hold'" class="text-blue-900 font-bold">On Hold</span>
                        </td>
                        <td  class="p-2 text-center">
                            <button
                                @click="showVerifyLink(task.id, 'expired')"
                                class="px-3 py-2 border border-blue-900 text-blue-900 rounded-md hover:bg-blue-900 hover:text-white" 
                                type="button"
                            >
                                Expired
                            </button>
                        </td>
                        <td  class="p-2 text-center">
                            <button
                                @click="showVerifyLink(task.id, 'verified')"
                                class="px-3 py-2 border border-blue-900 text-blue-900 rounded-md hover:bg-blue-900 hover:text-white" 
                                type="button"
                            >
                                Verified
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>

        </table>

        <Paginator :links="todoTasks.links" />

    </div>

    <div class="space-y-4">

        <div class="flex items-center justify-between">

            <h1 class="text-3xl font-black text-blue-900">Drops</h1>

            <button
                @click="showAddDropModal = true" 
                class="px-3 py-2 border border-blue-900 text-blue-900 rounded-md text-sm hover:bg-blue-900 hover:text-white" 
                type="button"
            >
                Add Drop
            </button>

        </div>

        <table class="w-full border-2 border-gray-300">

            <thead>
                <tr class="bg-blue-900 text-white font-black">
                    <td class="p-2 text-center capitalize">Name</td>
                    <td class="p-2 text-center capitalize">Address</td>
                    <td class="p-2 text-center capitalize">Country</td>
                    <td class="p-2 text-center capitalize">Contact</td>
                    <td class="p-2 text-center capitalize">Birthday</td>
                    <td class="p-2 text-center capitalize">Documents</td>
                    <td class="p-2 text-center capitalize">Status</td>
                    <td  class="p-2 text-center capitalize">Sent</td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                </tr>
            </thead>

            <tbody>
                <tr class="even:bg-gray-100 text-xs" v-for="task in tasks.data" :key="task.id">
                    <td class="p-2 text-center capitalize">{{ task.fullname }}</td>
                    <td class="p-2 text-center capitalize max-w-fit">{{ task.address }}</td>
                    <td class="p-2 text-center capitalize">{{ task.country }}</td>
                    <td  class="p-2 text-center capitalize">{{ task.contact }}</td>
                    <td class="p-2 text-center capitalize">{{ task.birthday }}</td>
                    <td class="p-2 text-center capitalize space-x-1">
                        <a v-for="(document, index) in task.documents" class="hover:underline text-blue-500" target="_blank" :href="`http://localhost:8000/storage/${document}`">Doc {{ index + 1 }}</a>
                    </td>
                    <td class="p-2 text-center capitalize">
                        <span v-if="task.status == 'On Hold'" class="text-blue-900 font-black">{{ task.status }}</span>
                        <span v-if="task.status == 'Done'" class="text-green-500 font-black">{{ task.status }}</span>
                        <span v-if="task.status == 'Rejected'" class="text-red-500 font-black">{{ task.status }}</span>
                    </td>
                    <td  class="p-2 text-center capitalize">
                        <i v-if="task.is_sent" class="fa-solid fa-check text-green-500"></i>
                        <i v-else class="fa-solid fa-xmark text-red-500"></i>
                    </td>
                    <td  class="p-2 text-center">
                        <button @click="showSendModal(task)" :disabled="task.is_sent" class="px-2 py-1 border border-blue-900 text-blue-900 rounded-md text-sm hover:bg-blue-900 hover:text-white disabled:border-gray-300 disabled:bg-gray-300 disabled:text-white disabled:cursor-not-allowed" type="button">
                            Send
                        </button>
                    </td>
                    <td  class="p-2 text-center">
                        <button @click="showUpdateModal(task)" :disabled="task.is_sent" class="px-2 py-1 border border-blue-900 text-blue-900 rounded-md text-sm hover:bg-blue-900 hover:text-white disabled:border-gray-300 disabled:bg-gray-300 disabled:text-white disabled:cursor-not-allowed" type="button">
                            Edit
                        </button>
                    </td>
                    <td  class="p-2 text-center">
                        <button @click="showDeleteModal(task)" :disabled="task.is_sent" class="px-2 py-1 border border-blue-900 text-blue-900 rounded-md text-sm hover:bg-blue-900 hover:text-white disabled:border-gray-300 disabled:bg-gray-300 disabled:text-white disabled:cursor-not-allowed" type="button">
                            Delete
                        </button>
                    </td>

                </tr>
            </tbody>

        </table>

        <Paginator :links="tasks.links" />

    </div>

    <p v-if="showCopiedMessage" class="fixed top-7 left-1/2 -translate-x-1/2 z-[99999] bg-black p-4 rounded-xl">
        <span class="text-white">Link copied successfully</span>
    </p>

    <AddDropModal :show="showAddDropModal" @close="showAddDropModal = false" />

    <UpdateDropModal :drop="modalTask" :show="showUpdateDropModal" @close="showUpdateDropModal = false" />

    <ConfirmationModal :show="showDeleteConfirmation" @close="showDeleteConfirmation = false">

        <template #title>
            Delete Drop Confirmation
        </template>

        <template #content>
            <p class="text-base font-bold">
                Are you sure you want to delete this Drop ?
            </p>
            <ul class="pl-5 mt-2 list-disc text-red-500">
                <li>
                    Once deleted you won't be able to recover it !
                </li>
            </ul>
        </template>

        <template #footer>
            <div class="flex flex-row justify-end gap-2 bg-gray-100 text-right">
                <PrimaryButton @click="showDeleteConfirmation = false" type="button">cancel</PrimaryButton>
                <PrimaryButton @click="deleteDrop(modalTask)" type="submit">delete</PrimaryButton>
            </div>
        </template>

    </ConfirmationModal>

    <ConfirmationModal :show="showSendConfirmation" @close="showSendConfirmation = false">

        <template #title>
            Send Task Confirmation
        </template>

        <template #content>
            <p class="text-base font-bold">
                Are you sure you want to Send this Task ?
            </p>
        </template>

        <template #footer>
            <div class="flex flex-row justify-end gap-2 bg-gray-100 text-right">
                <PrimaryButton @click="showSendConfirmation = false" type="button">cancel</PrimaryButton>
                <PrimaryButton @click="sendTask(modalTask)" type="submit">send</PrimaryButton>
            </div>
        </template>

    </ConfirmationModal>

    <ConfirmationModal :show="showVerifyLinkModal" @close="showVerifyLinkModal = false">

        <template #title>
            Verify Link Confirmation
        </template>

        <template #content>
            <p class="text-base font-bold">
                Are you sure you want to this Link a status?
            </p>
        </template>

        <template #footer>
            <div class="flex flex-row justify-end gap-2 bg-gray-100 text-right">
                <PrimaryButton @click="showVerifyLinkModal = false" type="button">cancel</PrimaryButton>
                <PrimaryButton @click="verifyLink(modalTask)" type="submit">Done</PrimaryButton>
            </div>
        </template>

    </ConfirmationModal>

</template>