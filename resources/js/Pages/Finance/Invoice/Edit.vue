<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Sunting Invoice - </title>
        </template>
        <Modal :show="uploadSuccessModal">
            <div class="p-6">
                <h2 class="text-lg text-center font-medium text-gray-900">
                    Data Invoice telah berhasil dibuat. <br> Kembali ke halaman dashboard.
                </h2>
                <div class="mt-6 flex justify-end">
                    <Link :href="route('finance')" as="button"
                        class="mx-auto mb-3 w-fit block justify-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Kembali </Link>
                </div>
            </div>
        </Modal>
        <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <h1 class="text-2xl font-bold text-neutral-black-300">Sunting Invoice - {{ currentInvoice.id }}</h1>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman menyunting invoice yang telah dibuat</p>
            <form @submit.prevent="submit">
                <div class="mt-4">
                    <InputLabel value="Status" />
                    <select id="status"
                        class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit"
                        v-model="resourceForm.status">
                        <option v-for="(label, id) in invoiceStatus" :value=id>{{ label }}</option>
                    </select>
                </div>
                <div class="mt-4">
                    <InputLabel value="Payment Method" />
                    <select id="status"
                        class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit"
                        v-model="resourceForm.paymentMethods">
                        <option v-for="(label, id) in paymentMethods" :value=id>{{ label }}</option>
                    </select>
                </div>
                <div class="mt-4">
                    <InputLabel value="Catatan" />
                    <TextInput v-model="resourceForm.note" />
                </div>
                <div class="flex flex-col items-center justify-end mt-10">
                    <!-- TODO 4: API untuk submit -->
                    <MainButton :isLoading="isLoading"
                        class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0" type="submit">
                        Sunting Invoice
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import axios from 'axios';
import Modal from '@/Components/Modal.vue';
import { ref, onMounted, computed, provide, watch } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    id: {
        type: String,
    },
});

// List Practitioner, Encounter, Procedure, Medication
const practitionerList = ref(null);
const encounterList = ref(null);
const chargeItemList = ref([]);
const isLoading = ref(false); // Status loading
const selectedEncounter = ref(null);
const uploadSuccessModal = ref(false)
const currentInvoice = ref({})

const chargeItemTotal = computed(() => {
    return chargeItemList.value
        .filter(item => item.priceOverride.value)
        .reduce((total, item) => total + Number(item.priceOverride.value), 0);
});

// Resource Form
const resourceForm = ref({
    status: 'draft',
    cancelledReason: null,
    subject: null,
    recipient: null,
    date: null,
    participant: null,
    paymentMethods: null,
    rekening: "",
    note: '',
    encounter: '',
    totalPriceComponent: `${chargeItemTotal.value}`,
    totalNett: 0,
    totalGross: 0,
    lineItem: [],
    issuer: null,
    account: null
});

const grossPrice = computed(() => {
    return chargeItemTotal.value;
})

const nettPrice = computed(() => {
    return chargeItemTotal.value;
})

// IF: Ada ID Encounter
const populateForm = async (itemId) => {
    // selectedEncounter.value =  showResource('Encounter', itemId)
    resourceForm.value.encounter = "Encounter/" + itemId
}

// Watch for changes in chargeItemList to update total price
watch(chargeItemTotal, (newValue) => {
    resourceForm.value.totalPriceComponent = newValue;
});

// Watch for changes in chargeItemList to update total price
watch(grossPrice, (newValue) => {
    resourceForm.value.totalGross = newValue;
});

// // Watch for changes in chargeItemList to update total price
watch(nettPrice, (newValue) => {
    resourceForm.value.totalNett = newValue;
});

watch(chargeItemList, (newValue) => {
    resourceForm.value.lineItem = newValue;
});

const invoiceStatus = { 'draft': 'draft', 'issued': 'issued', 'balanced': 'balanced', 'cancelled': 'cancelled', 'entered-in-error': 'entered in error' } // Status Invoice
const paymentMethods = { 'bank': 'Bank Transfer', 'cash': 'Cash', 'qris': 'QRIS', 'debit': 'Debit Card', 'credit': 'Credit Card' } // Payment Methods

const fetchInvoice = async (id) => {
    try {
        const { data } = await axios.get('/resources/Invoice/' + id)
        const originalData = data
        currentInvoice.value = originalData
        console.log(currentInvoice)
        populate(resourceForm, currentInvoice.value)
    } catch (error) {
        console.error('Error fetching resources:', error)
    }
}

