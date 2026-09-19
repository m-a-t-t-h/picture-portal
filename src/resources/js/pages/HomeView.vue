<script setup>
import ResultsList from "../components/ResultsList.vue";
import {useStateStore} from "../services/state.js";
import SelectLayout from "../components/toolbar/SelectLayout.vue";
import SelectOrderBy from "../components/toolbar/SelectOrderBy.vue";
import PageNumber from "../components/toolbar/PageNumber.vue";
const state = useStateStore();

state.page.has_header = true;
state.page.has_footer = true;

</script>

<template>
    <div class="toolbar">
        <SelectOrderBy></SelectOrderBy>
        <SelectLayout></SelectLayout>
        <PageNumber></PageNumber>
    </div>
    <ResultsList></ResultsList>
</template>

<style scoped>
@reference "tailwindcss";

.toolbar {
    @apply hidden;
    @apply md:top-[96px];
    @apply md:fixed md:-mt-12 md:z-10;
    @apply md:min-h-12 md:h-12 md:w-full;
    @apply md:flex md:flex-row md:items-center;
    @apply bg-slate-200 md:shadow-[0_0_10px_#000];
}

.toolbar-element {
    @apply flex items-center px-1 gap-x-2;
    @apply text-xs;
}
</style>
