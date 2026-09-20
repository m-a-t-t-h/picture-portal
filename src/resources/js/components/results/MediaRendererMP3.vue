<script setup>
import {useStateStore} from "../../services/state.js";
import {computed} from "vue";

const state = useStateStore();
const props = defineProps({
    photo: Object,
});
const supportedFormats = new Set(['MP3'])
const isSupported = computed(() => {
    return supportedFormats.has(props.photo.img_format);
});
</script>

<template>
    <div v-if="isSupported" class="format_mp3">
        <audio controls loop :src="photo.img_path" class="format_mp3"></audio>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.format_mp3 {
    @apply w-full p-1;
}
</style>
