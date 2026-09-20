<script setup>
import {useStateStore} from "../../services/state.js";

defineProps({
    photo: Object,
});

const state = useStateStore();
</script>

<template>
    <div v-if="state.prefs.showImageId" class="media_format">
        {{ photo.img_format }}
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.layout-table .media_format {
    @apply text-xs;
}
</style>
