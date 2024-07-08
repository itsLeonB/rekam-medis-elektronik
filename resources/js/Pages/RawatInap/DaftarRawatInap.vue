<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Daftar Rawat Inap - </title>
        </template>
        <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <h1 class="text-2xl font-bold text-neutral-black-300">Daftar Rawat Inap</h1>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman pendaftaran rawat inap.</p>
            <form>

                <!-- <h2 class="font-semibold text-secondhand-orange-300 mt-4">Status Kunjungan</h2> -->
                <!-- Status -->
                <div class="mt-4">
                    <InputLabel for="status" value="Status" />
                    <select id="status"
                        class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                        <option value="planned">Planned</option>
                        <option value="arrived">Arrived</option>
                        <option value="triaged">Triaged</option>
                        <option value="in-progress">In Progress</option>
                        <option value="onleave">Onleave</option>
                        <option value="finished">Finished</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <InputError class="mt-1" />
                </div>

                <!-- <div>
                    <VueMultiselect v-model="testyinput" :options="searchResults" :searchable=true @search-change="fetchicd10" placeholder="Type to search" label="display_id"
                        track-by="code">
                        <template #noResult>
                            Oops! No elements found. Consider changing the search query.
                        </template>
                    </VueMultiselect>
                </div> -->

                <div class="mt-4">
                    <InputLabel for="testy" value="Status" />
                    <TextInput id="testy" type="text" class="mt-1 float w-full" required placeholder="Pasien"
                        v-model="testyinput" @change="fetchicd10" />
                    <select id="testy"
                        class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                        <option v-for="item in searchResults" :value=item.code>{{ item.display_id }}</option>
                    </select>
                    <InputError class="mt-1" />
                </div>

                <!-- Identifier -->
                <div class="mt-4">
                    <InputLabel for="pasien" value="Identitas Pasien" />
                    <TextInput id="pasien" type="text" class="mt-1 block w-full" required placeholder="Pasien" />
                    <InputError class="mt-1" />
                </div>

                <!-- Participant/Nakes -->
                <div class="mt-4">
                    <InputLabel for="dokter" value="Identitas Dokter" />
                    <TextInput id="dokter" type="text" class="mt-1 block w-full" required placeholder="Dokter" />
                    <InputError class="mt-1" />
                </div>

                <!-- Ruangan -->
                <div class="mt-4">
                    <InputLabel for="ruangan" value="Ruangan" />
                    <TextInput id="ruangan" type="text" class="mt-1 block w-full" required placeholder="Ruangan" />
                    <InputError class="mt-1" />
                </div>
            </form>
            <div class="flex flex-col items-center justify-end mt-10">
                <MainButton class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0">
                    Daftar
                </MainButton>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import VueMultiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.css';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import MainButton from '@/Components/MainButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import axios from 'axios';
import { ref } from 'vue';

const testyinput = ref('');
const searchResults = ref([]);
const fetchicd10 = async () => {
    const { data } = await axios.get(route('terminologi.icd10', { 'search': testyinput.value }));
    searchResults.value = data;
    console.log(searchResults.value);
};


const resource = ref({
    "resourceType": "Encounter",
    "identifier": [
        {
            "system": "http://sys-ids.kemkes.go.id/encounter/10085103",
            "value": "10085103"
        }
    ],
    "status": "in-progress",
    "class": {
        "system": "http://terminology.hl7.org/CodeSystem/v3-ActCode",
        "code": "IMP",
        "display": "inpatient encounter"
    },
    "subject": {
        "reference": "Patient/100000030015",
        "display": "Diana Smith"
    },
    "participant": [
        {
            "type": [
                {
                    "coding": [
                        {
                            "system": "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                            "code": "ATND",
                            "display": "attender"
                        }
                    ]
                }
            ],
            "individual": {
                "reference": "Practitioner/N10000001",
                "display": "Dokter Bronsig"
            }
        }
    ],
    "period": {
        "start": "2022-12-22T08:00:00+00:00"
    },
    "location": [
        {
            "location": {
                "reference": "Location/fbd65f98-4864-48bd-a6f3-7b477a23e763",
                "display": "Bed 2, Ruang 210, Bangsal Rawat Inap Kelas 1, Layanan Penyakit Dalam, Lantai 2, Gedung Utama"
            },
            "extension": [
                {
                    "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/ServiceClass",
                    "extension": [
                        {
                            "url": "value",
                            "valueCodeableConcept": {
                                "coding": [
                                    {
                                        "system": "http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient",
                                        "code": "1",
                                        "display": "Kelas 1"
                                    }
                                ]
                            }
                        },
                        {
                            "url": "upgradeClassIndicator",
                            "valueCodeableConcept": {
                                "coding": [
                                    {
                                        "system": "http://terminology.kemkes.go.id/CodeSystem/locationUpgradeClass",
                                        "code": "kelas-tetap",
                                        "display": "Kelas Tetap Perawatan"
                                    }
                                ]
                            }
                        }
                    ]
                }
            ]
        }
    ],
    "statusHistory": [
        {
            "status": "in-progress",
            "period": {
                "start": "2022-12-22T08:00:00+00:00"
            }
        }
    ],
    "serviceProvider": {
        "reference": "Organization/10085103"
    },
    "basedOn": [
        {
            "reference": "ServiceRequest/1e1a260d-538f-4172-ad68-0aa5f8ccfc4a"
        }
    ]
});



const test = async () => {
    console.log(form);
};

</script>
<style src="@vueform/multiselect/themes/default.css"></style>
