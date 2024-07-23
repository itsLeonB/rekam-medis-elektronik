<template>
    <AuthenticatedLayout>
        <Modal :show="uploadSuccessModal">
            <div class="p-6">
                <h2 class="text-lg text-center font-medium text-gray-900">
                    Data Account telah berhasil dibuat. <br> Kembali ke halaman daftar Account.
                </h2>
                <div class="mt-6 flex justify-end">
                    <Link :href="route('finance.account.index')"
                        class="inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Kembali </Link>
                </div>
            </div>
        </Modal>
        <Modal :show="showForm">
            <div class="p-6">
                <SecondaryButton @click="toggleForm(true)"
                    class="mx-auto mb-3 inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3 "
                        stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Back
                </SecondaryButton>
                <h1 class="text-2xl font-bold text-neutral-black-300">Tambah Coverage baru</h1>
                <form @submit.prevent="submitCov">
                    <div class="mt-4">
                        <InputLabel value="Nomor ID" />
                        <TextInput v-model="covResForm.subscriberId" />
                    </div>
                    <div class="mt-4">
                        <InputLabel value="Status" />
                        <select id="status-cov" v-model="covResForm.status"
                            class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                            <option v-for="status in cov_status" :value="status.id">{{ status.label }}</option>
                        </select>
                    </div>
                    <div class="mt-4">
                        <InputLabel value="Jenis Coverage" />
                        <Multiselect mode="single" placeholder="Jenis Coverage" :filter-results="false" :object="true"
                            :min-chars="1" :resolve-on-load="true" :delay="300" :searchable="true" :options="getCovType"
                            label="display" valueProp="code" track-by="code" :classes="combo_classes" required
                            v-model="covResForm.type" />
                    </div>
                    <div class="mt-4">
                        <InputLabel value="Hubungan dengan pasien" />
                        <Multiselect mode="single" placeholder="Relationship" :filter-results="false" :object="true"
                            :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true"
                            :options="relationshipTypes" label="label" valueProp="id" track-by="id"
                            :classes="combo_classes" required v-model="covResForm.relationship" />
                    </div>
                    <div class="mt-4">
                        <InputLabel value="Penerima benefit (beneficiary)" />
                        <Multiselect mode="single" placeholder="Beneficiary" :filter-results="false" :object="true"
                            :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true"
                            :options="searchPatient" label="label" valueProp="satusehatId" track-by="satusehatId"
                            :classes="combo_classes" required v-model="covResForm.beneficiary" />
                        <InputError class="mt-1" />
                    </div>
                    <div class="mt-4">
                        <InputLabel value="Penanggungjawab pembayaran (payor)" />
                        <Multiselect mode="single" placeholder="Payor" :filter-results="false" :object="true"
                            :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true"
                            :options="searchPatient" label="label" valueProp="satusehatId" track-by="satusehatId"
                            :classes="combo_classes" required v-model="covResForm.payor" />
                        <InputError class="mt-1" />
                    </div>
                    <div class="mt-4 grid grid-cols-2">
                        <div>
                            <InputLabel value="Class type" />
                            <Multiselect mode="single" placeholder="Class" :filter-results="false" :object="true"
                                :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true"
                                :options="classType" label="label" valueProp="id" track-by="id" :classes="combo_classes"
                                v-model="covResForm.classType[0]" />
                            <InputError class="mt-1" />
                        </div>
                        <div>
                            <InputLabel value="Class value" />
                            <Multiselect mode="single" placeholder="Class value" :filter-results="false" :object="true"
                                :min-chars="1" :resolve-on-load="true" :delay="300" :searchable="true"
                                :options="getCovClass" label="name" valueProp="value" track-by="value"
                                :classes="combo_classes" v-model="covResForm.classValue[0]" />
                            <InputError class="mt-1" />
                        </div>

                    </div>
                    <div class="mt-4 text-center">
                        <MainButton :isLoading="isCovLoading"
                            class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0"
                            type="submit">
                            Tambah
                        </MainButton>
                    </div>
                </form>
                <div></div>
            </div>
        </Modal>
        <template #apphead>
            <title>Tambah ke Tagihan - </title>
        </template>
        <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <h1 class="text-2xl font-bold text-neutral-black-300">Tambah Account baru</h1>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman untuk membuat Account tagihan baru.</p>

            <form @submit.prevent="submit">
                <div class="mt-4">
                    <InputLabel value="Status" />
                    <select id="status" v-model="resForm.status"
                        class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                        <option v-for="status in account_status" :value=status.id>{{ status.label }}</option>
                    </select>
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Type" />
                    <Multiselect mode="single" placeholder="Type" :filter-results="false" :object="true" :min-chars="1"
                        :resolve-on-load="false" :delay="300" :searchable="true" :options="account_type" label="label"
                        valueProp="id" track-by="id" :classes="combo_classes" required v-model="resForm.type" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Identitas Pasien" />
                    <Multiselect mode="single" placeholder="Pasien" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true" :options="searchPatient"
                        label="label" valueProp="satusehatId" track-by="satusehatId" :classes="combo_classes" required
                        v-model="resForm.subject" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Nama Pemilik Account" />
                    <TextInput v-model="resForm.name" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Deskripsi Account" />
                    <TextArea v-model="resForm.description"></TextArea>
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4 grid-cols-4 grid gap-1">
                    <div class=" col-span-2">
                        <InputLabel value="Coverage" />
                        <Multiselect mode="single" placeholder="Coverage" :filter-results="false" :object="true"
                            :min-chars="1" :resolve-on-load="true" :delay="300" :searchable="true"
                            :options="getCoverage" label="label" valueProp="id" track-by="id" :classes="combo_classes"
                            required v-model="resForm.coverage[0]" />
                        <InputError class="mt-1" />
                    </div>
                    <div class=" col-span-2">
                        <InputLabel value="Prioritas" />
                        <select id="status" v-model="resForm.coveragePriority"
                            class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                            <option v-for="status in [1, 2, 3, 4]" :value=status>{{ status }}</option>
                        </select>
                    </div>
                    <SecondaryButton @click="toggleForm(showForm.value)"
                        class="inline-block mt-2 mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm orange-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                        Tambah Coverage
                    </SecondaryButton>
                </div>
                <div class="mt-4">
                    <InputLabel value="Owner" />
                    <Multiselect mode="single" placeholder="Owner" :filter-results="false" :object="true" :min-chars="1"
                        :resolve-on-load="false" :delay="300" :searchable="true" :options="searchOrg" label="label"
                        valueProp="id" track-by="id" :classes="combo_classes" required v-model="resForm.owner" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4 grid grid-cols-2 gap-1">
                    <div>
                        <InputLabel value="ServicePeriod (start)" />
                        <VueDatePicker class=" border-[1.5px] rounded-lg border-neutral-grey-0 " required
                            v-model="resForm.servicePeriodStart">
                        </VueDatePicker>
                    </div>
                    <div>
                        <InputLabel value="ServicePeriod (end)" />
                        <VueDatePicker class=" border-[1.5px] rounded-lg border-neutral-grey-0 " required
                            v-model="resForm.servicePeriodEnd">
                        </VueDatePicker>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <MainButton :isLoading="isLoading"
                        class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0" type="submit">
                        Tambah Account
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
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import Modal from '@/Components/Modal.vue';
import MainButton from '@/Components/MainButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const showForm = ref(false)
const covType = ref([])
const isCovLoading = ref(false)
const isLoading = ref(false)
const uploadSuccessModal = ref(false)

