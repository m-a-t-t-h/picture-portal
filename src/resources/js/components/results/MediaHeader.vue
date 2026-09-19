<script setup>
import {useStateStore} from "../../services/state.js";
import MediaFilename from "./MediaFilename.vue";
import MediaImageSequence from "./MediaImageSequence.vue";

defineProps({
    photo: Object,
});

const state = useStateStore();
</script>

<template>
    <div class="media-header">
        <MediaFilename :photo="photo"></MediaFilename>
        <MediaImageSequence :photo></MediaImageSequence>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.media-header {
    @apply whitespace-nowrap;
    @apply m-0.5 p-1 rounded-sm;
    @apply bg-slate-200;
}
</style>
