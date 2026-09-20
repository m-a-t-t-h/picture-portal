<script setup>
import {useStateStore} from "../../services/state.js";

defineProps({
    photo: Object,
});

const state = useStateStore();
</script>

<template>
    <div v-if="state.prefs.showImageId" class="img_sequence">
        #{{ photo.img_sequence }}
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.layout-table .img_sequence {
    @apply text-xs;
    @apply min-w-12 w-12 text-center;
}
</style>
