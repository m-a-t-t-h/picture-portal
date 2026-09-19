<script setup>
import {useStateStore} from "../../services/state.js";

defineProps({
    photo: Object,
});

const state = useStateStore();

</script>

<template>
    <div v-if="state.prefs.showTagsBelow" class="img_tags">
        <div v-for="(item) in photo.tags" :key="item" class="img_tag">
            <span class="img_tag_name">#{{ item[1] }}</span>
            <span class="img_tag_id" v-if="state.prefs.showTagId">({{ item.id }})</span>
        </div>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.img_tags {
    @apply flex flex-row flex-wrap  items-start gap-0.5 min-h-16 max-h-16 p-1;

    .img_tag {
        @apply text-[8pt] border-amber-50 border rounded bg-amber-100  p-1;
    }
}

</style>
