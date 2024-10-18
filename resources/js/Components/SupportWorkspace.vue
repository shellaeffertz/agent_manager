<script setup>

import { ref } from 'vue';
import Paginator from './Paginator.vue';
import { useForm } from '@inertiajs/vue3';
import AddAccountModal from './AddAccountModal.vue';
import UpdateAccountModal from './UpdateAccountModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import AddVerificationLinkModal from './AddVerificationLinkModal.vue';

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

let showAddAccountModal = ref(false);

let showUpdateAccountModal = ref(false);

let showDeleteConfirmation = ref(false);

let showSendConfirmation = ref(false);

let showRejectWebsiteConfirmation = ref(false);

let showFinishTaskConfirmation = ref(false);

let showVerificationLinkModal = ref(false);

let showFinishProjectModal = ref(false);

let showCopiedMessage = ref(false);

let modalTask = ref(null);

const form = useForm({
    id: ''
});

const finishProjectForm = useForm({
    id: '',
    status: ''
});

const showUpdateModal = (newtask) => {
    modalTask.value = newtask;
    showUpdateAccountModal.value = true;
};

const showAddVerificationLinkModal = (newtask) => {
    modalTask.value = newtask;
    showVerificationLinkModal.value = true;
};

const showSendModal = (newtask) => {
    modalTask.value = newtask;
    showSendConfirmation.value = true;
};

const showDeleteModal = (newtask) => {
    modalTask.value = newtask;
    showDeleteConfirmation.value = true;
};

const showAddAccount = (newtask) => {
    modalTask.value = newtask;
    showAddAccountModal.value = true;
};

const showRejectModal = (newtask) => {
    modalTask.value = newtask;
    showRejectWebsiteConfirmation.value = true;
};

const showFinishModal = (newtask) => {
    modalTask.value = newtask;
    showFinishTaskConfirmation.value = true;
};

const showFinishProject = (newtask) => {
    modalTask.value = newtask;
    showFinishProjectModal.value = true;
};

const deleteAccount = (newtask) => {
    form.id = newtask.id;
    form.delete(`/task/destroy`);
    showDeleteConfirmation.value = false;
};

const sendTask = (newtask) => {
    form.id = newtask.id;
    form.put(`/task/send`);
    showSendConfirmation.value = false;
};

const rejectWebsite = (newtask) => {
    form.id = newtask.id;
    form.put('/task/reject');
    showRejectWebsiteConfirmation = false;
};

const finishTask = (newtask) => {
    form.id = newtask.id;
    form.put('/task/finish');
    
    showFinishTaskConfirmation = false;
};

const finishProject = (newtask, status) => {
    finishProjectForm.id = newtask.id;
    finishProjectForm.status = status;
    finishProjectForm.put(`/finish`);
    showFinishProjectModal.value = false;
};

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

const downloadData = (newTask) => {

    const formData = new FormData();
    formData.append('id', newTask.id);

    axios.post('/download', formData, {
        responseType: 'blob'
      })
      .then(response => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const a = document.createElement('a');
        a.href = url;
        a.download = 'data.txt';
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);
      })
      .catch(error => {
        console.error('Error downloading the file:', error);
      });
}

</script>