const searchPatient = async (query) => {
    const { data } = await axios.get(route('rekam-medis.index', { 'nik': query }));
    const originalData = data.rekam_medis.data;
    // console.log(data);
    for (const key in originalData) {
        const currentObject = originalData[key];
        const label = `${currentObject.name} | NIK: ${currentObject.nik}`;
        currentObject.label = label;
    }
    return originalData;
};

const searchOrg = async (query) => {
    try {
        const { data } = await axios.get(route('satusehat.search.organization', { 'name': query }));
        const originalData = data.entry || [];
        // Map the data to the required structure
        return originalData.map(item => ({
            label: item.resource.name,
            id: item.resource.id,
            ...item.resource
        }));
    } catch (error) {
        console.error('Error fetching data:', error);
        return [];
    }
}

const getCovType = async () => {
    const { data } = await axios.get(route('terminologi.cov-type'));
    const originalData = data.data;
    return originalData;
}

function formatDateString(dateString) {
    // Parse the date string into a Date object
    const date = new Date(dateString);

    // Get the components of the date
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');

    // Get the timezone offset in hours and minutes
    const timezoneOffset = -date.getTimezoneOffset();
    const offsetHours = String(Math.floor(Math.abs(timezoneOffset) / 60)).padStart(2, '0');
    const offsetMinutes = String(Math.abs(timezoneOffset) % 60).padStart(2, '0');
    const offsetSign = timezoneOffset >= 0 ? '+' : '-';

    // Construct the formatted date string
    const formattedDate = `${year}-${month}-${day}T${hours}:${minutes}:${seconds}${offsetSign}${offsetHours}:${offsetMinutes}`;

    return formattedDate;
}

