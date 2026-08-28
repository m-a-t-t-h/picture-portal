<script setup>

defineProps({
    filterPanelOpen: Boolean,
    settingsPanelOpen: Boolean,
});

const emits = defineEmits(["filter-panel-toggle", "settings-panel-toggle"]);

window.addEventListener("toggle-filter-panel", function (event) {
//    console.log("Footer: toggle-filter-panel");
//    emits("filter-panel-toggle");
});

</script>

<template>

    <button class="btn" @click="emits('filter-panel-toggle')"   :aria-expanded="filterPanelOpen" aria-label="Open filter panel" >Ξ</button>

    <button v-if="0" class="btn">
        <router-link to="/dw"><img src="/svg/trashcan.svg"></router-link>
    </button>

    <button class="btn" @click="emits('settings-panel-toggle')"   :aria-expanded="settingsPanelOpen" aria-label="Open settings panel" >⚙</button>




</template>

<style scoped>

</style>
