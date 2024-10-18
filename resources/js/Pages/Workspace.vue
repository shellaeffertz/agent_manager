<script setup>

import AppLayout from '@/Layouts/AppLayout.vue';
import Workspace from '@/Components/Workspace.vue';

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

    <AppLayout title="Workspace">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight capitalize">
                <span v-if=" $page.props.auth.user.role !== 'admin'">Workspace of {{ $page.props.auth.user.role.split('_').join(' ') }}s</span>
                <span v-else>Dashboard</span>
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Workspace :todoTasks="todoTasks" :tasks="tasks" />
                </div>
            </div>
        </div>
    </AppLayout>

</template>
