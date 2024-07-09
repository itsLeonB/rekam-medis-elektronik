<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Tambah ke Tagihan - </title>
        </template>
        <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <h1 class="text-2xl font-bold text-neutral-black-300">Tambah item tagihan baru</h1>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman untuk membuat item tagihan baru.</p>
            <form @submit.prevent="submit">
                <!-- Status -->
                <div class="mt-4">
                    <InputLabel value="Status" />
                    <select id="status"
                        class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit"
                        v-model="resourceForm.status">
                        <option v-for="(label, id) in chStatus" :value=id>{{ label }}</option>
                    </select>
                </div>
                <!-- Identifier -->
                <div class="mt-4">
                    <InputLabel value="Identifier" />
                    <select id="identifier"
                        class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit"
                        v-model="resourceForm.identifier">
                        <option v-for="(label, id) in identifier" :value=id>{{ label }}</option>
                    </select>
                </div>
                <!-- NOT IMPLEMENTED -->
                <!-- <div class="mt-4">
                    <InputLabel value="Koding" />
                    <Multiselect mode="single" placeholder="Koding" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true" :options="searchCoding"
                        label="display" valueProp="code" track-by="code" :classes="combo_classes" required
                        v-model="resourceForm.coding" />
                    <InputError class="mt-1" />
                </div>

                <div class="mt-4 modifier-field" v-for="(modifier, index) in resourceForm.codingModifier" :key="index">
                    <InputLabel :value="'Modifier ' + (index + 1)" />
                    <Multiselect mode="single" placeholder="Modifier" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="false" :delay="300" :options="searchCodingModifier"
                        label="label" valueProp="code" track-by="code" :classes="combo_classes" required
                        :searchable="true" v-model="resourceForm.codingModifier[index]" />
                    <SecondaryButtonSmall type="button" @click="removeField(index)" class="teal-button-text">Remove
                    </SecondaryButtonSmall>
                </div>
                <div class="mt-2">
                    <SecondaryButtonSmall type="button" @click="addField" class="orange-button text-white">
                        + Tambah Modifier
                    </SecondaryButtonSmall>
                </div> -->
                <!-- NOT IMPLEMENTED  -->
                <!-- Procedure/MedicationRequest/Observation Reference -->
                <div class="mt-4">
                    <InputLabel value="Service" />
                    <TextInput v-model="resourceForm.service" />
                    <InputError class="mt-1" />
                </div>
                <!-- Encounter Reference -->
                <div class="mt-4">
                    <InputLabel value="Kunjungan" />
                    <TextInput v-model="resourceForm.context.reference" />
                    <InputError class="mt-1" />
                </div>
                <!-- Patient Reference -->
                <div class="mt-4">
                    <InputLabel value="Pasien" />
                    <TextInput v-model="resourceForm.subject.reference" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Penginput" />
                    <Multiselect mode="single" placeholder="Penginput" :object="true" :options="practitionerList"
                        label="name" valueProp="satusehat_id" track-by="satusehat_id" class="mt-1"
                        :classes="combo_classes" required v-model="resourceForm.enterer" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Account" />
                    <Multiselect mode="single" placeholder="Account" :filter-results="false" :resolve-on-load="true"
                        :object="true" :options="getAccount" label="name" valueProp="id" track-by="id" class="mt-1"
                        :searchable="true" :classes="combo_classes" required v-model="resourceForm.account" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Tanggal Input" />
                    <div class="flex pt-1">
                        <VueDatePicker class=" border-[1.5px] rounded-lg border-neutral-grey-0 " required
                            v-model="resourceForm.enteredDate">
                        </VueDatePicker>
                    </div>
                </div>
                <div class="mt-4">
                    <InputLabel value="Jumlah barang" />
                    <TextInput type="number" v-model="resourceForm.quantity" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Harga (Rp)" />
                    <TextInput type="number" v-model="resourceForm.price" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4 text-center">
                    <MainButton :isLoading="isLoading"
                        class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0" type="submit">
                        Tambah
                    </MainButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted, computed, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import InputError from '@/Components/InputError.vue';
import MainButton from '@/Components/MainButton.vue';
import SecondaryButtonSmall from '@/Components/SecondaryButtonSmall.vue';
import EncounterDetails from '@/Pages/RekamMedis/Partials/EncounterDetails.vue';

const props = defineProps({
    item_id: {
        type: String,
    },
    item_res_type: {
        type: String,
    }
});

const isLoading = ref(false)

