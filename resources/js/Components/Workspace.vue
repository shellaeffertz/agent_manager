<script setup>

import SupportWorkspace from './SupportWorkspace.vue';
import DropManagerWorkspace from './DropManagerWorkspace.vue';
import WebsiteCreatorWorkspace from './WebsiteCreatorWorkspace.vue';
import CompanyCreatorWorkspace from './CompanyCreatorWorkspace.vue';

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

</script>

<template>

    <div v-if="$page.props.auth.user.role == 'drop_manager'" class="p-4 space-y-8">
        <DropManagerWorkspace :todoTasks="todoTasks" :tasks="tasks" />
    </div>

    <div v-if="$page.props.auth.user.role == 'company_creator'" class="p-4 space-y-8">
        <CompanyCreatorWorkspace :todoTasks="todoTasks" :tasks="tasks" />
    </div>

    <div v-if="$page.props.auth.user.role == 'website_creator'" class="p-4 space-y-8">
        <WebsiteCreatorWorkspace :todoTasks="todoTasks" :tasks="tasks" />
    </div>

    <div v-if="$page.props.auth.user.role == 'support'" class="p-4 space-y-8">
        <SupportWorkspace :todoTasks="todoTasks" :tasks="tasks" />
    </div>

    

</template>
