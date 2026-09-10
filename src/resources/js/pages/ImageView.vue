<script setup>
import {useRoute} from "vue-router";
import {useStateStore} from "../services/state.js";
import {onMounted, reactive, ref} from "vue";
import dayjs from 'dayjs';
import { sprintf } from 'sprintf-js'

const state = useStateStore();
const route = useRoute();
const img_hash = route.params.img_hash;
let photo = reactive(state.getSelectedPhoto);
let info = reactive({});
let loaded = ref(true);

state.page.has_footer = false;
state.page.has_header = false;

onMounted(async () => {
    state.page.has_footer = false;
    state.page.has_header = false;

    window.scrollTo(0, 0);
});

import { useRouter } from 'vue-router'
const router = useRouter()
function goBack() {
    router.back()
}

</script>

<template>

    <button type="button" @click="goBack" class="fixed z-50 hover:curor-pointer border p-1 bg-white -mt-12">
        Back to images
    </button>

    <div class="image_info ">

        <div class="image_row">
            <img
                @click="goBack"
                :src="'/dw/imgsrv/full/' + img_hash" :id="`img_${photo.img_id}`" loading="lazy" decoding="async" :alt="photo.name" class="max-h-fit object-contain"/>

            <div class="info_row">

                <div class="lhs">

                    <div class="field ">
                        <div class="img_tags">
                            <div v-for="(item) in photo.tags" :key="item" class="img_tag">
                                <span class="img_tag_name">#{{ item[1] }}</span>
                                <span class="img_tag_id" v-if="state.prefs.showTagId">({{ item.id }})</span>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <div class="label">Filename</div>
                        <div class="value">{{ photo.img_name }}</div>
                    </div>


                    <div class="field">
                        <div class="label">Digitization</div>
                        <div class="value">{{
                                photo.img_digitization_date ? dayjs(photo.img_digitization_date).format('dddd MMMM D, YYYY') : 'Unknown'
                            }}
                        </div>
                    </div>
                    <div class="field">
                        <div class="label">Creation</div>
                        <div class="value">{{
                                photo.img_creation_date ? dayjs(photo.img_creation_date).format('dddd MMMM D, YYYY') : 'Unknown'
                            }}
                        </div>
                    </div>

                    <div class="field">
                        <div class="label">Dimensions</div>
                        <div class="value">{{ photo.img_width }} x {{ photo.img_height }} px</div>
                    </div>
                    <div class="field">
                        <div class="label">Parameters</div>
                        <div class="value">
                            <div class="flex flex-row items-center justify-between">
                                <div class="flex flex-row items-center" v-if="photo.camera_aperture">
                                    f{{ photo.camera_aperture }}
                                </div>

                                <div class="flex flex-row items-center" v-if="photo.camera_shutter">
                                    1/{{ sprintf("%0.0f", 1/photo.camera_shutter) }}s
                                </div>

                                <div class="flex flex-row items-center" v-if="photo.camera_iso">
                                    ISO {{ photo.camera_iso }}
                                </div>

                                <div class="flex flex-row items-center" v-if="photo.camera_focalLength">
                                    {{ photo.camera_focalLength}}mm
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <div class="label">Camera</div>
                        <div class="columns-2 value">{{ photo.camera_make ?? 'Unknown' }} {{ photo.camera_model ?? '' }}</div>
                    </div>
                    <div class="field">
                        <div class="label">Lens</div>
                        <div class="columns-2 value">{{ photo.camera_lens ?? 'Unknown' }}</div>
                    </div>
                    <div class="field">
                        <div class="label">Location</div>
                        <div class="value">
                            {{ photo.geo_lat ? photo.geo_lat : 'Unknown' }}
                            {{ photo.geo_long ? photo.geo_long : '' }}
                        </div>
                    </div>
                    <div class="field">
                        <div class="label">Altitude</div>
                        <div class="value">{{ info.altitude ? info.altitude + 'ft' : 'Unknown' }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>