const resourceForm = ref({
    status: "planned",
    identifier: "tarif-rumah-sakit",
    service: null,
    context: {
        reference: null,
        display: ""
    },
    subject: {
        reference: null,
        name: ""
    },
    occurence: null,
    performer: [],
    bodySite: [],
    enterer: null,
    enteredDate: null,
    product: {},
    coding: null,
    codingModifier: [],
    quantity: 0,
    account: null
})

const chStatus = { 'planned': 'Planned', 'billable': 'Billable', 'not-billable': 'Not Billable', 'aborted': 'Aborted', 'entered-in-error': 'Entered in error', 'billed': 'Billed', 'unknown': 'Unknown' } // Status Invoice
const practitionerList = ref(null)
const chargeItem = ref({})
const encounterDetails = ref({})
const subjectDetail = ref("")
const contextDetail = ref("")
const serviceDetail = ref("")
const identifier = {
    'prosedur-non-bedah': 'Prosedur non Bedah',
    'prosedur-bedah': 'Prosedur Bedah',
    'konsultasi': 'Konsultasi',
    'tenaga-ahli': 'Tenaga Ahli',
    'keperawatan': 'Keperawatan',
    'penunjang': 'Penunjang',
    'radiologi': 'Radiologi',
    'laboratorium': 'Laboratorium',
    'pelayanan-darah': 'Pelayanan Darah',
    'rehabilitasi': 'Rehabilitasi',
    'kamar-akomodasi': 'Kamar Akomodasi',
    'rawat-intensif': 'Rawat Intensif',
    'obat': 'Obat',
    'obat-kronis': 'Obat Kronis',
    'obat-kemoterapi': 'Obat Kemoterapi',
    'alat-kesehatan': 'Alat Kesehatan',
    'bmhp': 'BMHP',
    'sewa-alat': 'Sewa Alat',
    'tarif-rumah-sakit': 'Tarif Rumah Sakit',
    'tarif-poli-eksekutif': 'Tarif Poli Eksekutif',
    'tambahan-biaya-naik-kelas': 'Tambahan Biaya Naik Kelas'
}

watch(() => resourceForm.value.coding, (newValue) => {
    console.log(newValue)
    searchCodingModifier(newValue.modifier)
})

const getAccount = async (name) => {
    try {
        const { data } = await axios.get('/resources/Account')
        const originalData = data
        originalData.filter(account => account.name === name)
        return originalData
    } catch (error) {
        console.error('Error fetching resources:', error)
    }
}

const getResourceById = async (resourceName, variable, id) => {
    try {
        const { data } = await axios.get(`/resources/${resourceName}/${id}`)
        variable.value = data;
        console.log(variable.value.subject)
        fillForm(resourceForm, variable.value, props)
    } catch (error) {
        console.error('Error fetching resources:', error);
    }
}

const getpractitionerList = async () => {
    const { data } = await axios.get(route('form.index.encounter'));
    practitionerList.value = data;
};

const fillForm = (resForm, data, props) => {
    if (data.resourceType == 'ChargeItem') {
        // Service
        resForm.value.service = `${props.item_res_type}/${props.item_id}`
        serviceDetail.value = `${props.item_res_type}/${props.item_id} | ${data.code.coding[0].display}`
        // Context (Encounter)
        resForm.value.context.reference = data?.encounter?.reference
        resForm.value.context.display = data?.encounter?.display
        contextDetail.value = `${data?.encounter?.reference} | ${data.encounter.display}`
        // Subject (Patient)
        resForm.value.subject.reference = data?.subject?.reference
        resForm.value.subject.name = data.subject.display
        subjectDetail.value = `${data?.subject?.reference} | ${data?.subject?.display}`
        // Occurence
        resForm.value.occurence = data?.performedPeriod
        // Performer
        resForm.value.performer = data?.performer
        // Bodysite
        resForm.value.bodySite = data?.bodySite
    } else {
        resForm.value.context.reference = "Encounter/" + data?.id
        resForm.value.subject.reference = data.subject.reference
     }
}

