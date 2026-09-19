<script setup>
import {ref, watch} from 'vue';
import {Select} from "vue3-select-component";
import {useStateStore} from "../../services/state.js";

const state = useStateStore();

const layoutOptions = [
    {label: 'Layout 1', value: 'layout-1'},
    {label: 'Layout 2', value: 'layout-2'},
    {label: 'Layout 3', value: 'layout-3'},
];
const layout = ref(state.prefs.layout);
watch(layout, async () => {
    state.setLayout(layout);
});
</script>

<template>
    <div class="toolbar-element">
        <label>Layout</label>
        <Select v-model="layout" :options="layoutOptions" placeholder="Layout"></Select>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

</style>
