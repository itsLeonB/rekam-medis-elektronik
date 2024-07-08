<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'block w-full pl-3 pr-4 py-2 border-l-4 border-original-teal-300 text-left text-base font-medium text-original-teal-300 bg-[#EEF6F7] focus:outline-none focus:text-original-teal-400 focus:bg-[#EEF6F7] focus:border-original-teal-400 transition duration-150 ease-in-out'
        : 'block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:bg-[#EEF6F7] hover:text-original-teal-300 hover:border-original-teal-300 focus:outline-none focus:text-original-teal-400 focus:border-original-teal-400 hover:bg-neutral-teal-300 transition duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
