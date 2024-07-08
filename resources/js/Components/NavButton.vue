<script setup>
import { computed } from 'vue';

const props = defineProps({
    href: {
        type: String,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'w-full inline-flex items-start px-7 text-left h-11 py-2 border-l-4 border-original-teal-300 text-base font-semibold leading-5 text-original-teal-300 rounded-r-full bg-[#EEF6F7] focus:outline-none focus:text-original-teal-400 focus:border-original-teal-400 transition duration-500 ease-in-out'
        : 'w-full inline-flex items-start px-7 text-left h-11 py-2 border-l-4 border-transparent text-base font-semibold leading-5 text-neutral-grey-200 rounded-r-full hover:bg-[#EEF6F7] hover:text-original-teal-300 hover:border-original-teal-300 focus:outline-none focus:text-original-teal-400 focus:border-original-teal-400 transition duration-500 ease-in-out'
);
</script>

<template>
    <button :href="href" :class="classes">
        <slot />
    </button>
</template>
