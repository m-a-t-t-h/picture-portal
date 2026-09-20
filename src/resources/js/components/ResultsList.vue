<script setup>
import {onMounted, reactive, ref} from 'vue';
import {useStateStore} from "../services/state.js";
import {useRouter} from "vue-router";
import MediaFactory from "./results/MediaFactory.vue";
import api from "../services/api.js";

let resultsList = reactive([]);
const state = useStateStore();
const router = useRouter();
let showShield = ref(false);
let layoutHook = ref("layout-hook");
let isInView = false;

onMounted(() => {
    state.prefs.page = 0;
    api.loadMore();

    const onFilterChanged = (event) => {
        resultsList.value = [];
        state.prefs.page = 0;
        api.loadMore();
        window.scrollTo(0, 0);
    }
    const onLayoutChange = (event) => {
        layoutHook.value.classList.remove(event.detail.oldlayout);
        layoutHook.value.classList.add(event.detail.layout.value);
    }
    const onDataLoaded = (event) => {
        if (resultsList.value?.length) {
            resultsList.value.push(...event.detail);
        } else {
            resultsList.value = event.detail;
        }
        window.setTimeout(checkVisibility, 500);
    }
    const onSentinelExposed = (event) => {
        console.log("sentinal-exposed: page [" + event.detail.page + "]");
        if (event.detail.page === state.filterPage) {
            state.prefs.page++;
            console.log("Incremented page to " + state.prefs.page);
            api.loadMore();
        }
    }
    const checkVisibility = () => {

        const element = window.document.querySelector("#sentinel");
        if (!element) return;

        const rect = element.getBoundingClientRect();
        let wasntInView = !isInView;

        isInView = rect.top < window.innerHeight && rect.bottom > 0;

        if (wasntInView && isInView) {
            window.dispatchEvent(new CustomEvent("sentinel-exposed", {detail: {page: state.prefs.page}}));
        }
    }

    window.addEventListener("filter-updated", onFilterChanged);
    window.addEventListener('scroll', checkVisibility, {passive: true});
    window.addEventListener('resize', checkVisibility);
    window.addEventListener("layout-changed", onLayoutChange)
    window.addEventListener("sentinel-exposed", onSentinelExposed);
    window.addEventListener("data-loaded", onDataLoaded);
});
</script>

<template>
    <div :class="'results-list ' + state.prefs.layout" ref="layoutHook">
        <div class="shield fixed top-0 left-0 w-full h-full bg-white/90 z-10" v-if="showShield"></div>

        <div v-if="resultsList.value" :class="'media-loop'">
            <div v-for="(photo, idx) in resultsList.value" :key="photo.id" class="media-item">
                <div v-if="idx === resultsList.value.length-1" id="sentinel" ref="sentinel">
                    <MediaFactory :photo></MediaFactory>
                </div>
                <div v-else>
                    <MediaFactory :photo></MediaFactory>
                </div>
            </div>
        </div>
        <div v-else class="flex flex-col justify-center items-center border p-12 m-12 rounded bg-gray-100">
            <div class="font-bold text-xl">Welcome!</div>
            <div class="mt-12">Use the filter icon in the footer toolbar to start</div>
        </div>
    </div>

</template>

<style scoped>
@reference "tailwindcss";

.results-list {
    @apply p-1;
    @apply md:mt-12 mb-12;
}

.media-item {
    @apply h-full min-h-full;
    @apply shadow-[0_0_20px_#333];
}

.layout-comfy {
    .media-loop {
        @apply p-1 pt-2;
        @apply grid;
        @apply grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3;

        .media-item {
            @apply hover:border-[rgba(0,0,255,1)] ;
        }
    }
}

.layout-tight {
    .media-loop {
        @apply p-1 pt-2;
        @apply grid;
        @apply grid-cols-1 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-8 gap-1;
    }
}

.media-loop {
    .media-item {
        @apply border-4 border-transparent rounded;
    }
}

.layout-table {
    .media-loop {
        @apply h-20;
        @apply flex flex-col items-start;
    }
}

</style>
