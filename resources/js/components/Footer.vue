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

  <div class="panel-footer btn-row ">
    <button class="btn" @click="emits('filter-panel-toggle')" :aria-expanded="filterPanelOpen" aria-label="Open filter panel">
      <img src="/svg/filter.svg">
    </button>

    <button class="btn" @click="emits('settings-panel-toggle')" :aria-expanded="settingsPanelOpen" aria-label="Open settings panel">
      <img src="/svg/settings.svg">

    </button>

  </div>


</template>

<style scoped>

</style>
