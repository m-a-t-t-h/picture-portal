<script setup>
import {useStateStore} from "../../services/state.js";
import MediaRendererMP3 from "./MediaRendererMP3.vue";
import MediaRendererMP4 from "./MediaRendererMP4.vue";
import MediaRendererImage from "./MediaRendererImage.vue";

defineProps({
    photo: Object,
});

const state = useStateStore();
</script>

<template>
    <div class="media">
        <MediaRendererMP4 :photo="photo"></MediaRendererMP4>
        <MediaRendererMP3 :photo="photo"></MediaRendererMP3>
        <MediaRendererImage :photo="photo"></MediaRendererImage>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.media {
    @apply p-0 mx-auto;
}
</style>
