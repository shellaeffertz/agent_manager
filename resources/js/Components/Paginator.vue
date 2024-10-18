<script setup>

import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Object,
        required: true
    }
});

</script>

<template>

    <div class="block space-x-1" v-if="links.length > 3">

        <Component
            v-for="link in links"
            :key="link.id"
            :is="link.url ? Link : 'span'"
            class="px-2 md:px-3 py-1 md:py-2 border border-gray-300 rounded-md text-xs"
            v-html="link.label"
            :class="{'bg-blue-900 text-white font-bold': link.active, 'text-gray-400 border-gray-200 cursor-not-allowed': !link.url, 'hover:bg-gray-200': link.url && !link.active}"
            :href="link.url"
            preserve-scroll
        />

    </div>

</template>