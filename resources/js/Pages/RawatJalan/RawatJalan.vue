<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutNav.vue';
import CardwithButton from '@/Components/CardwithButton.vue';

defineProps({
    rekamMedis: Object
});
</script>

<template>
    <AuthenticatedLayout>
        <!-- Search bar
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 mb-5">
            <form>
                <div class="relative p-0 rounded-full border-none text-neutral-black-300">
                    <div class="absolute inset-y-0 left-0 mx-3 w-5 h-5 my-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="#8f8f8f" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input id="search-poli" placeholder="Cari poli"
                        class="pl-9 h-9 block w-full border border-none outline-none focus:border-original-teal-300 focus:ring-original-teal-300 rounded-full shadow" />
                </div>
            </form>
        </div> -->

        <!-- Main content -->
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="sm:rounded-lg">
                <div class="grid grid-cols-4 gap-8 w-full mb-10">
                    <!-- <h2 class="text-lg font-medium text-gray-900">Halaman Rawat Jalan</h2> -->
                    <CardwithButton>
                        <template #cardimage>
                            <img src="storage/images/poli-umum.png" class="h-full mx-auto" alt="">
                        </template>

                        <template #cardbutton>
                            Poli Umum
                        </template>
                    </CardwithButton>
                    <CardwithButton>
                        <template #cardimage>
                            <img src="storage/images/poli-neurologi.png" class="h-full mx-auto" alt="">
                        </template>

                        <template #cardbutton>
                            Poli Neurologi
                        </template>
                    </CardwithButton>
                    <CardwithButton>
                        <template #cardimage>
                            <img src="storage/images/poli-obgyn.png" class="h-full mx-auto" alt="">
                        </template>

                        <template #cardbutton>
                            Poli Obgyn
                        </template>
                    </CardwithButton>
                    <CardwithButton>
                        <template #cardimage>
                            <img src="storage/images/poli-gigi.png" class="h-full mx-auto" alt="">
                        </template>

                        <template #cardbutton>
                            Poli Gigi
                        </template>
                    </CardwithButton>
                    <CardwithButton>
                        <template #cardimage>
                            <img src="storage/images/poli-kulit-kelamin.png" class="h-full mx-auto" alt="">
                        </template>

                        <template #cardbutton>
                            Poli Kulit dan Kelamin
                        </template>
                    </CardwithButton>
                    <CardwithButton>
                        <template #cardimage>
                            <img src="storage/images/poli-ortopedi.png" class="h-full mx-auto" alt="">
                        </template>

                        <template #cardbutton>
                            Poli Ortopedi
                        </template>
                    </CardwithButton>
                    <CardwithButton>
                        <template #cardimage>
                            <img src="storage/images/poli-penyakit-dalam.png" class="h-full mx-auto" alt="">
                        </template>

                        <template #cardbutton>
                            Poli Penyakit Dalam
                        </template>
                    </CardwithButton>
                    <CardwithButton>
                        <template #cardimage>
                            <img src="storage/images/poli-bedah.png" class="h-full mx-auto" alt="">
                        </template>

                        <template #cardbutton>
                            Poli Bedah
                        </template>
                    </CardwithButton>
                    <CardwithButton>
                        <template #cardimage>
                            <img src="storage/images/poli-anak.png" class="h-full mx-auto" alt="">
                        </template>

                        <template #cardbutton>
                            Poli Anak
                        </template>
                    </CardwithButton>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
