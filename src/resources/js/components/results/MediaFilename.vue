<script setup>
import {useStateStore} from "../../services/state.js";

defineProps({
    photo: Object,
});

const state = useStateStore();
</script>

<template>
    <div v-if="state.prefs.showFilename" class="media-filename">
        <div class="inner">{{ photo.img_name }}</div>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.media-filename {
    @apply text-sm pl-1;
    @apply whitespace-nowrap overflow-hidden;
    @apply border border-t-0 border-l-0;
    @apply border-slate-200 shadow-sm;

    .inner {
    }
}
</style>
