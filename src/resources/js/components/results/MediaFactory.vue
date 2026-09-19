<script setup>
import {useStateStore} from "../../services/state.js";
import MediaRendererMP3 from "./MediaRendererMP3.vue";
import MediaRendererMP4 from "./MediaRendererMP4.vue";
import MediaRendererImage from "./MediaRendererImage.vue";
import MediaHeader from "./MediaHeader.vue";
import MediaFooter from "./MediaFooter.vue";
import MediaPath from "./MediaPath.vue";
import MediaRatingStars from "./MediaRatingStars.vue";
import MediaTags from "./MediaTags.vue";
import MediaType from "./MediaType.vue";
import MediaImageSequence from "./MediaImageSequence.vue";
import MediaFilename from "./MediaFilename.vue";
import MediaTimestamp from "./MediaTimestamp.vue";
import MediaImageId from "./MediaImageId.vue";

defineProps({
    photo: Object,
});

const state = useStateStore();
</script>

<template>

    <div v-if="state.prefs.layout === 'layout-table'" class="layout-table">
        <div class="table-row">
            <div class="flex flex-col cell">
                <MediaImageSequence :photo></MediaImageSequence>
                <MediaType :photo></MediaType>
            </div>
            <MediaRendererMP4 class="cell" :photo></MediaRendererMP4>
            <MediaRendererMP3 class="cell" :photo></MediaRendererMP3>
            <MediaRendererImage class="cell" :photo></MediaRendererImage>
            <MediaPath class="cell" :photo></MediaPath>
            <MediaRatingStars class="cell" :photo></MediaRatingStars>
            <MediaTags class="cell" :photo></MediaTags>
        </div>
    </div>

    <div v-else class="media-wrapper">
        <MediaHeader :photo>
            <template #header-1>
                <MediaFilename :photo></MediaFilename>
            </template>
            <template #header-2>
                <MediaTimestamp :photo></MediaTimestamp>
            </template>
        </MediaHeader>

        <div class="media-container">
            <MediaRendererMP4 :photo></MediaRendererMP4>
            <MediaRendererMP3 :photo></MediaRendererMP3>
            <MediaRendererImage :photo></MediaRendererImage>
        </div>

        <MediaFooter :photo>
            <template #footer-1>
                <MediaRatingStars :photo></MediaRatingStars>
            </template>
            <template #footer-2>
                <MediaTags :photo></MediaTags>
            </template>
            <template #footer-3>
                <MediaImageId :photo></MediaImageId>
            </template>
            <template #footer-4>
            </template>

        </MediaFooter>
    </div>

</template>

<style scoped>
@reference "tailwindcss";

.media-wrapper {
    @apply h-full;
    @apply overflow-hidden;
}

.layout-comfy {
    .wrapper {
        @apply shadow-[0_0_20px_rgba(0,0,0,.7)];
    }
}

.layout-table {
    @apply w-full min-w-full;
}

.layout-table .table-row {
    @apply flex flex-row  gap-4;
    @apply w-full min-w-full;
    @apply items-center;

    .cell {
        @apply items-center;
    }
}
</style>
