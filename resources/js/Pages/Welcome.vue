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
        <div class="flex flex-col lg:flex-row lg:border lg:rounded-3xl lg:shadow-lg bg-white px-6 ">

            <img src="storage/images/welcome-doctor.png" class="w-full block pt-5 max-w-lg" alt="">

            <div class="w-full max-w-lg lg:w-96 flex flex-col justify-center px-10">
                <h1 class="font-bold text-2xl text-center mb-5 text-zinc-700">Hai, Selamat Datang!</h1>
                <Link :href="route('login')" as="button"
                    class="mx-auto mb-3 w-full max-w-[284px] block justify-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm text-white tracking-widest bg-vegan-300 hover:bg-vegan-400 focus:bg-vegan-400 focus:outline-none focus:ring-2 focus:ring-vegan-400 focus:ring-offset-2 active:bg-vegan-500 transition ease-in-out duration-150">
                Login
                </Link>
                <p class="text-center text-sm font-light text-zinc-700">
                    Belum punya akun?
                    <Link class="inline-block font-semibold text-vegan-300">
                    Klik disini
                    </Link>
                </p>
            </div>
        </div>
    </GuestLayout>
</template>
