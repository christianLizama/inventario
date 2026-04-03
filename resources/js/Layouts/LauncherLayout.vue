<script setup>
import AppDock from '@/Components/AppDock.vue';
</script>

<template>
    <div class="min-h-screen bg-black text-white pb-28">
        <slot />
        <AppDock />
    </div>
</template>
