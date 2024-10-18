<script setup>

import { ref } from 'vue';
import Paginator from './Paginator.vue';
import { useForm } from '@inertiajs/vue3';
import AddWebsiteModal from './AddWebsiteModal.vue';
import UpdateWebsiteModal from './UpdateWebsiteModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';

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

let showAddWebsiteModal = ref(false);

let showUpdateWebsiteModal = ref(false);

let showDeleteConfirmation = ref(false);

let showSendConfirmation = ref(false);

let showRejectCompanyConfirmation = ref(false);

let showFinishTaskConfirmation = ref(false);

let modalTask = ref(null);

const form = useForm({
    id: ''
});

const showAddModal = (newTask) => {
    modalTask.value = newTask;
    showAddWebsiteModal.value = true;
}

const showUpdateModal = (newTask) => {
    modalTask.value = newTask;
    showUpdateWebsiteModal.value = true;
}

const showReject = (newTask) => {
    modalTask.value = newTask;
    showRejectCompanyConfirmation.value = true;
}

const showDone = (newTask) => {
    modalTask.value = newTask;
    showFinishTaskConfirmation.value = true;
}

const showDelete = (newTask) => {
    modalTask.value = newTask;
    showDeleteConfirmation.value = true;
}

const showSend = (newTask) => {
    modalTask.value = newTask;
    showSendConfirmation.value = true;
}

const deleteWebsite = (newTask) => {
    form.id = newTask.id;
    form.delete(`/task/destroy`);
    showDeleteConfirmation.value = false;
}

const sendTask = (newTask) => {
    form.id = newTask.id;
    form.put(`/task/send`);
    showSendConfirmation.value = false;
}

const rejectCompany = (newTask) => {
    form.id = newTask.id;
    form.put('/task/reject');
    showRejectCompanyConfirmation = false;
}

const finishTask = (newTask) => {
    form.id = newTask.id;
    form.put('/task/finish');
    showFinishTaskConfirmation = false;
}

</script>


