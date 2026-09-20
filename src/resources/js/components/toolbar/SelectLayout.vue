<script setup>
import {ref, watch} from 'vue';
import {Select} from "vue3-select-component";
import {useStateStore} from "../../services/state.js";

const state = useStateStore();
const layout = ref(state.prefs.layout);
const layoutOptions = [
    {label: 'Comfy', value: 'layout-comfy'},
    {label: 'Tight', value: 'layout-tight'},
    {label: 'Table', value: 'layout-table'},
];

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
