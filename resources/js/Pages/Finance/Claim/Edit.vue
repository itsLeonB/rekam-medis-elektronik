<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Edit Claim - </title>
        </template>
        <Modal :show="uploadSuccessModal">
            <div class="p-6">
                <h2 class="text-lg text-center font-medium text-gray-900">
                    Data Claim telah berhasil disunting. <br> Kembali ke halaman dashboard.
                </h2>
                <div class="mt-6 flex justify-end">
                    <Link :href="route('finance')" as="button"
                        class="mx-auto mb-3 w-fit block justify-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Kembali </Link>
                </div>
            </div>
        </Modal>
        <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <h1 class="text-2xl font-bold text-neutral-black-300">Edit Claim</h1>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman untuk menyunting claim.</p>

            <!-- Form -->
            <form @submit.prevent="submit">
                <div class="mt-4">
                    <InputLabel value="Pasien" />
                    <Multiselect mode="single" placeholder="Pasien" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true" :options="searchPatient"
                        label="label" valueProp="satusehatId" track-by="satusehatId" :classes="combo_classes" required
                        v-model="resourceForm.subject" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Invoice" />
                    <Multiselect mode="single" placeholder="Invoice" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="true" :delay="300" :searchable="true" :options="getInvoice"
                        label="label" valueProp="id" track-by="id" :classes="combo_classes" required
                        v-model="resourceForm.invoice" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Status" />
                    <select v-model="resourceForm.status"
                        class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                        <option v-for="item in status" :value=item.id>{{ item.label }}</option>
                    </select>
                </div>
                <div class="mt-4">
                    <InputLabel value="Priority" />
                    <Multiselect mode="single" placeholder="Priority" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="true" :delay="300" :searchable="true" :options="priority"
                        label="display" valueProp="code" track-by="code" :classes="combo_classes" required
                        v-model="resourceForm.priority" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Type" />
                    <Multiselect mode="single" placeholder="Type" :filter-results="false" :object="true" :min-chars="1"
                        :resolve-on-load="true" :delay="300" :searchable="true" :options="type" label="display"
                        valueProp="code" track-by="code" :classes="combo_classes" required
                        v-model="resourceForm.type" />
                    <InputError class="mt-1" />
                </div>

                <div class="mt-4 text-center">
                    <MainButton :isLoading="isLoading"
                        class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0" type="submit">
                        Edit Claim
                    </MainButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>

</template>
<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';
import TextInput from '@/Components/TextInput.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import MainButton from '@/Components/MainButton.vue';
import Modal from '@/Components/Modal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import SecondaryButtonSmall from '@/Components/SecondaryButtonSmall.vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, computed, provide, watch } from 'vue';

const props = defineProps({
    id: {
        type: String,
    },
});

// Variables
const practitionerList = ref([])
const isLoading = ref(false)
const uploadSuccessModal = ref(false)

const status = [
    { id: "active", label: "Active" },
    { id: "cancelled", label: "Cancelled" },
    { id: "draft", label: "Draft" },
    { id: "entered-in-error", label: "Entered in Error" }
];

const type = [
    {
        system: "http://terminology.hl7.org/CodeSystem/claim-type",
        code: "institutional",
        display: "Institutional"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claim-type",
        code: "oral",
        display: "Oral"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claim-type",
        code: "pharmacy",
        display: "Pharmacy"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claim-type",
        code: "professional",
        display: "Professional"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claim-type",
        code: "vision",
        display: "Vision"
    },
];

const subType = [
    {
        system: "http://terminology.hl7.org/CodeSystem/ex-claimsubtype",
        id: "ortho",
        label: "Orthodontic Claim"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/ex-claimsubtype",
        id: "emergency",
        label: "Emergency Claim"
    },
];

const use = [
    {
        system: "http://hl7.org/fhir/claim-use",
        id: "claim",
        label: "Claim"
    },
    {
        system: "http://hl7.org/fhir/claim-use",
        id: "preauthorization",
        label: "Preauthorization"
    },
    {
        system: "http://hl7.org/fhir/claim-use",
        id: "predetermination",
        label: "Predetermination"
    }
];

const priority = [
    {
        system: "http://terminology.hl7.org/CodeSystem/processpriority",
        code: "stat",
        display: "Immediate"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/processpriority",
        code: "normal",
        display: "Normal"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/processpriority",
        code: "deferred",
        display: "Deferred"
    },
];

