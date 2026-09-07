<script setup>
import {useRoute} from "vue-router";
import {useStateStore} from "../services/state.js";
import {onMounted, reactive, ref} from "vue";
import dayjs from 'dayjs';

const state = useStateStore();
const route = useRoute();
const img_id = route.params.img_id;
let photo = reactive(state.getSelectedPhoto);
let info = reactive({});
let loaded = ref(false);

state.page.has_footer = false;
state.page.has_header = false;

onMounted(async () => {
    state.page.has_footer = false;
    state.page.has_header = false;

    await loadImage();
});

async function loadImage() {
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const ret = await fetch("/dw/image/" + img_id + "/info",
        {method: "POST", headers: {"Content-Type": "application/json", "X-CSRF-TOKEN": token}})
        .then(response => response.json()).then(data => {
            return data[0];
        });

    loaded.value = true;
    info = ret;
}

</script>

<template>

    <div v-if="loaded" class="image_info overflow-hidden">

        <div class="image_row">
            <img :src="'/dw/imgsrv/full/' + photo.img_hash" :id="`img_${photo.img_id}`" loading="lazy" decoding="async" :alt="photo.name" class="max-h-fit object-contain"/>

            <div class="info_row">

                <div class="lhs">
                    <div class="field">
                        <div class="label">Filename</div>
                        <div class="value">{{ photo.img_name }}</div>
                    </div>
                    <div class="field">
                        <div class="label">Digitization</div>
                        <div class="value">{{ photo.img_digitization_date ? dayjs(photo.img_digitization_date).format('dddd MMMM D, YYYY') : 'Unknown' }}</div>
                    </div>
                    <div class="field">
                        <div class="label">Creation</div>
                        <div class="value">{{ photo.img_creation_date ? dayjs(photo.img_creation_date).format('dddd MMMM D, YYYY') : 'Unknown' }}</div>
                    </div>

                    <div class="field">
                        <div class="label">Dimensions</div>
                        <div class="value">{{ info.width }} x {{ info.height }} px</div>
                    </div>
                    <div class="field">
                        <div class="label">Parameters</div>
                        <div class="value">{{ info.sensitivity ? 'ISO ' + info.sensitivity : 'ISO Unknown' }} f{{ info.aperture ?? '' }}</div>
                    </div>
                    <div class="field">
                        <div class="label">Camera</div>
                        <div class="columns-2 value">{{ info.make ?? 'Unknown' }} {{ info.model ?? '' }}</div>
                    </div>
                    <div class="field">
                        <div class="label">Lens</div>
                        <div class="columns-2 value">{{ info.lens ?? 'Unknown' }}</div>
                    </div>
                    <div class="field">
                        <div class="label">Location</div>
                        <div class="value">
                            {{ info.latitudeNumber ? info.latitudeNumber + 'N' : 'Unknown'}}
                            {{ info.longitudeNumber ? info.longitudeNumber + 'W' : info.latitudeNumber ? 'Unknown' : ''}}
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
