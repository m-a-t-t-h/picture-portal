<script setup>
import {useRoute} from "vue-router";
import {useStateStore} from "../services/state.js";
import {onMounted, reactive, ref} from "vue";

const state  = useStateStore();
const route  = useRoute();
const img_id = route.params.img_id;
let photo    = reactive(state.getSelectedPhoto);
let info     = reactive({});
let loaded   = ref(false);

onMounted(async () => {
    await loadImage();
});

async function loadImage() {
    console.log("Loading image info");
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const ret   = await fetch("/api/image/" + img_id + "/info",
        {method: "POST", headers: {"Content-Type": "application/json", "X-CSRF-TOKEN": token}})
        .then(response => response.json()).then(data => {
            console.log("Loaded image info");
            console.log(data[0]);
            return data[0];
        });

    loaded.value = true;
    info         = ret;
    return ret;
}

</script>

<template>

    <div v-if="loaded" class="image_info overflow-hidden">

        <div class="image_row">
            <img class="" :id="`img_${photo.img_id}`" :src="'/imgsrv/full/' + photo.img_hash" loading="lazy" decoding="async" :alt="photo.img_hash"/>
        <div class="info_row">

            <div class="lhs">

                <div class="field">
                    <div class="label">Filename</div>
                    <div class="value">{{ photo.img_name }}</div>
                </div>

                <div class="field">
                    <div class="label">Digitization</div>
                    <div class="value">{{ photo.img_digitization_date }}</div>
                </div>

                <div class="field">
                    <div class="label">Creation</div>
                    <div class="value">{{ photo.img_creation_date }}</div>
                </div>

            </div>
            <div class="rhs">

                <div class="field">
                    <div class="value">{{ info.width }} x {{ info.height }} px</div>
                </div>

                <div class="field">
                    <div class="value">ISO {{ info.sensitivity }} f{{ info.aperture }}</div>
                </div>

                <div class="field">
                    <div class="columns-2 value">{{ info.make }} {{ info.model }}</div>
                </div>

                <div class="field">
                    <div class="columns-2 value">{{ info.lens }}</div>
                </div>

                <div class="field">
                    <div class="col-span-2">
                        {{ info.latitudeNumber }}N {{ info.longitudeNumber }}W {{ info.altitude }}ft
                    </div>
                </div>


            </div>
        </div>

        </div>

    </div>

</template>
