<script setup>
import {useAuth} from "../services/useAuth.js";

const user  = useAuth();
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
        <h1>Home</h1>

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
