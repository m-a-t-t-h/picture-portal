<script setup>
import {onMounted, ref, watch} from "vue";
import {useStateStore} from "../services/state.js";
import VTree from "@wsfe/vue-tree";
import {useAuth} from "../services/useAuth.js";
import {Select} from "vue3-select-component";
import "vue3-select-component/styles";

let treeData         = ref([]);
let treeLoaded       = ref(false);
let showSelectedOnly = ref(false);
const treeRef        = ref();

const emits           = defineEmits(["filter-panel-toggle"]);
const state           = useStateStore();
const {user, loading} = useAuth();
let requestInFlight   = false;

const handleCheckedChange = (nodes) => {
    if (requestInFlight) return;
    let checked = [];
    nodes.forEach(function (i) {
        checked.push(i.id);
    });
    state.setTagFilter(JSON.stringify(checked));
};
const clearFilter         = (event) => {
    treeRef.value?.clearChecked();
    treeRef.value?.setExpandAll(false);
};
const collapseAll         = (event) => {
    treeRef.value?.setExpandAll(false);
};
const selectedOnly        = (event) => {
    showSelectedOnly.value = !showSelectedOnly.value;

    if (showSelectedOnly.value) {
        console.log("Showing selected only");
        treeRef.value.showCheckedNodes(true);
    } else {
        console.log("Showing all nodes");
        treeRef.value.showCheckedNodes(false);
    }
};
const orderBy             = ref(state.prefs.orderBy);
const orderByOptions      = [
    {label: 'ID (asc)', value: '1'},
    {label: 'ID (desc)', value: '2'},
    {label: 'Filename (asc)', value: '3'},
    {label: 'Filename (desc)', value: '4'},
    {label: 'Digitization date (asc)', value: '5'},
    {label: 'Digitization date (desc)', value: '6'},
    {label: 'Random', value: '7'}
];

onMounted(async () => {
    treeData         = await state.getTree;
    treeLoaded.value = true;

    // ---- Restore the previous state of the tree selections
    //
    let tags = state.prefs.tag_filter;
    if (tags.length) {
        tags = JSON.parse(tags);
        setTimeout(function () {
            requestInFlight = true;
            for (var i in tags) {
                treeRef.value.setChecked(tags[i], true);
                treeRef.value.setExpand(tags[i], true);
            }
            requestInFlight = false;
        }, 50);
    }
});
watch(orderBy, async (newval) => {
    state.setOrderBy(orderBy);
});
</script>

<template>
    <div class="panel-container">
        <div class="panel-heading">
            <div class="panel-heading-inner">
                <h1>Filter</h1>
                <div class="icon-wrapper flex flex-row items-center">
                    <div class=" border-r-2 border-r-slate-200 pr-3 mr-3 block">
                        <Select v-model="orderBy" :options="orderByOptions" placeholder="Order by"></Select>
                    </div>
                    <button @click="selectedOnly">
                        <img src="/svg/checkbox-checked.svg" class="w-6 h-6" alt="Show selected only"></button>
                    <button @click="collapseAll">
                        <img src="/svg/minimise.svg" class="w-6 h-6" alt="Collapse all open nodes"></button>
                    <button @click="clearFilter"><img src="/svg/trashcan.svg" class="w-6 h-6" alt="Reset filter">
                    </button>

                    <div class="border-l-2 border-l-slate-200 ml-3">
                        <button class="btn ml-3 " @click="emits('filter-panel-toggle')">
                            <img src="/svg/close.svg">
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <VTree ref="treeRef" v-if="treeLoaded" :data="treeData"
                   @checked-change="handleCheckedChange"
                   checkable animation :cascade="false">
                <template #node="{ node }">
                    <span :style="{ }">{{ node.title }}</span>
                </template>
            </VTree>
        </div>

        <div class="panel-footer">
            <div></div>
            <button @click="emits('filter-panel-toggle')"><span>Close</span></button>
        </div>
    </div>
</template>

<style>
@import 'https://cdn.jsdelivr.net/npm/@wsfe/vue-tree@latest/dist/style.css';
</style>
