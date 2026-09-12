<script setup>
import {useStateStore} from "../../services/state.js";

defineProps({
    photo: Object,
});

const state = useStateStore();
</script>

<template>
    <div v-if="state.prefs.showTimestamp" class="media-timestamp">
        <div class="img_creation_date">{{ photo.img_creation_date }}</div>
        <div class="img_format">{{ photo.img_format }}</div>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.media-timestamp {
    @apply p-1;
    @apply flex flex-row justify-between;
    @apply text-slate-500 text-xs;
}
</style>