const getCovClass = async () => {
    const { data } = await axios.get(route('terminologi.cov-class'));
    const originalData = data.data;
    return originalData;
}

const getCoverage = async () => {
    try {
        const { data } = await axios.get(`/resources/Coverage`);
        const originalData = data;
        for (const key in originalData) {
            const currentObject = originalData[key];
            const label = `${currentObject.beneficiary.display} | NIK: ${currentObject.subscriberId} | Type: ${currentObject.type.coding[0].display}`;
            currentObject.label = label;
        }
        return originalData;
    } catch (error) {
        console.error('Error fetching resources:', error);
    }
}

const resForm = ref({
    status: 'active',
    type: '_ActAccountCode',
    subject: null,
    owner: null,
    name: '',
    description: '',
    servicePeriodStart: null,
    servicePeriodEnd: null,
    coverage: [],
    coveragePriority: 1,
});

const covResForm = ref({
    status: 'active',
    type: 'pay',
    relationship: 'self',
    beneficiary: null,
    subscriberId: "",
    payor: null,
    classType: [],
    classValue: [],
});

const toggleForm = (status) => {
    showForm.value = !status
}

const cov_status = [
    { "id": "active", "label": "Active" },
    { "id": "cancelled", "label": "Cancelled" },
    { "id": "draft", "label": "Draft" },
    { "id": "entered-in-error", "label": "Entered in Error" }
]

const relationshipTypes = [
    { "id": "child", "label": "Child" },
    { "id": "parent", "label": "Parent" },
    { "id": "spouse", "label": "Spouse" },
    { "id": "common", "label": "Common Law Spouse" },
    { "id": "other", "label": "Other" },
    { "id": "self", "label": "Self" },
    { "id": "injured", "label": "Injured Party" }
];

const account_status = [
    { "id": "active", "label": "Active" },
    { "id": "inactive", "label": "Inactive" },
    { "id": "entered-in-error", "label": "Entered in error" },
    { "id": "on-hold", "label": "On hold" },
    { "id": "unknown", "label": "Unknown" }
];

const classType = [
    { "id": "group", "label": "Group" },
    { "id": "subgroup", "label": "SubGroup" },
    { "id": "plan", "label": "Plan" },
    { "id": "subplan", "label": "SubPlan" },
    { "id": "class", "label": "Class" },
    { "id": "subclass", "label": "SubClass" },
    { "id": "sequence", "label": "Sequence" },
    { "id": "rxbin", "label": "RX BIN" },
    { "id": "rxpcn", "label": "RX PCN" },
    { "id": "rxid", "label": "RX Id" },
    { "id": "rxgroup", "label": "RX Group" }
];


