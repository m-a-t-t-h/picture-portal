<script setup>
import SidePanel from "../components/SidePanel.vue";
import SettingsPanel from "../panels/SettingsPanel.vue";
import FilterPanel from "../panels/FilterPanel.vue";
import {ref} from "vue";
import MainMenuPanel from "../panels/MainMenuPanel.vue";

const filterPanelOpen   = ref(true);
const settingsPanelOpen = ref(false);
const isMainMenuOpen      = ref(false);

function toggleFilterPanel() {
    console.log("Toggle filter panel");
    filterPanelOpen.value   = !filterPanelOpen.value;
    settingsPanelOpen.value = false;
}

function toggleSettingsPanel() {
    console.log("Toggle settings panel");
    settingsPanelOpen.value = !settingsPanelOpen.value;
    filterPanelOpen.value   = false;
}


const openMainMenu  = () => isMainMenuOpen.value = true;
const closeMainMenu = () => isMainMenuOpen.value = false;

</script>

<template>
    <div class="layout">
        <header class="header-wrapper">
            <RouterView name="header" :isMainMenuOpen="isMainMenuOpen"
                        @open-main-menu="openMainMenu"/>
        </header>

        <main class="main-content-wrapper">
            <div class="main-content">
                <RouterView/>
            </div>
        </main>

        <footer class="footer-wrapper" id="footer">
            <RouterView name="footer"
                        @filter-panel-toggle="toggleFilterPanel"
                        @settings-panel-toggle="toggleSettingsPanel"/>
        </footer>
    </div>

    <SidePanel side="left" :open="filterPanelOpen">
        <template #default>
            <FilterPanel @filter-panel-toggle="toggleFilterPanel"></FilterPanel>
        </template>
    </SidePanel>

    <SidePanel side="right" :open="settingsPanelOpen">
        <template #default>
            <SettingsPanel @settings-panel-toggle="toggleSettingsPanel"></SettingsPanel>
        </template>
    </SidePanel>

    <SidePanel side="left" :open="isMainMenuOpen">
        <template #default>
            <MainMenuPanel @close-main-menu="closeMainMenu"></MainMenuPanel>
        </template>
    </SidePanel>
</template>
