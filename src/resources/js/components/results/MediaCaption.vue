<script setup>
import {useStateStore} from "../../services/state.js";

defineProps({
    photo: Object,
});

const state = useStateStore();
</script>

<template>
    <div v-if="state.prefs.showCaption" class="img_caption">
        {{ photo.img_caption }}
    </div>
</template>
<style scoped>
@reference "tailwindcss";

.img_caption {
    @apply  p-1 mt-1 text-sm ;
    @apply bg-slate-100/50;
    @apply border-slate-300/50 border-1;
}

</style>
