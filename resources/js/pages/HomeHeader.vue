<script setup>
import {useAuth} from "../services/useAuth.js";

const user  = useAuth();
const emits = defineEmits(["open-main-menu"]);
const showMainMenu = import.meta.env.VITE_SHOW_MAIN_MENU;

console.log("Show main menu: [" + showMainMenu + "]");


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

    <div class="rhs">
        <div v-if="!user">
            <a href="/auth/login" class="btn px-3">
              Login
            </a>
        </div>
        <div v-else>

        </div>
    </div>
</template>
