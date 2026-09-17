<script setup>
import {ref, watch} from 'vue';
import {Select} from "vue3-select-component";
import {useStateStore} from "../../services/state.js";
import SelectOrderBy from "./SelectOrderBy.vue";

const state = useStateStore();

</script>

<template>
    <div class="toolbar">
        <SelectOrderBy></SelectOrderBy>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.toolbar-element {
    @apply flex items-center px-1 gap-x-2;
    @apply text-xs;
}

</style>