const fundsreserve = [
    {
        system: "http://terminology.hl7.org/CodeSystem/fundsreserve",
        id: "patient",
        label: "Patient"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/fundsreserve",
        id: "provider",
        label: "Provider"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/fundsreserve",
        id: "none",
        label: "None"
    },
];

const claimRelationship = [
    {
        system: "http://terminology.hl7.org/CodeSystem/ex-relatedclaimrelationship",
        code: "prior",
        display: "Prior Claim"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/ex-relatedclaimrelationship",
        code: "associated",
        display: "Associated Claim"
    }
];

const payeeType = [
    {
        system: "http://terminology.hl7.org/CodeSystem/payeetype",
        code: "subscriber",
        display: "Subscriber"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/payeetype",
        code: "provider",
        display: "Provider"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/payeetype",
        code: "other",
        display: "Other"
    },
];

const careTeamRole = [
    {
        system: "http://terminology.hl7.org/CodeSystem/claimcareteamrole",
        code: "primary",
        display: "Primary Provider"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claimcareteamrole",
        code: "assist",
        display: "Assisting Provider"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claimcareteamrole",
        code: "supervisor",
        display: "Supervising Provider"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claimcareteamrole",
        code: "other",
        display: "Primary Provider"
    },
];

const careTeamQualification = [
    {
        system: "http://terminology.hl7.org/CodeSystem/ex-providerqualification",
        code: "311405",
        display: "Dentist"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/ex-providerqualification",
        code: "604215",
        display: "Ophthalmologist"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/ex-providerqualification",
        code: "604210",
        display: "Optometrist"
    },
];

const supportingInfoCategories = [
    {
        system: "http://terminology.kemkes.go.id/CodeSystem/claiminformationcategory",
        code: "upgrade-class-indicator",
        display: "Indikator Naik Kelas"
    },
    {
        system: "http://terminology.kemkes.go.id/CodeSystem/claiminformationcategory",
        code: "upgrade-class-class",
        display: "Kenaikan Kelas"
    },
    {
        system: "http://terminology.kemkes.go.id/CodeSystem/claiminformationcategory",
        code: "claim-text-encoded",
        display: "Claim Text Encoded"
    },
    {
        system: "http://terminology.kemkes.go.id/CodeSystem/claiminformationcategory",
        code: "e-klaim-version",
        display: "Versi Aplikasi E-Klaim"
    },
    {
        system: "http://terminology.kemkes.go.id/CodeSystem/claiminformationcategory",
        code: "unu-grouper-version",
        display: "Versi Grouper INACBG"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "info",
        display: "Information"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "discharge",
        display: "Discharge"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "onset",
        display: "Onset"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "related",
        display: "Related Services"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "exception",
        display: "Exception"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "material",
        display: "Materials Forwarded"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "attachment",
        display: "Attachment"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "missingtooth",
        display: "Missing Tooth"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "prosthesis",
        display: "Prosthesis"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "other",
        display: "Other"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "hospitalized",
        display: "Hospitalized"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "employmentimpacted",
        display: "Employment Impacted"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "externalcause",
        display: "External Cause"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "patientreasonforvisit",
        display: "Patient Reason for Visit"
    }
];

const payeePartyType = ["Practitioner", "Patient", "Organization"]
const providerType = ["Practitioner", "Organization"]
const claimData = ref({ subject: {} });

// ResourceForm
const resourceForm = ref({
    status: 'active',
    encounter: {},
    type: 'institutional',
    subType: 'ortho',
    use: 'claim',
    fundsreserve: 'none',
    subject: {},
    billablePeriod: {
        start: "",
        end: ""
    },
    enterer: {},
    insurer: {},
    provider: {},
    priority: "stat",
    related: [],
    prescription: {},
    originalPrescription: {},
    payee: {
        type: {},
        party: {
            type: 'Organization',
            reference: {}
        },
    },
    facility: {},
    careTeam: [

    ],
    supportingInfo: [],
    procedure: [],
    diagnosis: [],
    insurance: [],
    invoice: {},
});

const fetchClaim = async (id) => {
    try {
        const { data } = await axios.get('/resources/Claim/' + id)
        const originalData = data
        claimData.value = originalData
        fillData(originalData)
    } catch (error) {
        console.error('Error fetching resources:', error)
        claimData.value = {}
    }
}