<template>

        <div class="space-y-4">

            <h1 class="text-3xl font-black text-blue-900">Full Data</h1>

            <table class="w-full border-2 border-gray-300">

                <thead>
                    <tr class="bg-blue-900 text-white font-black">
                        <td class="p-2 text-center capitalize">Drop</td>
                        <td class="p-2 text-center capitalize">Documents</td>
                        <td class="p-2 text-center capitalize">Company</td>
                        <td class="p-2 text-center capitalize">Website</td>
                        <td class="p-2 text-center capitalize">Status</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </thead>

                <tbody>
                    <tr class="even:bg-gray-100 text-xs" v-for="task in todoTasks.data" :key="task.id">
                        <td class="p-2 text-center capitalize">{{ task.drop.fullname }}</td>
                        <td class="p-2 text-center capitalize space-x-1">
                            <a v-for="(document, index) in task.drop.documents" class="hover:underline text-blue-500" target="_blank" :href="`https://agent-manager.com/storage/${document}`">Doc {{ index + 1 }}</a>
                        </td>
                        <td class="p-2 text-center capitalize">{{ task.company.company_name }}</td>
                        <td class="p-2 text-center capitalize">{{ task.website_link }}</td>
                        <td  class="p-2 text-center capitalize">
                            <span v-if="task.status == 'On Hold'" class="text-blue-900 font-black">{{ task.status }}</span>
                            <span v-if="task.status == 'Done'" class="text-green-500 font-black">{{ task.status }}</span>
                            <span v-if="task.status == 'Rejected'" class="text-red-500 font-black">{{ task.status }}</span>
                        </td>
                        <td  class="p-2 text-center">
                            <button
                                @click="downloadData(task)"
                                class="px-3 py-2 border border-blue-900 text-blue-900 rounded-md hover:bg-blue-900 hover:text-white" 
                                type="button"
                            >
                                Download
                            </button>
                        </td>
                        <td  class="p-2 text-center">
                            <button
                                @click="showAddAccount(task)"
                                class="px-3 py-2 border border-blue-900 text-blue-900 rounded-md hover:bg-blue-900 hover:text-white" 
                                type="button"
                            >
                                Create Account
                            </button>
                        </td>
                        <td  class="p-2 text-center">
                            <button
                                @click="showRejectModal(task)"
                                class="px-3 py-2 border border-blue-900 text-blue-900 rounded-md hover:bg-blue-900 hover:text-white" 
                                type="button"
                            >
                                Reject
                            </button>
                        </td>
                        <td  class="p-2 text-center">
                            <button
                                @click="showFinishModal(task)"
                                class="px-3 py-2 border border-blue-900 text-blue-900 rounded-md hover:bg-blue-900 hover:text-white" 
                                type="button"
                            >
                                Done
                            </button>
                        </td>
                    </tr>
                </tbody>

            </table>

            <Paginator :links="todoTasks.links" />

        </div>

        <div class="space-y-4">

            <div class="py-4 flex justify-between">
                <h1 class="text-3xl font-black text-blue-900">Accounts</h1>
            </div>

            <table class="w-full border-2 border-gray-300">

                <thead>
                    <tr class="bg-blue-900 text-white font-black">
                        <td class="p-2 text-center capitalize">Drop</td>
                        <td class="p-2 text-center capitalize">Company</td>
                        <td class="p-2 text-center capitalize">Website</td>
                        <td class="p-2 text-center capitalize">Account Name</td>
                        <td class="p-2 text-center capitalize">Account Credentials</td>
                        <td class="p-2 text-center capitalize">Verification Links</td>
                        <td class="p-2 text-center capitalize">Status</td>
                        <td class="p-2 text-center capitalize">Sent</td>
                        <td class="p-2 text-center capitalize"></td>
                        <td class="p-2 text-center capitalize"></td>
                        <td class="p-2 text-center capitalize"></td>
                        <td class="p-2 text-center capitalize"></td>
                        <td class="p-2 text-center capitalize"></td>
                    </tr>
                </thead>

                <tbody>
                    <tr class="even:bg-gray-100 text-xs" v-for="task in tasks.data" :key="task.id">
                        <td class="p-2 text-center capitalize">{{ task.drop.fullname }}</td>
                        <td class="p-2 text-center capitalize">{{ task.company.company_name }}</td>
                        <td class="p-2 text-center capitalize">{{ task.website.website_link }}</td>
                        <td class="p-2 text-center capitalize max-w-fit">{{ task.account_name }}</td>
                        <td class="p-2 text-center capitalize max-w-fit">{{ task.account_credentials }}</td>
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
                        <td  class="p-2 text-center capitalize">
                            <span v-if="task.status == 'On Hold'" class="text-blue-900 font-black">{{ task.status }}</span>
                            <span v-if="task.status == 'Done'" class="text-green-500 font-black">{{ task.status }}</span>
                            <span v-if="task.status == 'Rejected'" class="text-red-500 font-black">{{ task.status }}</span>
                        </td>
                        <td class="p-2 text-center capitalize">
                            <i v-if="task.is_sent" class="fa-solid fa-check text-green-500"></i>
                            <i v-else class="fa-solid fa-xmark text-red-500"></i>
                        </td>
                        <td class="p-2 text-center">
                            <button @click="showAddVerificationLinkModal(task)" :disabled="task.status == 'Done'"  class="px-2 py-1 border border-blue-900 text-blue-900 rounded-md text-sm hover:bg-blue-900 hover:text-white disabled:border-gray-300 disabled:bg-gray-300 disabled:text-white disabled:cursor-not-allowed" type="button">
                                Add Link
                            </button>
                        </td>
                        <td class="p-2 text-center">
                            <button @click="showSendModal(task)" :disabled="task.is_sent" class="px-2 py-1 border border-blue-900 text-blue-900 rounded-md text-sm hover:bg-blue-900 hover:text-white disabled:border-gray-300 disabled:bg-gray-300 disabled:text-white disabled:cursor-not-allowed" type="button">
                                Send
                            </button>
                        </td>
                        <td class="p-2 text-center">
                            <button @click="showUpdateModal(task)" :disabled="task.is_sent" class="px-2 py-1 border border-blue-900 text-blue-900 rounded-md text-sm hover:bg-blue-900 hover:text-white disabled:border-gray-300 disabled:bg-gray-300 disabled:text-white disabled:cursor-not-allowed" type="button">
                                Edit
                            </button>
                        </td>
                        <td class="p-2 text-center">
                            <button @click="showDeleteModal(task)" :disabled="task.is_sent" class="px-2 py-1 border border-blue-900 text-blue-900 rounded-md text-sm hover:bg-blue-900 hover:text-white disabled:border-gray-300 disabled:bg-gray-300 disabled:text-white disabled:cursor-not-allowed" type="button">
                                Delete
                            </button>
                        </td>
                        <td class="p-2 text-center">
                            <button @click="showFinishProject(task)" class="px-2 py-1 border border-blue-900 text-blue-900 rounded-md text-sm hover:bg-blue-900 hover:text-white disabled:border-gray-300 disabled:bg-gray-300 disabled:text-white disabled:cursor-not-allowed" type="button">
                                Finish
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

        <AddAccountModal :website="modalTask" :show="showAddAccountModal" @close="showAddAccountModal = false" />

        <ConfirmationModal :show="showRejectWebsiteConfirmation" @close="showRejectWebsiteConfirmation = false">

            <template #title>
                Reject Website Confirmation
            </template>

            <template #content>
                <p class="text-base font-bold">
                    Are you sure you want to reject this Website ?
                </p>
            </template>

            <template #footer>
                <div class="flex flex-row justify-end gap-2 bg-gray-100 text-right">
                    <PrimaryButton @click="showRejectWebsiteConfirmation = false" type="button">cancel</PrimaryButton>
                    <PrimaryButton @click="rejectWebsite(modalTask)" type="submit">Reject</PrimaryButton>
                </div>
            </template>

        </ConfirmationModal>

        <ConfirmationModal :show="showFinishTaskConfirmation" @close="showFinishTaskConfirmation = false">

            <template #title>
                Finish Task Confirmation
            </template>

            <template #content>
                <p class="text-base font-bold">
                    Are you sure you want to mark this task as finished ?
                </p>
            </template>

            <template #footer>
                <div class="flex flex-row justify-end gap-2 bg-gray-100 text-right">
                    <PrimaryButton @click="showFinishTaskConfirmation = false" type="button">cancel</PrimaryButton>
                    <PrimaryButton @click="finishTask(modalTask)" type="submit">Finish</PrimaryButton>
                </div>
            </template>

        </ConfirmationModal>

        <AddVerificationLinkModal :account="modalTask" :show="showVerificationLinkModal" @close="showVerificationLinkModal = false" />

        <UpdateAccountModal :account="modalTask" :show="showUpdateAccountModal" @close="showUpdateAccountModal = false" />

        <ConfirmationModal :show="showDeleteConfirmation" @close="showDeleteConfirmation = false">

            <template #title>
                Delete Account Confirmation
            </template>

            <template #content>
                <p class="text-base font-bold">
                    Are you sure you want to delete this account ?
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
                    <PrimaryButton @click="deleteAccount(modalTask)" type="submit">delete</PrimaryButton>
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

        <ConfirmationModal :show="showFinishProjectModal" @close="showFinishProjectModal = false">

            <template #title>
                Finish Project Confirmation
            </template>

            <template #content>
                <p class="text-base font-bold">
                    Are you sure you want to Finish this project ?
                </p>
            </template>

            <template #footer>
                <div class="flex flex-row justify-end gap-2 bg-gray-100 text-right">
                    <PrimaryButton @click="showFinishProjectModal = false" type="button">cancel</PrimaryButton>
                    <PrimaryButton @click="finishProject(modalTask, 'Done')" type="submit">Done</PrimaryButton>
                    <PrimaryButton @click="finishProject(modalTask, 'On Hold')" type="submit">Undone</PrimaryButton>
                </div>
            </template>

        </ConfirmationModal>

</template>
