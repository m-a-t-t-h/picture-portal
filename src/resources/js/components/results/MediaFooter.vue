<script setup>
import {useStateStore} from "../../services/state.js";
import MediaRatingStars from "./MediaRatingStars.vue";
import MediaImageId from "./MediaImageId.vue";
import MediaTags from "./MediaTags.vue";
import MediaTimestamp from "./MediaTimestamp.vue";

defineProps({
    photo: Object,
});

const state = useStateStore();
</script>

<template>
    <div class="media-footer">
        <slot name="footer-1"/>
        <slot name="footer-2"/>
        <slot name="footer-3"/>
        <slot name="footer-4"/>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.media-footer {
    @apply text-xs;
}
</style>
