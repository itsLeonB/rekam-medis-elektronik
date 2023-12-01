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
        ? 'w-full inline-flex items-center px-7 h-11 py-2 border-l-4 border-original-teal-300 text-lg font-semibold leading-5 text-original-teal-300 rounded-r-full bg-original-lightteal focus:outline-none focus:text-original-teal-400 focus:border-original-teal-400 transition duration-150 ease-in-out'
        : 'w-full inline-flex items-center px-7 h-11 py-2 border-l-4 border-transparent text-lg font-semibold leading-5 text-neutral-grey-200 rounded-r-full hover:bg-original-lightteal hover:text-original-teal-300 hover:border-original-teal-300 focus:outline-none focus:text-original-teal-400 focus:border-original-teal-400 transition duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
