<script setup>
import ResultsList from "../components/ResultsList.vue";
import {useStateStore} from "../services/state.js";
const state = useStateStore();

state.page.has_header = true;
state.page.has_footer = true;

</script>

<template>
    <ResultsList></ResultsList>
</template>

<style scoped>

</style>
