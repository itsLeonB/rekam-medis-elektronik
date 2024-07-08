<template>
    <Link href="#" @click="back()" as="button"
        class="mx-auto mb-3 inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3 " stroke="currentColor"
        class="w-4 h-4 mr-2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
    </svg>
    Back
    </Link>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';

function back() {
    window.history.back();
}
</script>