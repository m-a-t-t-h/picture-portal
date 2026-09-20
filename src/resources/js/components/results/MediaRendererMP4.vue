<script setup>
import {computed} from "vue";

const props = defineProps({photo: Object});
const supportedFormats = new Set(['MP4'])
const isSupported = computed(() => supportedFormats.has(props.photo.img_format));
</script>

<template>
    <div v-if="isSupported" class="format_mp4">
        <video controls loop class="max-h-100 min-h-100 w-full">
            <source :src="photo.img_path">
        </video>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.format_mp4 {
}
</style>
