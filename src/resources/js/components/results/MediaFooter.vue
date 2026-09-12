<script setup>
import {useStateStore} from "../../services/state.js";
import MediaRatingStars from "./MediaRatingStars.vue";
import MediaImageId from "./MediaImageId.vue";
import MediaTags from "./MediaTags.vue";

defineProps({
    photo: Object,
});

const state = useStateStore();
</script>

<template>
    <div class="media-footer">
        <MediaTags :photo="photo"></MediaTags>
        <MediaImageId :photo="photo"></MediaImageId>
        <MediaRatingStars :photo="photo"></MediaRatingStars>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.media-footer {
    @apply text-xs;
    @apply whitespace-nowrap;
    @apply m-0.5 p-1 rounded-sm;
    @apply bg-slate-50;
    @apply min-h-[60px];
}
</style>
