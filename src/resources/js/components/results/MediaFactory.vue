<script setup>
import {useStateStore} from "../../services/state.js";
import MediaRendererMP3 from "./MediaRendererMP3.vue";
import MediaRendererMP4 from "./MediaRendererMP4.vue";
import MediaRendererImage from "./MediaRendererImage.vue";
import MediaHeader from "./MediaHeader.vue";
import MediaFooter from "./MediaFooter.vue";
import MediaImageId from "./MediaImageId.vue";
import MediaPath from "./MediaPath.vue";
import MediaRatingStars from "./MediaRatingStars.vue";
import MediaTags from "./MediaTags.vue";
import MediaType from "./MediaType.vue";

defineProps({
    photo: Object,
});

const state = useStateStore();
</script>

<template>
    <div v-if="state.prefs.layout!=='layout-3'" :class="'wrapper ' + state.prefs.layout">
        <MediaHeader :photo="photo"></MediaHeader>
        <MediaRendererMP4 :photo="photo"></MediaRendererMP4>
        <MediaRendererMP3 :photo="photo"></MediaRendererMP3>
        <MediaRendererImage :photo="photo"></MediaRendererImage>
        <MediaFooter :photo="photo"></MediaFooter>
    </div>
    <div v-else class="line">
        <div class="flex flex-col cell">
            <MediaImageId :photo></MediaImageId>
            <MediaType :photo></MediaType>
        </div>
        <MediaRendererMP4 class="cell" :photo="photo"></MediaRendererMP4>
        <MediaRendererMP3 class="cell" :photo="photo"></MediaRendererMP3>
        <MediaRendererImage class="cell" :photo="photo"></MediaRendererImage>
        <MediaPath class="cell" :photo></MediaPath>
        <MediaRatingStars class="cell" :photo></MediaRatingStars>
        <MediaTags class="cell" :photo></MediaTags>
    </div>

</template>

<style scoped>
@reference "tailwindcss";

.media {
    @apply p-0 mx-auto;
}

.layout-3 .line {
    @apply flex flex-row  gap-1;
    @apply border-t-slate-300 border-t min-w-full;
    @apply items-center h-auto;

    .cell {
        @apply flex;
        @apply items-center;
        @apply px-3;
        @apply shrink-0;
    }
}
</style>
