<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutNav.vue';

defineProps({
    rekamMedis: Object
});
</script>

<template>
    <AuthenticatedLayout>
        <div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-row justify-between bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="pl-8 my-auto">
                        <h2 class="text-3xl font-bold text-secondhand-orange-300 mb-1">Hai, {{ $page.props.auth.user.name
                        }}!</h2>
                        <p class="text-neutral-grey-200 font-normal text-2xl">Selamat datang di halaman utama</p>
                    </div>
                    <div class="mr-8">
                        <img src="storage/images/welcome-onboard.png" class="h-36 mx-auto" alt="">
                    </div>
                </div>
                <div class="grid grid-cols-3 space-x-8 h-28">
                    <div class="flex flex-row justify-between bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="pl-8 my-auto ">
                            <h2 class="text-xl font-normal text-secondhand-orange-300 mb-1">analytics</h2>
                        </div>
                    </div>
                    <div class="flex flex-row justify-between bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="pl-8 my-auto">
                            <h2 class="text-xl font-normal text-secondhand-orange-300 mb-1">analytics</h2>
                        </div>
                    </div>
                    <div class="flex flex-row justify-between bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="pl-8 my-auto">
                            <h2 class="text-xl font-normal text-secondhand-orange-300 mb-1">analytics</h2>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout></template>
