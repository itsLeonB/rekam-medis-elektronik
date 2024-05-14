<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Encounter - </title>
        </template>
        <div class="xl:hidden ml-10 mt-10 w-28 mb-2">
            <BackButton />
        </div>
        <div class="h-screen flex flex-row pt-4 xl:pt-7">
            <aside class="hidden overflow-auto xl:h-[90%] xl:pb-10 xl:fixed xl:flex xl:flex-col xl:w-72">
                <div class="px-8 w-28 mb-2">
                    <BackButton />
                </div>
                <!-- Navigation Links -->
                <ul class="space-y-2">
                    <li>
                        <NavButton @click="formSection = 1" :active="formSection === 1">
                            <span class="pt-1 mr-1">1.</span>
                            <span class="pt-1">Identitas Pasien</span>
                        </NavButton>
                    </li>
                </ul>
            </aside>
            <div v-show="formSection === 1" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-secondhand-orange-300">Identitas Pasien</h1>
                    <div class="flex flex-col lg:flex-row">
                        <!-- Kiri -->
                        <div class="w-full lg:w-2/3 py-2 lg:pr-7 lg:pb-0">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300">a. Identitas Pasien</h2>
                            <div class="space-y-4">
                                <IdentitasPasien :encounter="encounter" />
                            </div>
                        </div>
                        <!-- Kanan -->
                        <div class="w-full lg:w-1/3 py-2 lg:pl-7">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300">b. Status Kunjungan</h2>
                            <div class="space-y-4">
                                <StatusKunjungan :encounter="encounter" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <template #responsivecontent>
            <ResponsiveNavButton @click="formSection = 1" :active="formSection === 1"> 1. Identitas Pasien
            </ResponsiveNavButton>
           
        </template>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import IdentitasPasien from '@/Pages/FormRekamMedis/IdentitasPasien/IdentitasPasien.vue';
import StatusKunjungan from '@/Pages/FormRekamMedis/IdentitasPasien/StatusKunjungan.vue';

import BackButton from '@/Components/BackButton.vue';
import NavButton from '@/Components/NavButton.vue';
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
import ResponsiveNavButton from '@/Components/ResponsiveNavButton.vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';

const props = defineProps({
    encounter_satusehat_id: {
        type: String,
    },
});

// Vital Signs
const nadi = ref(null);
const pernafasan = ref(null);
const sistol = ref(null);
const diastole = ref(null);
const suhu = ref(null);
const vitalSubmit = () => {
    nadi.value.submit();
    pernafasan.value.submit();
    sistol.value.submit();
    diastole.value.submit();
    suhu.value.submit();
};

const formSection = ref(1);

const encounter_reference = {
    "reference": `Encounter/${props.encounter_satusehat_id}`
};

const encounter = ref({});
const practitioner_ref = ref({});
const subject_ref = ref({});

const fetchEncounter = async () => {
    const { data } = await axios.get(route('resources.show', 
        {
            'resType': 'Encounter',
            'id': props.encounter_satusehat_id 
        }));
    encounter.value = data;
    practitioner_ref.value = {
        "reference": encounter.value.participant[encounter.value.participant.length - 1].individual.reference
    };
    subject_ref.value = encounter.value.subject
};


onMounted(() => {
    fetchEncounter();
});

</script>
