<script setup>
defineProps({
    side: {
        type: String,
        required: true,
        validator: value => ['left', 'right'].includes(value),
    },
    open: Boolean,
})
</script>

<template>
    <aside class="side-panel" :class="[`side-panel--${side}`, { 'side-panel--open': open }]" :aria-hidden="!open">
        <div class="side-panel__content">
            <slot />
        </div>
    </aside>
</template>

<style scoped>

.side-panel {
    position: fixed;
    top: 0px;
    bottom: 0px;
    z-index: 9999;
    width: min(100vw, 500px);
    transition: transform 0.5s ease;
    background: white;
}

.side-panel--left {
    left: 0;
    transform: translateX(-200%);
}

.side-panel--right {
    right: 0;
    transform: translateX(200%);
}

.side-panel--open {
    transform: translateX(0);
}

.side-panel__content {
    height: 100%;
    padding: 0;
    overflow-y: auto;
    box-shadow: 0 0 30px #000;
}
</style>
