<script setup>
import {useStateStore} from "../services/state.js";
const showMainMenu = import.meta.env.VITE_SHOW_MAIN_MENU;
const state = useStateStore();
const emits = defineEmits(["open-main-menu"]);

defineProps({
    isMainMenuOpen: Boolean,
});

</script>

<template>
    <div class="btn-row ">
        <button class="ikon" @click="emits('open-main-menu')" v-if="showMainMenu">
            <img :aria-expanded="isMainMenuOpen" aria-label="Open main menu" src="/svg/menu.svg">
        </button>
        <h1>Image view header, with main menu</h1>

    </div>
</template>
