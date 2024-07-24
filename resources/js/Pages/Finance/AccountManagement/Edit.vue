<template>
    <AuthenticatedLayout>
        <Modal :show="uploadSuccessModal">
            <div class="p-6">
                <h2 class="text-lg text-center font-medium text-gray-900">
                    Data Account telah berhasil diperbarui. <br> Kembali ke dashboard.
                </h2>
                <div class="mt-6 grid justify-center">
                    <Link :href="route('finance')"
                        class="inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Kembali </Link>
                </div>
            </div>
        </Modal>

        <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <h1 class="text-2xl font-bold text-neutral-black-300">Edit Account - {{ props.id }}</h1>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman untuk menyunting Account tagihan.</p>
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
                    <InputLabel value="Nama Pemilik Account" />
                    <TextInput v-model="resForm.name" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Deskripsi Account" />
                    <TextArea v-model="resForm.description"></TextArea>
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
                        Sunting Account
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
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    id: {
        type: String,
    },
})

const showForm = ref(false)
const covType = ref([])
const isCovLoading = ref(false)
const isLoading = ref(false)
const uploadSuccessModal = ref(false)
const accountDetails = ref({})

const getAccountDetails = async (id) => {
    try {
        const { data } = await axios.get('/resources/Account/' + id);
        const originalData = data;
        accountDetails.value = originalData
        populateForm(originalData, resForm)
    } catch (error) {
        console.error('Error fetching data:', error);
        return [];
    }
}

const populateForm = (details, resForm) => {
    resForm.value.status = details.status
    resForm.value.name = details.name
    resForm.value.description = details.description
    resForm.value.servicePeriodStart = parseFormattedDateString(details.servicePeriod.start)
    resForm.value.servicePeriodEnd = parseFormattedDateString(details.servicePeriod.end)
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

function parseFormattedDateString(formattedDateString) {
    // Extract the different parts of the formatted date string
    const regex = /^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})([+-])(\d{2}):(\d{2})$/;
    const match = formattedDateString.match(regex);

    if (!match) {
        throw new Error("Invalid date format");
    }

    const [_, year, month, day, hours, minutes, seconds, offsetSign, offsetHours, offsetMinutes] = match;

    // Convert parts to integers
    const yearInt = parseInt(year, 10);
    const monthInt = parseInt(month, 10) - 1; // JavaScript months are 0-based
    const dayInt = parseInt(day, 10);
    const hoursInt = parseInt(hours, 10);
    const minutesInt = parseInt(minutes, 10);
    const secondsInt = parseInt(seconds, 10);
    const offsetHoursInt = parseInt(offsetHours, 10) * (offsetSign === '+' ? 1 : -1);
    const offsetMinutesInt = parseInt(offsetMinutes, 10) * (offsetSign === '+' ? 1 : -1);

    // Create the original Date object in UTC
    const date = new Date(Date.UTC(yearInt, monthInt, dayInt, hoursInt, minutesInt, secondsInt));

    // Apply the timezone offset
    const totalOffsetMinutes = offsetHoursInt * 60 + offsetMinutesInt;
    date.setUTCMinutes(date.getUTCMinutes() - totalOffsetMinutes);

    return date;
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
    getAccountDetails(props.id)
})

const submit = async () => {
    isLoading.value = true
    console.log(resForm.value)
    const submitForm = {
        "resourceType": "Account",
        "id": accountDetails.value.id,
        "status": resForm.value.status,
        "type": accountDetails.value.type,
        "name": resForm.value.name,
        "description": resForm.value.description,
        "subject": accountDetails.value.subject,
        "coverage": accountDetails.value.coverage,
        "owner": accountDetails.value.owner,
        "servicePeriod": {
            "start": formatDateString(resForm.value.servicePeriodStart),
            "end": formatDateString(resForm.value.servicePeriodEnd)
        }
    }

    try {
        const resourceType = "Account";
        // const response = await axios.put(route('integration.update', { resourceType: resourceType, id: props.id }), submitForm)
        const response = await axios.put(route('resources.update', { resType: resourceType, id: props.id }), submitForm)
        console.log(response.data)
        isLoading.value = false;
        uploadSuccessModal.value = true;
    } catch (error) {
        console.error(error.response ? error.response.data : error.message);
        isCovLoading.value = false;
    }
}

</script>