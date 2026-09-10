<script setup>
import {useStateStore} from "../services/state.js";

const emits = defineEmits(["open-main-menu"]);
const showMainMenu = import.meta.env.VITE_SHOW_MAIN_MENU;

defineProps({
    isMainMenuOpen: Boolean,
});
</script>

<template>
    <div class="flex flex-row items-center gap-3">
        <button class="btn" @click="emits('open-main-menu')" v-if="showMainMenu">
            <img :aria-expanded="isMainMenuOpen" aria-label="Open main menu" src="/svg/menu.svg" alt="Open main menu">
        </button>
        <h1 class="pl-3">Home</h1>
    </div>
</template>
