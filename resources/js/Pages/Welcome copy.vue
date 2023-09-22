<script setup>
import { Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const isLoggedIn = $page.props.auth.user;

if isLoggedIn {

}

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
        <!-- <div v-if="canLogin" class="0 p-6 text-right">
            <Link v-if="$page.props.auth.user" :href="route('dashboard.index')"
                class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
            Dashboard</Link>

            <template v-else>
                <Link :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Log in</Link>

                <Link v-if="canRegister" :href="route('register')"
                    class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Register</Link>
            </template>
        </div> -->

        <div v-if="canLogin"
            class="flex md:flex-row sm:flex-col justify-center max-w-5xl bg-white shadow-[0_0_25px_-5px_rgba(0,0,0,0.15)] lg:rounded-3xl p-12 overflow-hidden">
            <div>
                <img src="storage/images/welcome-doctor.png" class="max-w-lg" alt="">
            </div>

            <div class="flex flex-col px-10 justify-center">
                <h1 class="font-bold text-3xl text-center mb-8 text-zinc-700">Hai, Selamat Datang!</h1>
                <Link :href="route('login')" as="button"
                    class="mb-3 inline-flex justify-center px-4 py-2 border border-transparent rounded-lg font-bold text-base text-white tracking-widest bg-vegan-300 hover:bg-vegan-400 focus:bg-vegan-400 focus:outline-none focus:ring-2 focus:ring-vegan-400 focus:ring-offset-2 active:bg-vegan-500 transition ease-in-out duration-150">
                Login
                </Link>
                <div class="flex justify-center text-sm">
                    <p class="text-zinc-500">
                        Belum punya akun?
                        <Link v-if="canRegister" :href="route('register')"
                            class="font-bold text-vegan-300 hover:text-vegan-400 focus:text-vegan-400 active:text-vegan-500">
                        Klik disini</Link>
                    </p>
                </div>

            </div>
        </div>
    </GuestLayout>
</template>
