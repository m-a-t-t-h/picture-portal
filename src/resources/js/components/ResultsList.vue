<script setup>
import {onMounted, reactive, ref, toRaw} from 'vue';
import {useStateStore} from "../services/state.js";
import {useRouter} from "vue-router";

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

const imgClicked = function (photo) {
    state.setSelectedPhoto(photo);
    router.push("/dw/image/" + photo.img_hash + "/info");
};

</script>

<template>
    <div class="bg-white">
        <div class="shield fixed top-0 left-0 w-full h-full bg-white/90 z-10" v-if="showShield"></div>

        <div v-if="resultsList.value" class="media-loop">
            <div class="wrapper" v-for="(photo, idx) in resultsList.value" :key="photo.id">
                <div v-if="state.prefs.showFilename" class="iinfo filename">{{ photo.img_name }}</div>
                <div v-if="state.prefs.showTimestamp" class="iinfo timestamp ">
                    <div class="img_creation_date">{{ photo.img_creation_date }}</div>
                    <div class="img_format">{{ photo.img_format }}</div>
                </div>

                <!-- media -->
                <div class="media">
                    <div v-if="photo.img_format==='MP4'">
                        <video class="format_mp4" controls>
                            <source :src="photo.img_path">
                        </video>
                    </div>
                    <div v-else-if="photo.img_format==='MP3'">
                        <audio class="format_mp3" controls :src="photo.img_path"></audio>
                    </div>
                    <div class="img-container pink" v-else-if="photo.img_format==='JPG' || photo.img_format==='GIF' || photo.img_format==='PNG' || photo.img_format==='WEBP' || photo.img_format==='AVIF'" @click="imgClicked(photo)">
                        <img :src="'/dw/imgsrv/thumb/' + photo.img_hash" :id="`img_${photo.img_id}`" class="format_img" loading="lazy" decoding="async" :alt="photo.img_path">
                        <div v-if="state.prefs.showCameraInfo" class=" rounded-lg  relative inset-0 -mt-10 h-10  text-slate-700 w-full z-20 text-xs ">
                            <div class="flex flex-col bg-slate-200/80 p-1 px-2">
                                <div>{{ photo.camera_model }}</div>
                                <div>f{{ photo.camera_aperture }} 1/{{ photo.camera_focalLength }}" ISO{{ photo.camera_iso }}</div>
                            </div>
                        </div>
                        <div v-if="state.prefs.showRating" class="iinfo img_rating">
                            <div class="img_rating_star" v-if="photo.img_rating===1">
                                <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-empty.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-empty.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-empty.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-empty.svg" alt="1 star rating">
                            </div>
                            <div class="img_rating_star" v-else-if="photo.img_rating===2">
                                <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-empty.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-empty.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-empty.svg" alt="1 star rating">
                            </div>
                            <div class="img_rating_star" v-else-if="photo.img_rating===3">
                                <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-empty.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-empty.svg" alt="1 star rating">
                            </div>
                            <div class="img_rating_star" v-else-if="photo.img_rating===4">
                                <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-empty.svg" alt="1 star rating">
                            </div>
                            <div class="img_rating_star" v-else-if="photo.img_rating===5">
                                <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-filled.svg" alt="1 star rating">
                            </div>
                            <div class="img_rating_star" v-else>
                                <img class="img_rating_star_filled" src="/svg/star-empty.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-empty.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-empty.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-empty.svg" alt="1 star rating">
                                <img class="img_rating_star_filled" src="/svg/star-empty.svg" alt="1 star rating">
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <div class="">{{ photo.img_format }} is not yet supported</div>
                    </div>
                </div>
                <!-- /media -->

                <div v-if="state.prefs.showTagsBelow" class="iinfo img_tags">
                    <div v-for="(item) in photo.tags" :key="item" class="img_tag">
                        <span class="img_tag_name">#{{ item[1] }}</span>
                        <span class="img_tag_id" v-if="state.prefs.showTagId">({{ item.id }})</span>
                    </div>
                </div>
                <div v-if="state.prefs.showPath" class="iinfo">
                    {{ photo.img_path }}
                </div>
                <div v-if="state.prefs.showImageId" class="iinfo img_id">
                    #{{ photo.img_id }}
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
