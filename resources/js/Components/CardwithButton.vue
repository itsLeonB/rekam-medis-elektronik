<script setup>
import MainButtonSmall from '@/Components/MainButtonSmall.vue';

defineProps({
    rekamMedis: Object
});

</script>

<template>
    <div class="shadow bg-white rounded-2xl px-4 pt-3 pb-1 w-64">
        <div class="h-32 mb-2">
            <slot name="cardimage" />
        </div>
        <MainButtonSmall class="w-full mb-3 mx-auto max-w-full block teal-button text-original-white-0">
            <slot name="cardbutton" />
        </MainButtonSmall>
    </div>
</template>