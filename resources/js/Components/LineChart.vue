<template>
    <div class="p-5 flex flex-col h-fit bg-white overflow-hidden shadow sm:rounded-xl">
        <h2 class="pl-4 pt-3 mb-2 text-base font-semibold text-neutral-black-500">{{ title }}</h2>
        <VueApexCharts width="100%" height="auto" type="line" :options="options" :series="mergedSeries"></VueApexCharts>
    </div>
</template>

<script setup>
import { defineProps, computed } from 'vue';
import VueApexCharts from 'vue3-apexcharts';

const props = defineProps({
    title: String,
    options: Object,
    series: Array,
    forecastSeries: Array,
});

const trimData = (data) => {
    // Trim trailing null values
    for (let i = data.length - 1; i >= 0; i--) {
        if (data[i] === null) {
            data.pop();
        } else {
            break;
        }
    }
    // Ensure maximum of 12 values
    return data.slice(0, 12);
};

const mergedSeries = computed(() => {
    const merged = [];

    // Combine original series with forecast data
    props.series.forEach(originalSeries => {
        const targetSeries = {
            name: originalSeries.name,
            data: originalSeries.data.map(value => (value === 0 ? null : value))
        };

        // Check if there is forecast data for this series
        const forecastData = props.forecastSeries?.find(forecast => forecast.name === originalSeries.name);
        if (forecastData) {
            forecastData.data.forEach((value, index) => {
                if (targetSeries.data.length > index && targetSeries.data[index] === null) {
                    targetSeries.data[index] = value === 0 ? null : value;
                } else if (targetSeries.data.length <= index) {
                    targetSeries.data.push(value === 0 ? null : value);
                }
            });
        }

        merged.push({
            ...targetSeries,
            data: trimData(targetSeries.data)
        });
    });

    // Add any forecast series that do not have a corresponding original series
    props.forecastSeries?.forEach(forecast => {
        if (!merged.find(series => series.name === forecast.name)) {
            merged.push({
                name: forecast.name,
                data: trimData(forecast.data.map(value => (value === 0 ? null : value)))
            });
        }
    });

    console.log("Merged Series Data:", merged);

    return merged;
});
</script>
