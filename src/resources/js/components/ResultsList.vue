<script setup>
import {onMounted, reactive, ref, toRaw} from 'vue';
import {useStateStore} from "../services/state.js";
import {useRouter} from "vue-router";
import MediaFactory from "./results/MediaFactory.vue";
import MediaHeader from "./results/MediaHeader.vue";
import MediaFooter from "./results/MediaFooter.vue";

let resultsList = reactive([]);
const state = useStateStore();
const router = useRouter();
let showShield = ref(false);
let page = 0;
let isInView = false;

async function loadMore() {
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const filter = state.prefs.tag_filter;
    if (filter) {
        let orderBy = state.prefs.orderBy;
        let currentData = structuredClone(toRaw(resultsList.value));

        fetch("/dw/results", {
            method: "POST",
            headers: {"Content-Type": "application/json", "X-CSRF-TOKEN": token},
            body: JSON.stringify({
                filter: filter,
                orderBy: orderBy,
                page: page
            })
        }).then(response => response.json()).then(data => {
            if (data.length) {
                let start = 0;
                if (!currentData) {
                    currentData = [data[0]];
                    start = 1;
                }
                for (var i = start; i < data.length; i++) {
                    currentData.push(data[i]);
                }
                resultsList.value = currentData;
                state.setResults(resultsList);

                window.dispatchEvent(new CustomEvent("data-loaded", {detail: data}));
            }
        });
    }
}

function checkVisibility() {
    const element = window.document.querySelector("#sentinel");
    if (!element) return;
    let wasInView = isInView;
    const rect = element.getBoundingClientRect();

    isInView = rect.top < window.innerHeight && rect.bottom > 0;
    if (!wasInView && isInView) {
        page++;
        loadMore();
    }
}

onMounted(() => {
    window.addEventListener("filter-updated", function () {
        resultsList.value = [];
        page = 0;
        loadMore();
        window.scrollTo(0, 0);
    });
    window.addEventListener('scroll', checkVisibility, {passive: true});
    window.addEventListener('resize', checkVisibility);
    loadMore();
});
</script>

<template>
    <div class="bg-white">
        <div class="shield fixed top-0 left-0 w-full h-full bg-white/90 z-10" v-if="showShield"></div>

        <div v-if="resultsList.value" class="media-loop">
            <div class="wrapper" v-for="(photo, idx) in resultsList.value" :key="photo.id">
                <MediaHeader :photo="photo"></MediaHeader>
                <MediaFactory :photo="photo"></MediaFactory>
                <MediaFooter :photo="photo"></MediaFooter>
                <div v-if="idx===(resultsList.value.length - 5)" ref="sentinel" id="sentinel"></div>
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

.wrapper {
    @apply flex flex-col justify-between ;
    @apply m-0.5 my-1  w-full;
    @apply bg-slate-50 border border-slate-300 rounded-md;

    .iinfo {
        @apply z-0;
        @apply min-h-6 p-1 h-auto;
        @apply flex flex-row items-center mb-1;
        @apply overflow-hidden;
    }



    .img_rating {
        @apply p-0;

        .img_rating_star {
            @apply flex;
            @apply bg-white/50 w-4 min-h-auto;
        }
    }

    .img_id {
        @apply w-full justify-end flex;
    }
}

</style>
