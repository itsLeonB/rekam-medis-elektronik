<script setup>
import MainButton from '@/Components/MainButton.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Link } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>

        <template #apphead>
            <title>Lupa Password - </title>
        </template>

        <div
            class="flex flex-col justify-center items-center lg:flex-row lg:border lg:rounded-3xl lg:shadow-lg bg-original-white-0 p-6">

            <div v-if="status" class="absolute mb-1">
                <div class="flex justify-center items-center mb-6 text-original-teal-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="125" height="125" viewBox="0 0 185 185" fill="none">
                        <path
                            d="M72.3382 76.1681L94.0795 95.386C96.872 97.8544 101.095 97.7536 103.766 95.1548L153.32 46.9502"
                            stroke="currentColor" stroke-width="14.25" stroke-linecap="round" />
                        <path
                            d="M156.418 97.2362C155.343 110.591 150.111 123.274 141.457 133.504C132.804 143.733 121.164 150.994 108.171 154.269C95.179 157.543 81.4875 156.666 69.0195 151.759C56.5516 146.853 45.9336 138.165 38.6569 126.915C31.3801 115.664 27.81 102.417 28.4482 89.0341C29.0863 75.6508 33.9005 62.8035 42.2146 52.2967C50.5288 41.7898 61.9252 34.1511 74.8034 30.4534C87.6816 26.7557 101.394 27.1848 114.016 31.6804"
                            stroke="currentColor" stroke-width="14.25" stroke-linecap="round" />
                    </svg>
                </div>
                <p class="w-[75%] mx-auto mb-5 text-center font-medium text-base text-neutral-black-300">{{ status }}</p>
                <Link :href="route('login')" as="button"
                    class="mx-auto mb-3 w-full max-w-[284px] block justify-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                Kembali ke Login
                </Link>
            </div>

            <img src="storage/images/welcome-doctor.png" class="w-full h-full block max-w-lg my-4"
                :class="{ 'invisible': status }" alt="">

            <div class="w-full max-w-lg lg:w-96 flex flex-col justify-center items-center px-10 pt-2 pb-5"
                :class="{ 'invisible': status }">

                <h1 class="font-bold text-2xl text-center mb-5 text-neutral-black-300">Lupa Password?</h1>
                <div class="text-sm mb-7">
                    <p class="text-center">
                        Silahkan masukkan email akun Anda, dan kami akan mengirimkan tautan untuk membantu Anda kembali ke
                        akun Anda.
                    </p>
                </div>

                <form v-if="!status" class="w-full flex flex-col justify-center" @submit.prevent="submit">
                    <div class="mb-5">
                        <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                            autocomplete="username" placeholder="Masukkan email" />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <MainButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        class="teal-button text-original-white-0">
                        Reset Password
                    </MainButton>
                </form>
            </div>
        </div>

    </GuestLayout>
</template>