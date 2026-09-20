<script setup>
import {ref, watch} from 'vue';
import {Select} from "vue3-select-component";
import {useStateStore} from "../../services/state.js";

const state = useStateStore();

const orderByOptions = [
    {label: 'Random', value: '7'},
    {label: 'Rating (asc)', value: '8'},
    {label: 'Rating (desc)', value: '9'},
    {label: 'Newest', value: '5'},
    {label: 'Oldest', value: '6'},
    {label: 'ID (asc)', value: '1'},
    {label: 'ID (desc)', value: '2'},
    {label: 'Filename (asc)', value: '3'},
    {label: 'Filename (desc)', value: '4'},
];
const orderBy = ref(state.prefs.orderBy);
watch(orderBy, async () => {
    state.setOrderBy(orderBy);
});
</script>

<template>
    <div class="toolbar-element ">
        <label>Order by</label>
        <Select v-model="orderBy" :options="orderByOptions" placeholder="Order by"></Select>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

</style>