const submit = async () => {
    isLoading.value = true;
    const currentTime = new Date().toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '');
    const quantity = parseInt(resourceForm.value.quantity, 10)
    const price = parseInt(resourceForm.value.price, 10)
    console.log(quantity)
    console.log(price)
    const submitResource = {
        "resourceType": "ChargeItem",
        "identifier": {
            "system": "https://terminology.kemkes.go.id/CodeSystem/chargeitem-billingGroup",
            "value": resourceForm.value.identifier,
        },
        "status": resourceForm.value.status,
        "subject": {
            "reference": resourceForm.value.subject.reference,
            "display": resourceForm.value.subject.name,
        },
        "context": {
            "reference": resourceForm.value.context.reference,
            "display": resourceForm.value.context.display,
        },
        "occurrencePeriod": resourceForm.value.occurence ?? {
            start: currentTime,
            end: currentTime
        },
        "priceOverride": {
            "currency": "IDR",
            "value": price
        },
        // "performer": resourceForm.value.performer,
        "enterer": {
            "reference": "Practitioner/" + resourceForm.value.enterer['satusehat_id'],
            "display": resourceForm.value.enterer['name'],
        },
        "enteredDate": currentTime,
        "quantity": {
            "unit": props.item_res_type?.toLowerCase ?? "procedure",
            "value": quantity
        },
        "account": [
            {
                "reference": "Account/" + resourceForm.value.account.id,
                "display": resourceForm.value.account.name
            }
        ],
        "code": {
            "coding": []
        },

    }

    try {
        const resourceType = "ChargeItem";
        const response = await axios.post(route('integration.store', { resourceType: resourceType }), submitResource)
        // const response = await axios.post(route('resources.store', {resType: resourceType}), submitResource)
        console.log(response.data)
        isLoading.value = false;
    } catch (error) {
        console.error(error.response ? error.response.data : error.message);
        isLoading.value = false;
    }
}

onMounted(() => {
    getpractitionerList()
    if (props.item_res_type) {
        getResourceById(props.item_res_type, chargeItem, props.item_id)
    } else {
        getResourceById("Encounter", encounterDetails, props.item_id)
    }
})

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

// NOT IMPLEMENTED (Coding)

// const formatDate = (isoString) => {
//     const date = new Date(isoString);
//     const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
//     const dayOfWeek = daysOfWeek[date.getDay()]; // Get the day of the week
//     const day = String(date.getDate()).padStart(2, '0');
//     const month = String(date.getMonth() + 1).padStart(2, '0');
//     const year = date.getFullYear();
//     return `${dayOfWeek} ${day}/${month}/${year}`;
// };


// NOT IMPLEMENTED
// const processCoding = (base, modifiers) => {
//     let count = 0;
//     const usedModifiers = new Set();

//     // Split the string into parts using "." as the delimiter
//     let parts = base.split(".");

//     // Check for any modifiers already in the original string
//     parts.forEach(part => {
//         usedModifiers.add(part);
//     });

//     // Iterate over the parts and replace ".x" with modifiers
//     for (let i = 0; i < parts.length; i++) {
//         if (parts[i] === 'x') {
//             // Skip over any duplicates or already used modifiers in the original string
//             while (count < modifiers.length && usedModifiers.has(modifiers[count].code)) {
//                 count++;
//             }

//             if (count < modifiers.length) {
//                 parts[i] = modifiers[count].code;
//                 usedModifiers.add(modifiers[count].code);
//                 count++;
//             }
//         }
//     }

//     // Join the parts back into a single string
//     return parts.join(".");
// }

// NOT IMPLEMENTED
// const searchCoding = async (query) => {
//     const { data } = await axios.get(route('terminologi.kptl.base', { 'search': query }));
//     const originalData = data.data;
//     return originalData;
// };

// const searchCodingModifier = async (query) => {
//     const { data } = await axios.get(route('terminologi.kptl.modifier'), {
//         params: { search: query, category: resourceForm.value.coding.modifier }
//     });
//     const originalData = data.data;
//     return originalData;
// }

// const addField = () => {
//     if (getModifierSize(resourceForm.value.coding.modifier) > resourceForm.value.codingModifier.length) {
//         resourceForm.value.codingModifier.push(null);
//     }
// }

// const removeField = (index) => {
//     resourceForm.value.codingModifier.splice(index, 1);
// };

// const processCodingDisplay = (base, modifiers) => {
//     let count = 0;
//     const usedModifiers = new Set();

//     // Split the string into parts using "." as the delimiter
//     let parts = base.split(", ");

//     // Check for any modifiers already in the original string
//     parts.forEach(part => {
//         usedModifiers.add(part);
//     });

//     // Iterate over the parts and replace ".x" with modifiers
//     for (let i = 0; i < parts.length; i++) {
//         if (parts[i] === 'x') {
//             // Skip over any duplicates or already used modifiers in the original string
//             while (count < modifiers.length && usedModifiers.has(modifiers[count].display)) {
//                 count++;
//             }

//             if (count < modifiers.length) {
//                 parts[i] = modifiers[count].display;
//                 usedModifiers.add(modifiers[count].display);
//                 count++;
//             }
//         }
//     }

//     // Join the parts back into a single string
//     return parts.join(", ");
// }

// const getModifierSize = (string) => {
//     return string.split(", ").length
// }
</script>