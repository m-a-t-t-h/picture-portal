<script setup>
import {onMounted, reactive, ref} from 'vue';
import {useStateStore} from "../services/state.js";
import {useRouter} from "vue-router";
import MediaFactory from "./results/MediaFactory.vue";
import SelectOrderBy from "./toolbar/SelectOrderBy.vue";
import SelectLayout from "./toolbar/SelectLayout.vue";
import PageNumber from "./toolbar/PageNumber.vue";
import api from "../services/api.js";
import PicturePortal from "../services/PicturePortal.js";

let resultsList = reactive([]);
const state = useStateStore();
const router = useRouter();
let showShield = ref(false);
let layoutHook = ref("layout-hook");
let isInView = false;

function checkVisibility() {
    const element = window.document.querySelector("#sentinel");
    if (!element) return;

    const rect = element.getBoundingClientRect();
    let wasntInView = !isInView;

    isInView = rect.top < window.innerHeight && rect.bottom > 0;

    if (wasntInView && isInView) {
        window.dispatchEvent(new CustomEvent("sentinel-exposed", {detail: {page: state.prefs.page}}));
    }
}

onMounted(() => {

    PicturePortal.logComponentLoaded("ResultsList");
    console.log("  On load, ResultsList count: [" + resultsList.value?.count + "]");
    console.log(resultsList.value);

    // if (!resultsList.value.length) {
    //     console.log("  No cached results, loading more from page " + state.filterPage);
    //     state.prefs.page = 0;
    //     api.loadMore();
    // } else {
    //     console.log("  Using cached results from page " + state.filterPage);
    //     resultsList.value = resultsList.value;
    // }

    state.prefs.page = 0;
    api.loadMore();

    window.addEventListener("filter-updated", function () {
        resultsList.value = [];
        state.prefs.page = 0;
        api.loadMore();
        window.scrollTo(0, 0);
    });
    window.addEventListener('scroll', checkVisibility, {passive: true});
    window.addEventListener('resize', checkVisibility);
    window.addEventListener("layout-changed", function (event) {
        console.log("layout-changed from " + event.detail.oldlayout + " to " + event.detail.layout.value);
        layoutHook.value.classList.remove(event.detail.oldlayout);
        layoutHook.value.classList.add(event.detail.layout.value);
    })

    window.addEventListener("sentinel-exposed", function (event) {
        console.log("sentinal-exposed: page [" + event.detail.page + "]");
        if (event.detail.page === state.filterPage) {
            state.prefs.page++;
            console.log("Incremented page to " + state.prefs.page);
            api.loadMore();
        }
    });
    window.addEventListener("data-loaded", function (event) {
        console.log("DATA", event.detail);
        console.log("Initial ID of new data: " + event.detail[0].img_id)

        if (resultsList.value?.length)
            resultsList.value.push(...event.detail);
        else
            resultsList.value = event.detail;
    });
});

</script>

<template>
    <div class="bg-white">
        <div class="shield fixed top-0 left-0 w-full h-full bg-white/90 z-10" v-if="showShield"></div>
        <div class="toolbar">
            <SelectOrderBy></SelectOrderBy>
            <SelectLayout></SelectLayout>
            <PageNumber></PageNumber>
        </div>

        <div v-if="resultsList.value" ref="layoutHook" :class="'overflow-scroll media-loop ' + state.prefs.layout">
            <div v-for="(photo, idx) in resultsList.value" :key="photo.id">
                <div v-if="idx === resultsList.value.length-5" id="sentinel" ref="sentinel">
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

.toolbar {
    @apply fixed -mt-12 z-10;
    @apply min-h-12 h-12 w-full;
    @apply flex flex-row items-center;
    @apply border;
    @apply bg-white;
}

.toolbar-element {
    @apply flex items-center px-1 gap-x-2;
    @apply text-xs;
}

.media-loop {
    @apply mt-12;

    &.layout-1 {
        @apply grid ;
        @apply grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-1;
    }

    &.layout-2 {
        @apply grid ;
        @apply grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-1;
    }

    &.layout-3 {
        @apply grid grid-cols-1;
    }
}


.wrapper.layout-1 {
    @apply flex flex-col ;
    @apply w-full;
    @apply bg-slate-50 border border-slate-300 rounded-md;
    @apply border-4;

    .img_id {
        @apply w-full justify-end flex;
    }
}

.wrapper.layout-3 {
    @apply flex flex-row min-w-full;


    & > :nth-child(2) {
        @apply min-w-100;
        @apply bg-blue-500;
    }

    & > :nth-child(3) {
        @apply min-w-100;
        @apply bg-green-200;
    }
}

</style>