const populate = (resForm, invoice) => {
    resForm.value.status = invoice.status
    resForm.value.paymentMethods = invoice.paymentTerms
    resForm.value.note = invoice.note
}

// Searching Patient By NIK
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

// Format Date
const formatDate = (isoString) => {
    const date = new Date(isoString);

    const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const dayOfWeek = daysOfWeek[date.getDay()]; // Get the day of the week

    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();

    return `${dayOfWeek} ${day}/${month}/${year}`;
};

// Function for getting practitioner resource
const getpractitionerList = async () => {
    const { data } = await axios.get(route('form.index.encounter'));
    practitionerList.value = data;
};

// Function for getting fhir resource
const getResourceList = async (resourceName, list) => {
    try {
        const { data } = await axios.get(`/resources/${resourceName}`);

        if (resourceName == 'Encounter') {
            list.value = data.map((item, index) => ({
                ...item,
                label: `${item.subject.display} | ${formatDate(item.period.start)}`
            }));
        } else {
            list.value = data
        }
    } catch (error) {
        console.error('Error fetching resources:', error);
    }
}

const showResource = async (resourceName, id) => {
    try {
        const { data } = await axios.get(`/resources/${resourceName}/${id}`)
        console.log(data)
        return data.data;
    } catch (error) {
        console.error('Error fetching resources:', error);
    }
}

// Get data based on encounter dan beri harga untuk yang memiliki hargas
const getChargeItemList = async (id) => {
    try {
        const { data } = await axios.get('/resources/ChargeItem')
        const originalData = data.filter(item => item.context.reference === "Encounter/" + id)
        chargeItemList.value = originalData
        console.log(originalData)
    } catch (error) {
        console.error('Error fetching resources:', error);
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

function parseFormattedDateString(formattedDateString) {
    // Split the date string into date and time components
    const [datePart, timePartWithOffset] = formattedDateString.split('T');
    const [timePart, offsetPart] = timePartWithOffset.split(/([+-])/);
    const offsetSign = timePartWithOffset.includes('+') ? '+' : '-';

    // Split date part into year, month, and day
    const [year, month, day] = datePart.split('-').map(Number);

    // Split time part into hours, minutes, and seconds
    const [hours, minutes, seconds] = timePart.split(':').map(Number);

    // Split offset part into hours and minutes
    const offsetHours = Number(offsetPart.slice(0, 2));
    const offsetMinutes = Number(offsetPart.slice(3));

    // Calculate the total offset in minutes
    const totalOffset = (offsetHours * 60 + offsetMinutes) * (offsetSign === '+' ? 1 : -1);

    // Create a new Date object
    const date = new Date(Date.UTC(year, month - 1, day, hours, minutes, seconds));

    // Apply the timezone offset
    date.setMinutes(date.getMinutes() - totalOffset);

    // Format the date to a standard string
    const standardDateString = date.toISOString();

    return standardDateString;
}

onMounted(() => {
    fetchInvoice(props.id)
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

// Submit Form
const submit = async () => {
    isLoading.value = true;

    const submitResource = {
        "resourceType": "Invoice",
        "id": currentInvoice.value.id,
        "status": resourceForm.value.status,
        "subject": currentInvoice.value.subject,
        "recipient": currentInvoice.value.recipient,
        "date": currentInvoice.value.date,
        "participant": currentInvoice.value.participant,
        "issuer": currentInvoice.value.issuer,
        "lineItem": currentInvoice.value.lineItem,
        "totalNet": currentInvoice.value.totalNet,
        "totalGross": currentInvoice.value.totalGross,
        "paymentTerms": resourceForm.value.paymentMethods,
        "note": resourceForm.value.note,
        "account": currentInvoice.value.account
    }

    console.log(submitResource)

    try {
        const resourceType = "Invoice";
        const invId = currentInvoice.value.id;
        // const response = await axios.post(route('integration.store', { resourceType: resourceType }), submitResource)
        const response = await axios.put(route('resources.update', { resType: resourceType, id: invId }), submitResource)
        console.log(response.data)
        isLoading.value = false;
        uploadSuccessModal.value = true;
    } catch (error) {
        console.error(error.response ? error.response.data : error.message);
        isLoading.value = false;
    }
}

</script>