<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Daftar Rawat Inap - </title>
        </template>
        <Modal :show="creationSuccessModal">
            <div class="p-6">
                <h2 class="text-lg text-center font-medium text-gray-900">
                    Data kunjungan telah berhasil dibuat. <br> Kembali ke halaman Rawat Jalan.
                </h2>
                <div class="mt-6 flex justify-end">
                    <Link :href="route('rawatjalan')"
                        class="mx-auto mb-3 w-fit block justify-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Kembali </Link>
                </div>
            </div>
        </Modal>
        <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <h1 class="text-2xl font-bold text-neutral-black-300">Daftar Rawat Jalan</h1>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman pendaftaran rawat jalan.</p>
            <form @submit.prevent="submit">
                <!-- Status -->
                <div class="mt-4">
                    <InputLabel for="status" value="Status" />
                    <select id="status" v-model="resourceForm.status_kunjungan"
                        class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                        <option v-for="status in status_kunjungan_list" :value=status.id>{{ status.label }}</option>
                    </select>
                    <InputError class="mt-1" />
                </div>

                <!-- Identifier -->
                <div class="mt-4">
                    <InputLabel for="pasien" value="Identitas Pasien" />
                    <Multiselect v-model="resourceForm.patient" mode="single" placeholder="NIK Pasien" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true" :options="searchPatient"
                        label="label" valueProp="satusehatId" track-by="satusehatId" :classes="combo_classes" required />
                    <InputError class="mt-1" />
                </div>

                <!-- Participant/Nakes -->
                <div class="mt-4">
                    <InputLabel for="dokter" value="Identitas Dokter" />
                    <Multiselect v-model="resourceForm.dokter" mode="single" placeholder="Status" :object="true"
                                :options="practitionerList" label="name" valueProp="satusehat_id" track-by="satusehat_id" class="mt-1"
                                :classes="combo_classes" required />
                    <InputError class="mt-1" />
                </div>

                <!-- Poli -->
                <div class="mt-4">
                    <InputLabel for="ruangan" value="Poli" />
                    <Multiselect v-model="resourceForm.ruangan" mode="single" placeholder="Poli" :object="true"
                                :options="ruangan" label="label" valueProp="id" track-by="id" class="mt-1"
                                :classes="combo_classes" required />
                    <InputError class="mt-1" />
                </div>

                <!-- Ruangan -->
                <div class="mt-4">
                    <InputLabel for="lokasi_ruangan" value="Lokasi Poli" />
                    <Multiselect v-model="resourceForm.lokasi_ruangan" mode="single" placeholder="Lokasi Poli" :object="true"
                                :options="ruanganList" label="label" valueProp="satusehat_id" track-by="satusehat_id" class="mt-1"
                                :classes="combo_classes" required />
                    <InputError class="mt-1" />
                </div>
                <div class="flex flex-col items-center justify-end mt-10">
                    <MainButton class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0">
                        Daftar
                    </MainButton>
                </div>
            </form>
            <p v-if="successAlertVisible" class="text-sm text-original-teal-300">Sukses!</p>
                <p v-if="failAlertVisible" class="text-sm text-thirdouter-red-300">Gagal!</p>
                <p v-if="errorMessage" class="text-red-500">{{ errorMessage }}</p>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import MainButton from '@/Components/MainButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, onMounted } from 'vue';

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);
const errorMessage = ref('');

const resourceForm = ref({
    status_kunjungan: 'arrived',
    patient: null,
    dokter: null,
    ruangan: null,
    lokasi_ruangan: null
});

const searchPatient = async (query) => {
    const { data } = await axios.get(route('rekam-medis.index', { 'nik': query }));
    const originalData = data.rekam_medis.data;
    for (const key in originalData) {
        const currentObject = originalData[key];
        const label = `${currentObject.name} | NIK: ${currentObject.nik}`;
        currentObject.label = label;
    }
    return originalData;
};

const practitionerList = ref(null);
const getpractitionerList = async () => {
    const { data } = await axios.get(route('form.index.encounter'));
    practitionerList.value = data;
};

const ruanganList = ref(null);
const getRuanganList = async () => {
    const { data } = await axios.get(route('form.index.location'));
    const originalData = data;
    for (const key in originalData) {
        const currentObject = originalData[key];
        const label = `${currentObject.name} | ${currentObject.serviceClass}`;
        currentObject.label = label;
    }
    ruanganList.value = data;
};

const organizationRef = ref(null);
const getorganizationRef = async () => {
    const { data } = await axios.get(route('form.ref.organization', {layanan: 'induk'}));
    organizationRef.value = data;
};

