<script setup>
import { Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <GuestLayout>
        <template #apphead>
            <title>Selamat datang! - </title>
        </template>
        <div
            class="flex flex-col justify-center items-center lg:flex-row lg:border lg:rounded-3xl lg:shadow-lg bg-original-white-0 p-6">

            <img src="storage/images/welcome-doctor.png" class="w-full h-full block max-w-lg my-4" alt="">

            <div class="w-full max-w-lg lg:w-96 flex flex-col justify-center px-10 py-5">
                <h1 class="font-bold text-2xl text-center mb-5 text-neutral-black-300">Hai, Selamat Datang!</h1>
                <Link :href="route('login')" as="button"
                    class="mx-auto mb-3 w-full max-w-[284px] block justify-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                Login
                </Link>
            </div>
        </div>
    </GuestLayout>
</template>
