<script setup>
import AppHead from '@/Components/AppHead.vue'
</script>

<template>
    <AppHead>
        <slot name="apphead" />
    </AppHead>
    <div class="min-h-screen min-w-full flex
                justify-center items-center
                bg-white">
        <slot />
    </div>
</template>
