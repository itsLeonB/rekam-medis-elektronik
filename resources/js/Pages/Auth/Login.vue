<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

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
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>

        <Head title="Login - " />

        <div
            class="flex md:flex-row sm:flex-col justify-center max-w-5xl bg-white shadow-[0_0_25px_-5px_rgba(0,0,0,0.15)] lg:rounded-3xl p-12">

            <div>
                <img src="storage/images/welcome-doctor.png" class="max-w-lg" alt="">
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <div class="flex flex-col px-10 justify-center">

                <h1 class="font-bold text-3xl text-center mb-8 text-zinc-700">Hai, Jumpa Lagi!</h1>

                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="email" value="Email" />

                        <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                            autocomplete="username" placeholder="Masukkan Email"/>

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="Password" />

                        <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                            autocomplete="current-password" placeholder="Masukkan Password" />

                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <!-- <div class="block mt-4">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                    </div> -->

                    <div class="flex flex-col items-center justify-end mt-4">
                        <PrimaryButton class="w-full mb-4" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Login
                        </PrimaryButton>

                        <div class="flex justify-center text-sm">
                            <p>Lupa password?
                                <Link v-if="canResetPassword" :href="route('password.request')"
                                    class="font-bold text-teal-500 hover:text-teal-600 focus:text-teal-600 active:text-teal-700">
                                Klik disini
                                </Link>
                            </p>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
