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
        <div class="flex flex-col md:flex-row md:border md:rounded-3xl md:shadow-lg bg-white px-6 ">

            <div class="w-full max-w-lg">
                <img src="storage/images/welcome-doctor.png" alt="">
            </div>

            <div class="flex flex-col w-full max-w-lg flex flex-row justify-center px-5">
                <h1 class="font-bold text-2xl text-center mb-5">Hai, Selamat Datang!</h1>
                <Link :href="route('login')" as="button"
                    class="mb-3 block justify-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm text-white tracking-widest bg-vegan-300 hover:bg-vegan-400 focus:bg-vegan-400 focus:outline-none focus:ring-2 focus:ring-vegan-400 focus:ring-offset-2 active:bg-vegan-500 transition ease-in-out duration-150">
                Login
                </Link>
                <div>
                    <p class="text-center text-sm font-light text-zinc-700">
                        Belum punya akun?
                        <span class="font-semibold text-vegan-300">
                            Klik disini
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
