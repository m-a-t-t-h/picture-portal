<script setup>
import {useStateStore} from "../../services/state.js";
import {computed} from "vue";
import { useRouter} from "vue-router";
import {sprintf} from "sprintf-js";
const router = useRouter();


const state = useStateStore();
const props = defineProps({
    photo: Object,
});
const supportedFormats = new Set(['JPG', 'GIF', 'PNG', 'WEBP', 'AVIF'])
const isSupported = computed(() => {
    return supportedFormats.has(props.photo.img_format);
});
const imgClicked = function (photo) {
    state.setSelectedPhoto(photo);
    router.push("/dw/image/" + photo.img_hash + "/info");
};
</script>

<template>
    <div v-if="isSupported" class="img-container" @click="imgClicked(photo)">
        <img :src="'/dw/imgsrv/thumb/' + photo.img_hash" :id="`img_${photo.img_id}`"
             :class="'interactive ' + state.prefs.layout "
             loading="lazy" decoding="async" :alt="photo.img_path">
        <div v-if="state.prefs.showCameraInfo" class="camera-info">
            <div class="flex flex-row items-center">
                <div>{{ photo.camera_model }}</div>
            </div>
            <div class="flex flex-row items-center justify-between">
                <div class="flex flex-row items-center" v-if="photo.camera_aperture">
                    <img src="/svg/camera-fstop.svg" alt="Aperture" class="w-4 mr-1"/>
                    f{{ photo.camera_aperture }}
                </div>
                <div class="flex flex-row items-center" v-if="photo.camera_shutter">
                    <img src="/svg/camera-shutter.svg" alt="Shutter speed" class="w-4 mr-1"/>
                    1/{{ sprintf("%0.0f", 1 / photo.camera_shutter) }}s
                </div>
                <div class="flex flex-row items-center" v-if="photo.camera_iso">
                    <img src="/svg/camera-iso.svg" alt="ISO" class="w-6 mr-1"/>
                    {{ photo.camera_iso }}
                </div>
                <div class="flex flex-row items-center" v-if="photo.camera_focalLength">
                    <img src="/svg/camera-focallength.svg" alt="focal length" class="w-6 mr-1"/>
                    {{ photo.camera_focalLength }}mm
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.img-container {

    img.interactive {
        @apply  border-2 border-transparent;
        @apply hover:border-blue-500 hover:shadow-[0_0_8px_rgb(0,0,0)];
        @apply hover:cursor-pointer;
    }

    .layout-1 {
        @apply mx-auto w-full min-w-full;
        @apply w-100 min-h-100 max-h-100;
        @apply transition-all duration-200;
        @apply object-cover object-[50%_20%];
    }

    .layout-2 {
        @apply w-100 min-h-100 max-h-100;
        @apply object-cover;
        @apply rounded;
    }

    .layout-3 {
        @apply h-16 w-16;
        @apply object-cover;
    }

    .camera-info {
        @apply -mt-12 h-12 z-0 w-full;
        @apply p-1 px-2;
        @apply bg-slate-50/70 backdrop-blur-xs shadow-[0px_0px_15px_#fff];
        @apply rounded-sm  relative inset-0   text-slate-700  text-xs;
    }


}

</style>
