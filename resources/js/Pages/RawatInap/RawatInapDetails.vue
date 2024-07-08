<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Encounter - </title>
        </template>

        <div class="flex flex-row pt-7">
            <aside class="hidden xl:h-full xl:fixed xl:flex xl:flex-col xl:shrink-0 xl:w-72">
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
                    <li>
                        <NavButton @click="formSection = 2" :active="formSection === 2">
                            <span class="pt-1 mr-1">2.</span>
                            <span class="pt-1">Formulir Rawat Inap</span>
                        </NavButton>
                    </li>
                    <li>
                        <NavButton @click="formSection = 3" :active="formSection === 3">
                            <span class="pt-1 mr-1">3.</span>
                            <span class="pt-1">Diagnosis</span>
                        </NavButton>
                    </li>
                    <li>
                        <NavButton @click="formSection = 4" :active="formSection === 4">
                            <span class="pt-1 mr-1">4.</span>
                            <span class="pt-1">Tindakan</span>
                        </NavButton>
                    </li>
                    <li>
                        <NavButton @click="formSection = 5" :active="formSection === 5">
                            <span class="pt-1 mr-1">5.</span>
                            <span class="pt-1">Tatalaksana</span>
                        </NavButton>
                    </li>
                    <li>
                        <NavButton @click="formSection = 6" :active="formSection === 6">
                            <span class="pt-1 mr-1">6.</span>
                            <span class="pt-1">Prognosis</span>
                        </NavButton>
                    </li>
                    <li>
                        <NavButton @click="formSection = 7" :active="formSection === 7">
                            <span class="pt-1 mr-1">7.</span>
                            <span class="pt-1">Rencana Tindak Lanjut</span>
                        </NavButton>
                    </li>
                    <li>
                        <NavButton @click="formSection = 8" :active="formSection === 8">
                            <span class="pt-1 mr-1">8.</span>
                            <span class="pt-1">Cara Keluar dari Rumah Sakit</span>
                        </NavButton>
                    </li>
                </ul>
            </aside>
            <div v-show="formSection === 1" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-neutral-black-300">Masuk Encounter say page 1</h1>
                    {{ encounter_satusehat_id }}
                </div>
            </div>
            <div v-show="formSection === 2" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-neutral-black-300">Masuk Encounter say page 2</h1>
                    {{ encounter_satusehat_id }}
                </div>
            </div>
            <div v-show="formSection === 3" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-neutral-black-300">Masuk Encounter say page 3</h1>
                    {{ encounter_satusehat_id }}
                </div>
            </div>
            <div v-show="formSection === 4" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-neutral-black-300">Masuk Encounter say page 4</h1>
                    {{ encounter_satusehat_id }}
                </div>
            </div>
            <div v-show="formSection === 5" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-neutral-black-300">Masuk Encounter say page 5</h1>
                    {{ encounter_satusehat_id }}
                </div>
            </div>
            <div v-show="formSection === 6" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-neutral-black-300">Masuk Encounter say page 6</h1>
                    {{ encounter_satusehat_id }}
                </div>
            </div>
            <div v-show="formSection === 7" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-neutral-black-300">Masuk Encounter say page 7</h1>
                    {{ encounter_satusehat_id }}
                </div>
            </div>
            <div v-show="formSection === 8" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-neutral-black-300">Masuk Encounter say page 8</h1>
                    {{ encounter_satusehat_id }}
                </div>
            </div>
        </div>
        <template #responsivecontent>
            <ResponsiveNavLink :href="route('home.index')" :active="route().current('home.index')"> Home
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('rawatjalan')" :active="route().current('rawatjalan')"> Rawat Jalan
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('rawatinap')" :active="route().current('rawatinap')"> Rawat Inap
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('gawatdarurat')" :active="route().current('gawatdarurat')"> Gawat Darurat
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('rekammedis')" :active="route().current('rekammedis')"> Rekam Medis
            </ResponsiveNavLink>
            <ResponsiveNavLink :href="route('usermanagement')" :active="route().current('usermanagement')"> User Management
            </ResponsiveNavLink>
        </template>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BackButton from '@/Components/BackButton.vue';
import NavButton from '@/Components/NavButton.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import axios from 'axios';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    encounter_satusehat_id: {
        type: String,
    },
});

// const fetchUsers = async (page = 1) => {
//     const { data } = await axios.get(route('users.index', {'page': encounter_satusehat_id}));
//     users.value = data.users;
// };


// encounter
const formSection = ref(1)
</script>
