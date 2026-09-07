<script setup>
import {useStateStore} from "../services/state.js";
import {useAuth} from "../services/useAuth.js";

const state = useStateStore();
const {user, loading} = useAuth();

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
          <button class="btn" @click="emits('close-main-menu')">
            <img src="/svg/close.svg">
          </button>
        </div>
      </div>
    </div>
    <div class="flex flex-col panel-body">
      <div class="opacity-50 w-full borer-b ">

        <a v-if="!user" class="btn-row " href="/login">
          <img src="/svg/login.svg" class="w-10">
          <span class="text">Log in</span>
        </a>

        <form v-if="user" method="POST" action="/logout">
          <button type="submit" class="btn-row ">
            <img src="/svg/logout.svg">
            <span class="text">Log out</span>
          </button>
          <input type="hidden" name="_token" :value="csrf">
        </form>


      </div>

    </div>
    <div class="panel-footer">
      <div class="text-xs">
        DigiKamWeb is copyright (c) 2026 Matt Hoskison
      </div>
    </div>
  </div>

</template>
