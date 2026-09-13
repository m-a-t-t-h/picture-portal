<script setup>
import {useStateStore} from "../../services/state.js";
import {computed} from "vue";

const state = useStateStore();
const props = defineProps({
    photo: Object,
});
const supportedFormats = new Set(['MP4'])
const isSupported = computed(() => {
    return supportedFormats.has(props.photo.img_format);
});
</script>

<template>
    <div v-if="isSupported">
        <video class="format_mp4" controls>
            <source :src="photo.img_path">
        </video>
    </div>
</template>
