<script setup>
import {useStateStore} from "../services/state.js";

const state = useStateStore();
const authed = state.auth.isAuthed;
const emits = defineEmits(["open-main-menu"]);

defineProps({
    isMainMenuOpen: Boolean,
});

</script>

<template>
    <div class="flex flex-row items-center gap-3">
        <button class="btn " @click="emits('open-main-menu')">
            <img :aria-expanded="isMainMenuOpen" aria-label="Open main menu" src="/svg/menu.svg">
        </button>
        <h1>Image view header, with main menu</h1>

    </div>
</template>
