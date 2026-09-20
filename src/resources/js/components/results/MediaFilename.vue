<script setup>
import {useStateStore} from "../../services/state.js";

defineProps({
    photo: Object,
});

const state = useStateStore();
</script>

<template>
    <div v-if="state.prefs.showFilename" class="media-filename">
        {{ photo.img_name }}
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.media-filename {
    @apply overflow-hidden whitespace-nowrap;
    @apply text-xs;
}
</style>
