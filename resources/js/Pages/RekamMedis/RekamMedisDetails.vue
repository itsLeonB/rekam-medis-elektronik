<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Encounter - </title>
        </template>
        <div class="xl:hidden ml-10 mt-10 w-28 mb-2">
            <BackButton />
        </div>
        <div class="h-screen flex flex-row py-4 xl:pt-7">
            <aside class="hidden overflow-auto xl:h-[90%] xl:pb-10 xl:fixed xl:flex xl:flex-col xl:w-72">
                <div class="px-8 w-28 mb-2">
                    <BackButton />
                </div>
                <!-- Navigation Links -->
                <ul class="space-y-2">
                    <li>
                        <NavButton @click="formSection = 1" :active="formSection === 1">
                            <span class="pt-1">Identitas Pasien</span>
                        </NavButton>
                    </li>
                    <li>
                        <NavButton @click="formSection = 2" :active="formSection === 2">
                            <span class="pt-1">Riwayat Kunjungan</span>
                        </NavButton>
                    </li>
                    <!-- <li>
                        <NavButton @click="formSection = 5" :active="formSection === 5">
                            <span class="pt-1">Print Resep</span>
                        </NavButton>
                    </li> -->
                    <!-- <li>
                        <NavButton @click="formSection = 3" :active="formSection === 3">
                            <span class="pt-1">Riwayat Alergi</span>
                        </NavButton>
                    </li>
                    <li>
                        <NavButton @click="formSection = 4" :active="formSection === 4">
                            <span class="pt-1">Riwayat Pengobatan</span>
                        </NavButton>
                    </li> -->
                </ul>
            </aside>
            <div v-show="formSection === 1" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 py-10 w-full">
                <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <div class="flex justify-between">
                        <h1 class="text-2xl font-bold text-secondhand-orange-300">Identitas Pasien</h1>
                        <div class="flex space-x-5 items-center">
                            <p v-if="successAlertVisible" class="text-sm text-original-teal-300">Data berhasil diperbarui!
                            </p>
                            <p v-if="failAlertVisible" class="text-sm text-thirdouter-red-300">Data gagal diperbarui!</p>
                            <MainButton :isLoading="isLoading" @click="updateRekamMedis"
                                class="purple-button text-original-white-0">Update Rekam
                                Medis
                            </MainButton>
                        </div>
                    </div>
                    <div class="flex flex-col lg:flex-row">
                        <div class="w-full py-2">
                            <IdentitasPasien :patient="patient" />
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="formSection === 2" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 py-10 w-full">
                <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-secondhand-orange-300">Riwayat Kunjungan</h1>
                    <div class="flex flex-col lg:flex-row">
                        <div class="w-full py-2 lg:pr-7 lg:pb-0">
                            <Encounters :encounters="encounters" />
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="formSection === 3" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-secondhand-orange-300">Riwayat Alergi</h1>
                    <div class="flex flex-col lg:flex-row">
                        <!-- Kiri -->
                        <div class="w-full py-2 lg:pr-7 lg:pb-0">
                            <div class="space-y-4">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="formSection === 4" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-secondhand-orange-300">Riwayat Pengobatan</h1>
                    <div class="flex flex-col lg:flex-row">
                        <!-- Kiri -->
                        <div class="w-full py-2 lg:pr-7 lg:pb-0">
                            <div class="space-y-4">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div v-show="formSection === 5" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 py-10 w-full">
                <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <div class="flex flex-col lg:flex-row">
                        <div class="w-full py-2 lg:pr-7 lg:pb-0">
                            <PrintData :patient="patient" :encounters="encounters" />
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
        <template #responsivecontent>
            <ResponsiveNavButton @click="formSection = 1" :active="formSection === 1"> Identitas Pasien
            </ResponsiveNavButton>
            <ResponsiveNavButton @click="formSection = 2" :active="formSection === 2"> Riwayat Kunjungan
            </ResponsiveNavButton>
            <!-- <ResponsiveNavButton @click="formSection = 3" :active="formSection === 3"> Riwayat Alergi
            </ResponsiveNavButton>
            <ResponsiveNavButton @click="formSection = 4" :active="formSection === 4"> Riwayat Pengobatan
            </ResponsiveNavButton> -->
            <!-- <ResponsiveNavButton @click="formSection = 5" :active="formSection === 5"> Print Resep
            </ResponsiveNavButton> -->
        </template>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import IdentitasPasien from './Partials/IdentitasPasien.vue';
import Encounters from './Partials/Encounters.vue';
import PrintData from './Partials/PrintDataResep.vue';
import BackButton from '@/Components/BackButton.vue';
import NavButton from '@/Components/NavButton.vue';
import MainButton from '@/Components/MainButton.vue';
import ResponsiveNavButton from '@/Components/ResponsiveNavButton.vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';

const props = defineProps({
    patient_satusehat_id: {
        type: String,
    },
});

const formSection = ref(1);

const patient = ref({});
const encounters = ref({});
const successAlertVisible = ref(false);
const failAlertVisible = ref(false);
const isLoading = ref(false);

const updateRekamMedis = async () => {
    isLoading.value = true
    axios.get(route('rekam-medis.update', { 'patient_id': props.patient_satusehat_id })).then(response => {
        isLoading.value = false;
        successAlertVisible.value = true;
        setTimeout(() => {
            successAlertVisible.value = false;
        }, 3000);
    })
        .catch(error => {
            isLoading.value = false;
            console.error('Error creating user:', error);
            failAlertVisible.value = true;
            setTimeout(() => {
                failAlertVisible.value = false;
            }, 3000);
        });
    fetchRekamMedis();
};

const fetchRekamMedis = async () => {
    const { data } = await axios.get(route('rekam-medis.show', { 'patient_id': props.patient_satusehat_id }));
    patient.value = data.patient;
    encounters.value = data.encounters;
};


onMounted(() => {
    fetchRekamMedis();
});

</script>
