<script setup>
import {useStateStore} from "../../services/state.js";

defineProps({
    photo: Object,
});

const state = useStateStore();
</script>

<template>
    <div v-if="state.prefs.showImageId" class="img_id">
        #{{ photo.img_id }}
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.layout-3 .img_id {
    @apply text-xs;
    @apply min-w-12 w-12 text-center;
}
</style>
