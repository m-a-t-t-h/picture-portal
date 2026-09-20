<script setup>

defineProps({
    photo: Object,
});
</script>

<template>
    <div class="media-header">
        <slot name="header-1" />
        <slot name="header-2" />
    </div>
</template>

<style scoped>
@reference "tailwindcss";

.media-header {
    @apply hidden;
    @apply bg-slate-50 flex flex-col;
    @apply p-3;
}
</style>
