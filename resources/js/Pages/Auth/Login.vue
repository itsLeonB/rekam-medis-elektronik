<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import MainButton from '@/Components/MainButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const showPassword = ref(false);
const togglePassword = ref('password');

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
    togglePassword.value = togglePassword.value === 'password' ? 'text' : 'password';
};
</script>

<template>
    <GuestLayout>
        <template #apphead>
            <title>Login - </title>
        </template>

        <div class="flex flex-col justify-center items-center lg:flex-row lg:border lg:rounded-3xl lg:shadow-lg bg-original-white-0 p-6">

            <img src="storage/images/welcome-doctor.png" class="w-full h-full block max-w-lg my-4" alt="">

            <div class="w-full max-w-lg lg:w-96 flex flex-col justify-center px-10 py-5">

                <h1 class="font-bold text-2xl text-center mb-5 text-neutral-black-300">Hai, Jumpa Lagi!</h1>

                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="email" value="Email" />

                        <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                            autocomplete="username" placeholder="Masukkan Email" />

                        <InputError class="mt-1" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="Password" />

                        <TextInput id="password" :type="togglePassword" class="mt-1 block w-full" v-model="form.password"
                            required autocomplete="current-password" placeholder="Masukkan Password" :rightIcon=true>
                            <div class="text-neutral-grey-100 hover:text-original-teal-300 active:text-original-teal-400 cursor-pointer"
                                @click="togglePasswordVisibility">
                                <svg v-show="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg v-show="showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>

                            </div>

                        </TextInput>

                        <InputError class="mt-1" :message="form.errors.password" />
                    </div>

                    <div class="flex flex-col items-center justify-end mt-10">
                        <MainButton class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Login
                        </MainButton>

                        <p class="text-center text-sm font-light text-neutral-black-300">Lupa password?
                            <Link v-if="canResetPassword" :href="route('password.request')"
                                class="inline-block font-semibold text-original-teal-300 hover:text-original-teal-400 focus:text-original-teal-400 active:text-original-teal-500">
                            Klik disini
                            </Link>
                        </p>
                    </div>
                </form>
            </div>
        </div>

    </GuestLayout>
</template>
