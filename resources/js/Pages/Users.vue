<script setup>

import Users from '../Components/Users.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    users: {
        type: Object,
        required: true
    }
});

</script>

<template>

    <AppLayout title="Users">

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight capitalize">
                All Users
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Users :users="users" />
                </div>
            </div>
        </div>
    </AppLayout>

</template>
