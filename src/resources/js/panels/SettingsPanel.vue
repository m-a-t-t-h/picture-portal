<script setup>
import {useStateStore} from "../services/state.js";
const state = useStateStore();
const authed = state.auth.isAuthed;
const emits = defineEmits(["settings-panel-toggle"]);
</script>

<template>

    <div class="panel-container">

        <div class="panel-heading">
            <div class="panel-heading-inner">
                <h1>Settings</h1>
                <div class="icon-wrapper">
                    <div class="border-l-2 border-l-slate-200 ml-3">
                        <button class="ikon ikon-wb ml-3 " @click="emits('settings-panel-toggle')">
                            <img src="/svg/close.svg" alt="Close panel">
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <div class="settings-group">
                <span class="label">Above image</span>

                <div class="field">
                    <label for="showFilename" class="hover:cursor-pointer" :class="{'disabled':!authed}">
                        <input type="checkbox" id="showFilename" :checked="state.prefs.showFilename" @change="state.toggleShowFilename" :disabled="!authed">
                        <span>Show filename</span>
                    </label>
                </div>
                <div class="field">
                    <label for="showTimestamp" class="hover:cursor-pointer">
                        <input type="checkbox" id="showTimestamp" :checked="state.prefs.showTimestamp" @change="state.toggleShowTimestamp">
                        <span>Show timestamp</span>
                    </label>
                </div>
            </div>

            <div class="settings-group">
                <span class="label">Below image</span>

                <div class="field">
                    <label for="showRating" class="hover:cursor-pointer" :class="{'disabled':!authed}">
                        <input type="checkbox" id="showRating" :checked="state.prefs.showRating" @change="state.toggleShowRating" :disabled="!authed">
                        <span>Show rating</span>
                    </label>
                </div>

                <div class="field">
                    <label for="showTags" class="hover:cursor-pointer">
                        <input type="checkbox" id="showTags" :checked="state.prefs.showTagsBelow" @change="state.toggleShowTagsBelow">
                        <span>Show tags</span>
                    </label>
                </div>
                <div class="field">
                    <label for="showTagId" class="hover:cursor-pointer" :class="{'disabled':!authed}">
                        <input type="checkbox" id="showTagId" :checked="state.prefs.showTagId" @change="state.toggleShowTagId" :disabled="!authed">
                        <span>Show tag ID</span>
                    </label>
                </div>
                <div class="field">
                    <label for="showImagePath" class="hover:cursor-pointer" :class="{'disabled':!authed}">
                        <input type="checkbox" id="showImagePath" :checked="state.prefs.showPath" @change="state.toggleShowPath" :disabled="!authed">
                        <span>Show path</span>
                    </label>
                </div>
                <div class="field">
                    <label for="showImageId" class="hover:cursor-pointer" :class="{'disabled':!authed}">
                        <input type="checkbox" id="showImageId" :checked="state.prefs.showImageId" @change="state.toggleShowImageId" :disabled="!authed">
                        <span>Show image ID</span>
                    </label>
                </div>

                <div class="field">
                    <label for="showImageCameraInfo" class="hover:cursor-pointer" :class="{'disabled':!authed}">
                        <input type="checkbox" id="showImageCameraInfo" :checked="state.prefs.showCameraInfo" @change="state.toggleShowCameraInfo" :disabled="!authed">
                        <span>Show camera info</span>
                    </label>
                </div>

            </div>
        </div>

        <div class="panel-footer">
            <div></div>
            <button class="ikon ikon-wb ikon-btn" @click="emits('settings-panel-toggle')"><span>Close</span></button>
        </div>
    </div>
</template>
