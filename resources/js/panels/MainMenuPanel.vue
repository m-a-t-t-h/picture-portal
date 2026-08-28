<script setup>
import {useStateStore} from "../services/state.js";
import {ref} from "vue";
import {useAuth} from "../services/useAuth.js";

const state           = useStateStore();
const {user, loading} = useAuth();

const close = (event) => {
    window.dispatchEvent(new CustomEvent("toggle-settings-panel"));
};

const emits = defineEmits(["close-main-menu"]);

</script>

<template>

    <div class="panel-container">

        <div class="panel-heading">
            <div class="panel-heading-inner">
                <h1>DigiKamWeb</h1>
                <div class="icon-wrapper">
                    <button class="btn" @click="emits('close-main-menu')">
                        <img src="/svg/close.svg">
                    </button>
                </div>
            </div>
        </div>
        <div class="flex flex-col panel-body">



            <div class="opacity-50 w-full borer-b ">
                <a class="btn flex flex-row items-center gap-3  hover:bg-slate-300 hover:rounded hover:text-black hover:shadow-2xl p-3 " href="/login">
                    <img src="/svg/login.svg" class="w-10">
                    Log in
                </a>
            </div>

        </div>
        <div class="panel-footer">
            <div class="text-xs">
            DigiKamWeb is copyright (c) 2026 Matt Hoskison
            </div></div>
    </div>

</template>
