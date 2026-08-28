<script setup>
import {onMounted, reactive, useTemplateRef, ref, toRaw} from 'vue';
import {useStateStore} from "../services/state.js";

let resultsList = reactive([]);
const state     = useStateStore();
let showShield  = ref(false);
let page        = 0;
let isInView    = false;

async function loadMore() {
    const token  = document.querySelector('meta[name="csrf-token"]').content;
    const filter = state.prefs.tag_filter;
    if (filter) {
        console.log("Loading filtered data", filter);
        let currentData = structuredClone(toRaw(resultsList.value));

        fetch("/results", {
            method: "POST",
            headers: {"Content-Type": "application/json", "X-CSRF-TOKEN": token},
            body: JSON.stringify({
                filter: filter,
                page: page
            })
        }).then(response => response.json()).then(data => {
            console.log("Loaded " + data.length + " rows for page " + page);

            if (data.length) {
                let start = 0;
                if (!currentData) {
                    currentData = [data[0]];
                    start       = 1;
                }
                for (var i = start; i < data.length; i++) currentData.push(data[i]);
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
    const rect    = element.getBoundingClientRect();

    isInView = rect.top < window.innerHeight && rect.bottom > 0;
    if (!wasInView && isInView) {
        page++;
        loadMore();
    }
}

onMounted(() => {
    window.addEventListener("filter-updated", function (event) {
        console.log("Filter updated");
        resultsList.value = [];
        page              = 0;
        loadMore();
        window.scrollTo(0, 0);
    });
    window.addEventListener('scroll', checkVisibility, {passive: true});
    window.addEventListener('resize', checkVisibility);
    loadMore();
});

const imgClicked = function (photo) {

    let element = window.document.getElementById("img_" + photo.img_id);
    if (element) {
        console.log("Zooming element");
        showShield.value = true;
        if (element.style.position !== "fixed") {
            element.style.position  = "fixed";
            element.style.left      = "0";
            element.style.top       = "0";
            element.style.height    = "100%";
            element.style.objectFit = "contain";
            element.style.minHeight = "stretch";
            element.style.zIndex    = "50";

            window.document.getElementById("footer").style.display = "none";
        } else {
            showShield.value        = false;
            element.style.position  = "inherit";
            element.style.minHeight = "inherit";
            element.style.height    = "inherit";
            element.style.width    = "100%";


            window.document.getElementById("footer").style.display = "flex";
        }
    }


    return;
    console.log("Image clicked");
    console.log(photo);
    state.setSelectedPhoto(photo);
    router.push("/dw/img/" + photo.img_id);
};

</script>

<template>
    <div>
        <div class="shield fixed top-0 left-0 w-full h-full bg-white/90 z-10" v-if="showShield"></div>
        <div v-if="resultsList.value" class="flex flex-wrap mb-18 media">
            <div class="loop" v-for="(photo, idx) in resultsList.value" :key="photo.id">
                <div class="wrapper">
                    <div v-if="state.prefs.showFilename" class="img_filename ">{{ photo.img_name }}</div>
                    <div v-if="state.prefs.showTimestamp" class="img_timestamp">
                        <div class="img_creation_date">{{ photo.img_creation_date }}</div>
                        <div class="img_format">{{ photo.img_format }}</div>
                    </div>

                    <div v-if="photo.img_format==='MP4'">
                        <video class="format_mp4" controls>
                            <source :src="photo.img_path">
                        </video>
                    </div>

                    <div v-else-if="photo.img_format==='MP3'">
                        <audio class="format_mp3" controls :src="photo.img_path"></audio>
                    </div>

                    <div v-else-if="photo.img_format==='JPG' || photo.img_format==='GIF' || photo.img_format==='PNG' || photo.img_format==='WEBP' || photo.img_format==='AVIF'"
                         @click="imgClicked(photo)">
                        <img :id="`img_${photo.img_id}`" class="format_img" style="transition: transform-all 0.5s ease" :src="photo.img_path" loading="lazy" decoding="async" :alt="photo.img_path">
                    </div>

                    <div v-else>
                        <div class="">{{ photo.img_format }} is not yet supported</div>
                    </div>

                    <div v-if="state.prefs.showRating" class="img_rating">
                        <div class="img_rating_star" v-if="photo.img_rating>=1">
                            <img class="img_rating_star_filled" src="/svg/star-filled.svg"></div>
                        <div class="img_rating_star" v-if="photo.img_rating>=2">
                            <img class="img_rating_star_filled" src="/svg/star-filled.svg"></div>
                        <div class="img_rating_star" v-if="photo.img_rating>=3">
                            <img class="img_rating_star_filled" src="/svg/star-filled.svg"></div>
                        <div class="img_rating_star" v-if="photo.img_rating>=4">
                            <img class="img_rating_star_filled" src="/svg/star-filled.svg"></div>
                        <div class="img_rating_star" v-if="photo.img_rating>=5">
                            <img class="img_rating_star_filled" src="/svg/star-filled.svg"></div>
                    </div>

                    <div v-if="state.prefs.showTagsBelow" class="img_tags">
                        <div v-for="(item, index) in photo.tags" :key="item" class="img_tag">
                            <span class="img_tag_name">#{{ item.name }}</span>
                            <span class="img_tag_id" v-if="state.prefs.showTagId">({{ item.id }})</span>
                        </div>
                    </div>
                    <div v-if="state.prefs.showPath" class="pt-1">
                        <div class="text-xs rounded bg-slate-300 mr-1 p-1 overflow-hidden">{{ photo.img_path }}</div>
                    </div>
                    <div v-if="state.prefs.showImageId" class="pt-1">
                        <div class="text-xs rounded bg-slate-300 mr-1 p-1 overflow-hidden">{{ photo.img_id }}</div>
                    </div>
                </div>
                <div v-if="idx===(resultsList.value.length - 5)" ref="sentinel" id="sentinel"></div>
            </div>
        </div>
        <div v-else class="flex flex-col justify-center items-center border p-12 m-12 rounded bg-gray-100">
            <div class="font-bold text-xl">Welcome!</div>
            <div class="mt-12">Use the filter icon in the footer toolbar to start</div>
        </div>
    </div>
</template>
