<script setup>
import {ref, watch} from 'vue';
import {Select} from "vue3-select-component";
import {useStateStore} from "../../services/state.js";

const state = useStateStore();

</script>

<template>
    <div class="toolbar-element">
        <label>Page</label>
        <div class="border">{{state.prefs.page}}</div>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

</style>