const account_type = [
    { id: '_ActAccountCode', label: 'ActAccountCode' },
    { id: 'ACCTRECEIVABLE', label: 'account receivable' },
    { id: 'CASH', label: 'Cash' },
    { id: 'CC', label: 'credit card' },
    { id: 'AE', label: 'American Express' },
    { id: 'DN', label: 'Diner\'s Club' },
    { id: 'DV', label: 'Discover Card' },
    { id: 'MC', label: 'Master Card' },
    { id: 'V', label: 'Visa' },
    { id: 'PBILLACT', label: 'patient billing account' }
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
    getCovType()
    getCovClass()
})

const submit = async () => {
    isLoading.value = true
    console.log(resForm.value)
    const submitForm = {
        "resourceType": "Account",
        "status": resForm.value.status,
        "type": {
            "coding": [
                {
                    "system": "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                    "code": resForm.value.type.id,
                    "display": resForm.value.type.label
                }
            ]
        },
        "name": resForm.value.subject.name,
        "description": resForm.value.description,
        "subject": [
            {
                "reference": "Patient/" + resForm.value.subject.satusehatId,
                "display": resForm.value.subject.name
            }
        ],
        "coverage": [
            {
                "coverage": {
                    "reference": "Coverage/" + resForm.value.coverage[0].id,
                    "display": resForm.value.subject.name,
                },
                "priority": resForm.value.coveragePriority
            }
        ],
        "owner": {
            "reference": "Organization/" + resForm.value.owner.id,
            "display": resForm.value.owner.name
        },
        "servicePeriod": {
            "start": formatDateString(resForm.value.servicePeriodStart),
            "end": formatDateString(resForm.value.servicePeriodEnd)
        }
    }

    try {
        const resourceType = "Account";
        const response = await axios.post(route('integration.store', { resourceType: resourceType }), submitForm)
        // const response = await axios.post(route('resources.store', {resType: resourceType}), submitResource)
        console.log(response.data)
        isLoading.value = false;
        uploadSuccessModal.value = true;
    } catch (error) {
        console.error(error.response ? error.response.data : error.message);
        isLoading.value = false;
    }
}

const submitCov = async () => {
    isCovLoading.value = true
    console.log(covResForm.value)
    const submitCoverage = {
        "resourceType": "Coverage",
        "status": covResForm.value.status,
        "identifier": [
            {
                "use": "official",
                "system": "https://fhir.kemkes.go.id/id/coverage",
                "value": covResForm.value.beneficiary.satusehatId
            }
        ],
        "type": {
            "coding": [
                {
                    "system": covResForm.value.type.system,
                    "code": covResForm.value.type.code,
                    "display": covResForm.value.type.display
                }
            ]
        },
        "relationship": {
            "coding": [
                {
                    "system": "http://terminology.hl7.org/CodeSystem/subscriber-relationship",
                    "code": covResForm.value.relationship.id,
                    "display": covResForm.value.relationship.label
                }
            ]
        },
        "subscriberId": covResForm.value.subscriberId,
        "class": [
        ],
        "beneficiary": {
            "reference": "Patient/" + covResForm.value.beneficiary.satusehatId,
            "display": covResForm.value.beneficiary.name
        },
        "payor": [
            {
                "reference": "Patient/" + covResForm.value.payor.satusehatId,
                "display": covResForm.value.payor.name
            }
        ]
    }

    try {
        const resourceType = "Coverage";
        const response = await axios.post(route('integration.store', { resourceType: resourceType }), submitCoverage)
        // const response = await axios.post(route('resources.store', {resType: resourceType}), submitResource)
        console.log(response.data)
        isCovLoading.value = false;
        showForm.value = false;
    } catch (error) {
        console.error(error.response ? error.response.data : error.message);
        isCovLoading.value = false;
    }
}

</script>