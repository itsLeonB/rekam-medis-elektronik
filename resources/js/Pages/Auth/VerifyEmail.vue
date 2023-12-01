<script setup>
import AppHead from '@/Components/AppHead.vue';
import MainButton from '@/Components/MainButton.vue'
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <GuestLayout>

        <AppHead>
            <title>Verifikasi email - </title>
        </AppHead>

        <div
            class="flex flex-col justify-center items-center lg:flex-row lg:border lg:rounded-3xl lg:shadow-lg bg-original-white-0 p-6">

            <img src="storage/images/welcome-doctor.png" class="w-full h-full block max-w-lg my-4" alt="">

            <div class="w-full max-w-lg lg:w-96 flex flex-col justify-center px-10 py-5">

                <h1 class="font-bold text-2xl text-center mb-5 text-neutral-black-300">Verifikasi Email</h1>
                <div class="text-sm mb-7">
                    <p class="text-center">
                        Sebelum memulai, harap melakukan verifikasi email Anda dengan
                        menekan tautan yang kami kirimkan pada email Anda.
                    </p>
                </div>

                <div v-if="verificationLinkSent" class="mb-1 text-center font-medium text-sm text-original-teal-300">
                    <p class="mb-5">Link verifikasi telah kami kirimkan pada email Anda. Pastikan untuk memeriksa folder
                        spam
                        jika tidak menemukan email verifikasi.</p>
                </div>

                <form v-if="!verificationLinkSent" @submit.prevent="submit">
                    <div class="flex flex-col items-center justify-between">
                        <MainButton class="teal-button text-original-white-0 mb-6"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Kirim email verifikasi
                        </MainButton>

                        <Link :href="route('logout')" method="post"
                            class="underline text-sm text-neutral-black-300 hover:text-original-teal-300">
                        Log Out
                        </Link>
                    </div>
                </form>
            </div>
        </div>

    </GuestLayout>
</template>