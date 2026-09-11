<script setup>
import {useStateStore} from "../../services/state.js";

defineProps({
    photo: Object,
});

const state = useStateStore();
</script>

<template>
    <div v-if="state.prefs.showTagsBelow" class="iinfo img_tags">
        <div v-for="(item) in photo.tags" :key="item" class="img_tag">
            <span class="img_tag_name">#{{ item[1] }}</span>
            <span class="img_tag_id" v-if="state.prefs.showTagId">({{ item.id }})</span>
        </div>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.img_tags {
    @apply p-0 m-0;
}

</style>
