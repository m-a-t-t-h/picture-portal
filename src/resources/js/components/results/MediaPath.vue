<script setup>
import {useStateStore} from "../../services/state.js";

defineProps({
    photo: Object,
});

const state = useStateStore();

// For just the path, without the filename:
// photo.img_path.split("/").slice(0, -1).join("/")
//
</script>

<template>
    <div v-if="state.prefs.showPath" class="media-path">
        <div class="inner">{{ photo.img_path }}</div>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.media-path {
    @apply text-sm pl-1;
    @apply whitespace-nowrap overflow-hidden;
    @apply min-w-128;
    @apply flex-1;

    .inner {
    }
}
</style>
