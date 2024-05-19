<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Tambah Obat - </title>
        </template>
        <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <h1 class="text-2xl font-bold text-neutral-black-300">Tambah Obat</h1>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman untuk menambahkan user.</p>
            <form @submit.prevent="test">
                <div>
                    <InputLabel for="name" value="Kode Obat" />
                    <Multiselect v-model="form.code_obat" mode="single" placeholder="Obat"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="1000"
                                :searchable="true" :options="searchMedication" label="name" valueProp="kfa_code"
                                track-by="kfa_code" class="mt-1" :classes="combo_classes" required />
                   
                </div>
                <div class="mt-4">
                        <InputLabel for="form" value="Tipe Obat" />
                        <Multiselect v-model="form.form" mode="single" placeholder="Tipe"
                            :object="true" :options="medicationForm" label="display" valueProp="code" track-by="code"
                            class="mt-1" :classes="combo_classes" required />
                </div>
                <div class="mt-4">
                        <InputLabel for="extension" value="Extension" />
                        <Multiselect v-model="form.extension" mode="single" placeholder="Extension"
                            :object="true" :options="medicationExtension" label="display" valueProp="code" track-by="code"
                            class="mt-1" :classes="combo_classes" required />
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
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
import '@vueform/multiselect/themes/default.css';
import Multiselect from '@vueform/multiselect';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    code_obat: '',
    form: '',
    extension: '',
});

const searchMedication = async (query) => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'page': 1,
            'size': 10,
            'product_type': 'farmasi',
            'keyword': query
        }
    });
    console.log(query, 'print this bitch')
    const originalData = data.items.data;
    return originalData;
}

const medicationForm = ref(null);
const getMedicationForm = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'Medication',
            'attribute': 'form'
        }
    });
medicationForm.value = data;
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
    getMedicationForm();
    getMedicationExtension();
});

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);
const errorMessage = ref('');

const test = async () => {
  let formDataJson = {
    resourceType: 'Medication',
    identifier: [
       {
           system: 'http://sys-ids.kemkes.go.id/medication/d7c204fd-7c20-4c59-bd61-4dc55b78438c',
           use: 'official',
           value: '123456789'
       }
   ],
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
    form: {
      coding: [{
        system: 'http://terminology.kemkes.go.id/CodeSystem/medication-form',
        code: form.form.code,
        display: form.form.display,
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
   ]
  };

  try { 
    const resourceType = 'Medication';
    const response = await axios.post(route('integration.store', { resourceType: resourceType }), formDataJson) ;
    console.log(response.data);
    
    // Handle successful response
    successAlertVisible.value = true;
    failAlertVisible.value = false;
    errorMessage.value = ''; // Clear error message

  } catch (error) {
       console.error(error.response ? error.response.data : error.message);
        // Handle error response
            failAlertVisible.value = true;
            successAlertVisible.value = false;

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