const fetchSubject = async (id) => {
    try {
        const { data } = await axios.get('/resources/Patient/' + id)
        const originalData = data
        originalData.label = originalData.name[0].text
        originalData.satusehatId = originalData.id
        return originalData;
    } catch (error) {
        console.error('Error fetching resources:', error)
        return {}
    }
}

const fillData = (data) => {
    // console.log(data)
    resourceForm.value.status = data.status
    resourceForm.value.priority = data.priority.coding
    resourceForm.value.type = data.type.coding
    // console.log(resourceForm)
}

const getInvoice = async (query) => {
    try {
        const { data } = await axios.get('/resources/Invoice');
        const originalData = data
        for (const key in originalData) {
            const currentObject = originalData[key];
            const label = `${currentObject.subject.display} | ${formatTimestamp(currentObject.date)} `;
            currentObject.label = label;
        }
        const filteredData = originalData.filter(item => item.label.includes(query))
        return filteredData;
    } catch (error) {
        console.error('Error fetching data:', error);
        return [];
    }
}

const formatTimestamp = (timestamp) => {
    const date = new Date(timestamp);
    date.setHours(date.getHours() + 7);
    const daysOfWeek = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    const dayOfWeek = daysOfWeek[date.getUTCDay()];
    const day = date.getUTCDate();
    const month = months[date.getUTCMonth()];
    const year = date.getUTCFullYear();
    const hour = date.getUTCHours().toString().padStart(2, '0');
    const minute = date.getUTCMinutes().toString().padStart(2, '0');

    return `${dayOfWeek}, ${day} ${month} ${year} / ${hour}:${minute}`;
};

const searchPatient = async (query) => {
    try {
        const { data } = await axios.get(route('rekam-medis.index', { 'nik': query }));
        const originalData = data.rekam_medis.data;
        for (const key in originalData) {
            const currentObject = originalData[key];
            const label = `${currentObject.name} | NIK: ${currentObject.nik}`;
            currentObject.label = label;
            currentObject.id = currentObject.satusehatId;
        }
        return originalData;
    } catch (error) {
        console.error('Error fetching data:', error);
        return [];
    }
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

onMounted(async () => {
    await fetchClaim(props.id)
    if (claimData.value.patient?.reference) {
        // console.log(claimData.value);
        const subjectId = claimData.value.patient.reference.split("/")[1];
        resourceForm.value.subject = await fetchSubject(subjectId);
        // console.log(resourceForm.value.subject);
    } else {
        console.error('Patient data is missing in the fetched data');
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

function generateUUID() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        const r = (crypto.getRandomValues(new Uint8Array(1))[0] & 0x0f);
        const v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}


const submit = async () => {
    isLoading.value = true
    // console.log(resourceForm.value)

    const submitResource = {
        "id": claimData.value.id,
        "resourceType": "Claim",
        "status": resourceForm.value.status,
        "type": {
            "coding": {
                "system": resourceForm.value.type.system,
                "code": resourceForm.value.type.code,
                "display": resourceForm.value.type.display
            }
        },
        "subType": claimData.value.subType,
        "use": claimData.value.use,
        "patient": {
            "reference": "Patient/" + resourceForm.value.subject?.satusehatId,
            "display": resourceForm.value.subject?.name[0]?.text
        },
        "billablePeriod": claimData.value.billablePeriod,
        "created": claimData.value.created,
        "enterer": claimData.value.enterer,
        "insurer": claimData.value.insurer,
        "provider": claimData.value.provider,
        "priority": {
            "coding": {
                "system": resourceForm.value.priority.system,
                "code": resourceForm.value.priority.code,
                "display": resourceForm.value.priority.display
            }
        },
        "fundsReserve": claimData.value.fundsReserve,
        "prescription": claimData.value.prescription,
        "originalPrescription": claimData.value.originalPrescription,
        "payee": claimData.value.payee,
        "facility": claimData.value.facility,
        "careTeam": claimData.value.careTeam,
        "diagnosis": claimData.value.diagnosis,
        "procedure": claimData.value.procedure,
        "insurance": claimData.value.insurance,
        "total": resourceForm.value.invoice.totalNet
    }
    console.log(submitResource, resourceForm)

    try {
        const resourceType = "Claim";
        const claimID = claimData.value.id
        const response = await axios.put(route('resources.update', { resType: resourceType, id: claimID }), submitResource)
        isLoading.value = false;
        uploadSuccessModal.value = true;
    } catch (error) {
        console.error(error.response ? error.response.data : error.message);
        isLoading.value = false;
    }
}
</script>