<template>
    <div class="p-5 flex flex-col h-fit bg-white overflow-hidden shadow sm:rounded-xl">
        <h2 class="pl-4 pt-3 mb-2 text-base font-semibold text-neutral-black-500">Jumlah Pasien Per Bulan</h2>
        <VueApexCharts width="100%" height="320px" type="line" :options="options" :series="series"></VueApexCharts>
    </div>
</template>

<script setup>
    import { defineProps } from 'vue';
    import VueApexCharts from 'vue3-apexcharts';

    const props = defineProps({
    options: Object,
    series: Array,
    });
</script>
