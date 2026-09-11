<script setup>
import {useStateStore} from "../../services/state.js";

defineProps({
    photo: Object,
});

const state = useStateStore();
</script>

<template>
    <div v-if="state.prefs.showImageId" class="iinfo img_id">
        #{{ photo.img_id }}
    </div>
</template>
