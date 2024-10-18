<script setup>

import { ref } from 'vue';

defineProps(['modelValue'])

const emit = defineEmits(['update:modelValue']);

let filesNames = ref([]);

let files = ref([]);

let fileInput = ref(null);

const uploadFile = (e) => {

    files.value = Array.from(e.target.files);
    if(files.value) {
        filesNames.value =  files.value.map((file) => {
            return file.name;
        });
    } else {

        filesNames.value = [];
    }
    emit('update:modelValue', files.value);
}

const cancelUpload = () => {

    files.value = null;
    fileInput.value.value = null;
    filesNames.value = [];
    emit('update:modelValue', files.value);
}
</script>

<template>

    <div class="space-y-4">
        <div class="relative mt-2 bg-indigo-100 rounded-md h-28 border-2 border-dashed border-indigo-500">
            <input ref="fileInput" type="file" multiple @input="uploadFile" class="relative z-10 h-full w-full text-transparent focus:outline-none file:bg-transparent file:border-transparent file:text-transparent" />
            <div class="absolute top-1/2 -translate-y-1/2 w-full">
                <span class="flex justify-center items-center gap-2 text-xl text-indigo-500 w-fit mx-auto">
                    <i class="fa-solid fa-image"></i>
                </span>
                <span class="block mt-2 text-sm text-center">{{ filesNames.length !== 0 ? filesNames.join(', ') : 'choose files' }}</span>
            </div>
            <button v-if="files.length !== 0" @click="cancelUpload" type="button" class="absolute top-1 right-3 z-20 text-gray-800 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    </div>

</template>