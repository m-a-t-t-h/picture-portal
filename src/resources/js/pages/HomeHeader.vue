<script setup>
const emits = defineEmits(["open-main-menu"]);
const showMainMenu = import.meta.env.VITE_SHOW_MAIN_MENU;

defineProps({
    isMainMenuOpen: Boolean,
});
</script>

<template>
    <div class="btn-row ">
        <button class="ikon" @click="emits('open-main-menu')" v-if="showMainMenu">
            <img :aria-expanded="isMainMenuOpen" aria-label="Open main menu" src="/svg/menu.svg" alt="Open main menu">
        </button>
        <h1 class="pl-3">Home</h1>
    </div>
</template>