<template>

        <div class="space-y-4">
            <h1 class="text-3xl font-black text-blue-900">Companies</h1>

            <table class="w-full border-2 border-gray-300">

                <thead>
                    <tr class="bg-blue-900 text-white font-black">
                        <td class="p-2 text-center capitalize">Drop</td>
                        <td class="p-2 text-center capitalize">Company Name</td>
                        <td class="p-2 text-center capitalize">Company Address</td>
                        <td class="p-2 text-center capitalize">Company Number</td>
                        <td class="p-2 text-center capitalize">Company Activity</td>
                        <td class="p-2 text-center capitalize">Status</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </thead>

                <tbody>
                    <tr class="even:bg-gray-100 text-xs" v-for="task in todoTasks.data" :key="task.id">
                        <td class="p-2 text-center capitalize">{{ task.drop.fullname }}</td>
                        <td class="p-2 text-center capitalize">{{ task.company_name }}</td>
                        <td class="p-2 text-center capitalize">{{ task.company_address }}</td>
                        <td class="p-2 text-center capitalize">{{ task.company_number }}</td>
                        <td class="p-2 text-center capitalize">{{ task.company_activity }}</td>
                        <td  class="p-2 text-center capitalize">
                            <span v-if="task.status == 'On Hold'" class="text-blue-900 font-black">{{ task.status }}</span>
                            <span v-if="task.status == 'Done'" class="text-green-500 font-black">{{ task.status }}</span>
                            <span v-if="task.status == 'Rejected'" class="text-red-500 font-black">{{ task.status }}</span>
                        </td>
                        <td  class="p-2 text-center">
                            <button
                                @click="showAddModal(task)"
                                class="px-3 py-2 border border-blue-900 text-blue-900 rounded-md hover:bg-blue-900 hover:text-white" 
                                type="button"
                            >
                                Add Website
                            </button>
                        </td>
                        <td  class="p-2 text-center">
                            <button
                                @click="showReject(task)"
                                class="px-3 py-2 border border-blue-900 text-blue-900 rounded-md hover:bg-blue-900 hover:text-white" 
                                type="button"
                            >
                                Reject
                            </button>
                        </td>
                        <td  class="p-2 text-center">
                            <button
                                @click="showDone(task)"
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
                <h1 class="text-3xl font-black text-blue-900">Websites</h1>
            </div>

            <table class="w-full border-2 border-gray-300">

                <thead>
                    <tr class="bg-blue-900 text-white font-black">
                        <td class="p-2 text-center capitalize">Drop</td>
                        <td class="p-2 text-center capitalize">Company</td>
                        <td class="p-2 text-center capitalize">Website Link</td>
                        <td class="p-2 text-center capitalize">Website Credentials</td>
                        <td class="p-2 text-center capitalize">Status</td>
                        <td class="p-2 text-center capitalize">Sent</td>
                        <td class="p-2 text-center capitalize"></td>
                        <td class="p-2 text-center capitalize"></td>
                        <td class="p-2 text-center capitalize"></td>
                    </tr>
                </thead>

                <tbody>
                    <tr class="even:bg-gray-100 text-xs" v-for="task in tasks.data" :key="task.id">

                        <td class="p-2 text-center capitalize">{{ task.drop.fullname }}</td>
                        <td class="p-2 text-center capitalize">{{ task.company.company_name }}</td>
                        <td class="p-2 text-center capitalize">{{ task.website_link }}</td>
                        <td class="p-2 text-center capitalize max-w-fit">{{ task.website_credentials }}</td>
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
                            <button @click="showSend(task)" :disabled="task.is_sent" class="px-2 py-1 border border-blue-900 text-blue-900 rounded-md text-sm hover:bg-blue-900 hover:text-white disabled:border-gray-300 disabled:bg-gray-300 disabled:text-white disabled:cursor-not-allowed" type="button">
                                Send
                            </button>
                        </td>
                        <td class="p-2 text-center">
                            <button @click="showUpdateModal(task)" :disabled="task.is_sent" class="px-2 py-1 border border-blue-900 text-blue-900 rounded-md text-sm hover:bg-blue-900 hover:text-white disabled:border-gray-300 disabled:bg-gray-300 disabled:text-white disabled:cursor-not-allowed" type="button">
                                Edit
                            </button>
                        </td>
                        <td class="p-2 text-center">
                            <button @click="showDelete(task)" :disabled="task.is_sent" class="px-2 py-1 border border-blue-900 text-blue-900 rounded-md text-sm hover:bg-blue-900 hover:text-white disabled:border-gray-300 disabled:bg-gray-300 disabled:text-white disabled:cursor-not-allowed" type="button">
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>

            </table>

            <Paginator :links="tasks.links" />
        </div>

        <AddWebsiteModal :company="modalTask" :show="showAddWebsiteModal" @close="showAddWebsiteModal = false" />

        <ConfirmationModal :show="showRejectCompanyConfirmation" @close="showRejectCompanyConfirmation = false">

            <template #title>
                Reject Company Confirmation
            </template>

            <template #content>
                <p class="text-base font-bold">
                    Are you sure you want to reject this Company ?
                </p>
            </template>

            <template #footer>
                <div class="flex flex-row justify-end gap-2 bg-gray-100 text-right">
                    <PrimaryButton @click="showRejectCompanyConfirmation = false" type="button">cancel</PrimaryButton>
                    <PrimaryButton @click="rejectCompany(modalTask)" type="submit">Reject</PrimaryButton>
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

        <UpdateWebsiteModal :website="modalTask" :show="showUpdateWebsiteModal" @close="showUpdateWebsiteModal = false" />

        <ConfirmationModal :show="showDeleteConfirmation" @close="showDeleteConfirmation = false">

            <template #title>
                Delete Website Confirmation
            </template>

            <template #content>
                <p class="text-base font-bold">
                    Are you sure you want to delete this website ?
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
                    <PrimaryButton @click="deleteWebsite(modalTask)" type="submit">delete</PrimaryButton>
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

</template>
