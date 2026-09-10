<script setup>
import {onMounted} from "vue";
import {useStateStore} from "../services/state.js";

onMounted(async () => {
    const state = useStateStore();
    state.auth.isAuthed = false;
    await fetch("/auth/logout");
    location.href = "/";
})

</script>

<template></template>