const ruangan = [
    { "id": 124, "value": 'umum', "label": 'Poli Umum', "display": 'General practice' },
    { "id": 177, "value": 'neurologi', "label": 'Poli Neurologi', "display": 'Neurology' },
    { "id": 186, "value": 'obgyn', "label": 'Poli Obgyn', "display": 'Obstetrics & Gynaecology'  },
    { "id": 88, "value": 'gigi', "label": 'Poli Gigi', "display": 'General Dental'  },
    { "id": 168, "value": 'kulit', "label": 'Poli Kulit dan Kelamin', "display": 'Dermatology'  },
    { "id": 218, "value": 'ortopedi', "label": 'Poli Ortopedi', "display": 'Orthopaedic Surgery'  },
    { "id": 557, "value": 'dalam', "label": 'Poli Penyakit Dalam', "display": 'Inpatients'  },
    { "id": 221, "value": 'bedah', "label": 'Poli Bedah', "display": 'Surgery - General'  },
    { "id": 286, "value": 'anak', "label": 'Poli Anak', "display": 'Children'  }
];

const submit = async () => {
    isLoading.value = true;
    const currentTime = new Date().toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '');
    const submitResource = {
        "resourceType": "Encounter",
        "status": resourceForm.value.status_kunjungan,
        "class": {
            "system": "http://terminology.hl7.org/CodeSystem/v3-ActCode",
            "code": "AMB",
            "display": "ambulatory"
        }, 
        "serviceType": {
            "coding": [
                {
                    "system": "http://terminology.hl7.org/CodeSystem/service-type",
                    "code": resourceForm.value.ruangan.id.toString(),
                    "display": resourceForm.value.ruangan.display
                }
            ]
        },
        "subject": {
            "reference": "Patient/" + resourceForm.value.patient['ihs-number'],
            "display": resourceForm.value.patient.name
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
                    "reference": "Practitioner/" + resourceForm.value.dokter.satusehat_id,
                    "display": resourceForm.value.dokter.name
                }
            }
        ],
        "period": {
            "start": currentTime
        },
        "location": [
            {
                "location": {
                    "reference": "Location/" + resourceForm.value.lokasi_ruangan.satusehat_id,
                    "display": resourceForm.value.lokasi_ruangan.name
                },
                // "period": {
                //     "start": currentTime
                // },
                // "extension": [
                //     {
                //         "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/ServiceClass",
                //         "extension": [
                //             {
                //                 "url": "value",
                //                 "valueCodeableConcept": {
                //                     "coding": [
                //                         {
                //                             "system": "http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Outpatient",
                //                             "code": resourceForm.value.lokasi_ruangan.serviceClass.split(' ')[1].toLowerCase(),
                //                             "display": resourceForm.value.lokasi_ruangan.serviceClass
                //                         }
                //                     ]
                //                 }
                //             }
                //         ]
                //     }
                // ]
            }
        ],
        "statusHistory": [
            {
                "status": resourceForm.value.status_kunjungan,
                "period": {
                    "start": currentTime
                }
            }
        ],
        "serviceProvider": organizationRef.value,
        "identifier": [
            {
                "system": "http://sys-ids.kemkes.go.id/encounter/d7c204fd-7c20-4c59-bd61-4dc55b78438c",
                "value": resourceForm.value.patient['ihs-number']
            }
    ]
    };

    try { 
    const resourceType = 'Encounter';
    const response = await axios.post(route('integration.store', { resourceType:  resourceType}), submitResource) ;
    console.log(response.data);
    
    // Handle successful response
    creationSuccessModal.value = true;
    failAlertVisible.value = false;
    errorMessage.value = ''; // Clear error message

  } catch (error) {
       console.error(error.response ? error.response.data : error.message);
        // Handle error response
            failAlertVisible.value = true;
            creationSuccessModal.value = false;

       if (error.response && error.response.data) {
            console.error('Response:', error.response.data); // Display server response data
            // Assign the error message from the server response to the errorMessage property
            errorMessage.value = error.response.data.error || 'Failed to save data';
        } else {
            // If there is no response, assign a general error message
            errorMessage.value = 'An error occurred while saving data';
        }
        
    }
};

const creationSuccessModal = ref(false);
const isLoading = ref(false);

const status_kunjungan_list = [
    { "id": 'planned', "label": 'Planned' },
    { "id": 'arrived', "label": 'Arrived' },
    { "id": 'triaged', "label": 'Triaged' },
    { "id": 'in-progress', "label": 'In Progress' },
    { "id": 'onleave', "label": 'Onleave' },
    { "id": 'finished', "label": 'Finished' },
    { "id": 'cancelled', "label": 'Cancelled' }
];

const combo_classes = {
    container: 'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer border-2 border-neutral-grey-0 ring-0 shadow-sm rounded-xl bg-white text-base leading-snug outline-none',
    search: 'w-full absolute inset-0 outline-none border-0 ring-0 focus:ring-original-teal-300 focus:ring-2 appearance-none box-border text-base font-sans bg-white rounded-xl pl-3.5 rtl:pl-0 rtl:pr-3.5',
    placeholder: 'flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-500 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
    optionPointed: 'text-white bg-original-teal-300',
    optionSelected: 'text-white bg-original-teal-300',
    optionDisabled: 'text-gray-300 cursor-not-allowed',
    optionSelectedPointed: 'text-white bg-original-teal-300 opacity-90',
    optionSelectedDisabled: 'text-green-100 bg-original-teal-300 bg-opacity-50 cursor-not-allowed',
};

onMounted(() => {
    getpractitionerList();
    getRuanganList();
    getorganizationRef();
})
</script>