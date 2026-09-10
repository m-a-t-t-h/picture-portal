<script setup>

defineProps({
  filterPanelOpen: Boolean,
  settingsPanelOpen: Boolean,
});

const emits = defineEmits(["filter-panel-toggle", "settings-panel-toggle"]);

window.addEventListener("toggle-filter-panel", function (event) {
});

</script>

<template>

  <div class="panel-footer btn-row ">
    <button class="btn" @click="emits('filter-panel-toggle')" :aria-expanded="filterPanelOpen" aria-label="Open filter panel">
      <img src="/svg/filter.svg" alt="Open filter panel">
    </button>

    <button class="btn" @click="emits('settings-panel-toggle')" :aria-expanded="settingsPanelOpen" aria-label="Open settings panel">
      <img src="/svg/settings.svg" alt="Open settings panel">

    </button>

  </div>


</template>

<style scoped>

</style>
