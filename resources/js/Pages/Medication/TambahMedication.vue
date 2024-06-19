<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Tambah Obat - </title>
        </template>
        <Modal :show="creationSuccessModal">
            <div class="p-6">
                <h2 class="text-lg text-center font-medium text-gray-900">
                    Data Obat berhasil ditambahkan. <br> Kembali ke halaman Obat.
                </h2>
                <div class="mt-6 flex justify-end">
                    <Link :href="route('medication.table')"
                        class="mx-auto mb-3 w-fit block justify-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Kembali </Link>
                </div>
            </div>
        </Modal>
        <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <h1 class="text-2xl font-bold text-neutral-black-300">Tambah Obat</h1>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman untuk menambahkan obat.</p>
            <form @submit.prevent="submitForm">
                <div>
                    <InputLabel for="code_obat" value="Kode Obat" />
                    <Multiselect v-model="form.code_obat" mode="single" placeholder="Obat"
                        :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="1000"
                        :searchable="true" :options="searchMedication" label="name" valueProp="kfa_code"
                        track-by="kfa_code" class="mt-1" :classes="combo_classes" required />
                    <span v-if="errors.code_obat" class="text-red-500">{{ errors.code_obat }}</span>
                </div>
                <div class="mt-4">
                    <InputLabel for="extension" value="Extension" />
                    <Multiselect v-model="form.extension" mode="single" placeholder="Extension"
                        :object="true" :options="medicationExtension" label="display" valueProp="code" track-by="code"
                        class="mt-1" :classes="combo_classes" required />
                    <span v-if="errors.extension" class="text-red-500">{{ errors.extension }}</span>
                </div>
                <div class="mt-4">
                    <InputLabel for="amount" value="Amount" />
                    <TextInput v-model="form.amount" placeholder="Jumlah" />
                    <span v-if="errors.amount" class="text-red-500">{{ errors.amount }}</span>
                </div>
                <div class="flex flex-col items-center justify-end mt-10">
                    <MainButton class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0">
                        Tambah
                    </MainButton>
                </div>
                <p v-if="successAlertVisible" class="text-sm text-original-teal-300">Sukses!</p>
                <p v-if="failAlertVisible" class="text-sm text-thirdouter-red-300">Gagal!</p>
                <p v-if="errorMessage" class="text-red-500">{{ errorMessage }}</p>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import MainButton from '@/Components/MainButton.vue';
import '@vueform/multiselect/themes/default.css';
import Multiselect from '@vueform/multiselect';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Link } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    code_obat: '',
    extension: '',
});

const searchMedication = async (query) => {
    const { data } = await axios.get(route('terminologi.medication'), {
        params: {
            'page': 1,
            'size': 10,
            'product_type': 'farmasi',
            'keyword': query
        }
    });
    const originalData = data.items.data;
    return originalData;
}

const organizationRef = ref(null);
const getorganizationRef = async () => {
    const { data } = await axios.get(route('form.ref.organization', {layanan: 'induk'}));
    organizationRef.value = data;
};
const medicationExtension = ref(null);
const getMedicationExtension = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'Medication',
            'attribute': 'medicationType'
        }
    });
medicationExtension.value = data;
};

onMounted(() => {
    getMedicationExtension();
    getorganizationRef();
});

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);
const errorMessage = ref('');
const creationSuccessModal = ref(false);
const isLoading = ref(false);

const submitForm = async () => {
  isLoading.value = true;
  let formDataJson = {
    resourceType: 'Medication',
    identifier: [
       {
           system: 'http://sys-ids.kemkes.go.id/medication/d7c204fd-7c20-4c59-bd61-4dc55b78438c',
           use: 'official',
           value: '123456789'
       }
   ],
    // identifier: [organizationRef.value],
    meta: {
        profile: [
            'https://fhir.kemkes.go.id/r4/StructureDefinition/Medication'
        ]
    },
    code: {
      coding: [{
        system: 'http://sys-ids.kemkes.go.id/kfa',
        code: form.code_obat.kfa_code,
        display: form.code_obat.name,
      }],
    },
    
   status: form.code_obat.active ? 'active' : 'inactive',
    form: {
      coding: [{
        system: 'http://terminology.kemkes.go.id/CodeSystem/medication-form',
        code: form.code_obat.dosage_form.code,
        display: form.code_obat.dosage_form.name,
      }],
    },
    extension: [
       {
           url: 'https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType',
           valueCodeableConcept: {
                coding: [
                   {
                       system: 'http://terminology.kemkes.go.id/CodeSystem/medication-type',
                       code: form.extension.code,
                       display: form.extension.display,
                   }
               ]
           }
       }
   ],
   ingredient: form.code_obat.active_ingredients.map(ingredient => ({
                itemCodeableConcept: {
                    coding: [{
                        system: 'http://sys-ids.kemkes.go.id/kfa',
                        code: ingredient.kfa_code,
                        display: ingredient.zat_aktif
                    }]
                },
                isActive: ingredient.active,
            }))
  };

  try { 
    const resourceType = 'Medication';
    const response = await axios.post(route('integration.store', { resourceType: "Medication" }), formDataJson) ;
    console.log(response.data);
    
    creationSuccessModal.value = true;
    failAlertVisible.value = false;
    errorMessage.value = ''; 

  } catch (error) {
       console.error(error.response ? error.response.data : error.message);
            failAlertVisible.value = true;
            creationSuccessModal.value = true;

       if (error.response && error.response.data) {
            console.error('Response:', error.response.data); 
            errorMessage.value = error.response.data.error || 'Failed to save data';
        } else {
            errorMessage.value = 'An error occurred while saving data';
        }
        
    }
};

const combo_classes = {
    container: 'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer border-2 border-neutral-grey-0 ring-0 shadow-sm rounded-xl bg-white text-sm leading-snug outline-none',
    search: 'w-full absolute inset-0 outline-none border-0 ring-0 focus:ring-original-teal-300 focus:ring-2 appearance-none box-border text-sm font-sans bg-white rounded-xl pl-3.5 rtl:pl-0 rtl:pr-3.5',
    placeholder: 'flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-500 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
    option: 'flex items-center justify-start box-border text-left cursor-pointer text-sm leading-snug py-1.5 px-3',
    optionPointed: 'text-white bg-original-teal-300',
    optionSelected: 'text-white bg-original-teal-300',
    optionDisabled: 'text-gray-300 cursor-not-allowed',
    optionSelectedPointed: 'text-white bg-original-teal-300 opacity-90',
    optionSelectedDisabled: 'text-green-100 bg-original-teal-300 bg-opacity-50 cursor-not-allowed',
};
</script>
