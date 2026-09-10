<script setup>
import {useStateStore} from "../services/state.js";

const state = useStateStore();
const authed = state.auth.isAuthed;
const close = (event) => {
    window.dispatchEvent(new CustomEvent("toggle-settings-panel"));
};

const emits = defineEmits(["close-main-menu"]);
const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
</script>

<template>
    <div class="panel-container menu-page">
        <div class="panel-heading">
            <div class="panel-heading-inner">
                <h1>PicturePortal</h1>
                <div class="icon-wrapper">
                    <button class="ikon ikon-wb" @click="emits('close-main-menu')">
                        <img src="/svg/close.svg" alt="Close menu">
                    </button>
                </div>
            </div>
        </div>
        <div class="flex flex-col panel-body">
            <div class="opacity-50 w-full borer-b ">

                <a v-if="!authed" class="btn-row  " href="/login" >
                    <img src="/svg/login.svg"  alt="login">
                    <span class="text">Log in</span>
                </a>

                <a v-if="authed" class="btn-row " href="/dw/logout">
                    <img src="/svg/logout.svg" class="w-10">
                    <span class="text">Log out</span>
                </a>

            </div>

        </div>
        <div class="panel-footer">
            <div class="text-xs">
                DigiKamWeb is copyright (c) 2026 Matt Hoskison
            </div>
        </div>
    </div>

</template>
