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
                <div class="mt-4">
                    <InputLabel value="Koding" />
                    <Multiselect mode="single" placeholder="Koding" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true" :options="searchCoding"
                        label="display" valueProp="code" track-by="code" :classes="combo_classes" required
                        v-model="resourceForm.coding" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Modifier" />
                    <Multiselect mode="single" placeholder="Modifier" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="false" :delay="300" :options="searchCodingModifier"
                        label="label" valueProp="code" track-by="code" :classes="combo_classes" required
                        :searchable="true" v-model="resourceForm.codingModifier" />
                    <InputError class="mt-1" />
                    
                </div>
                <!-- Procedure/MedicationRequest/Observation Reference -->
                <div class="mt-4">
                    <InputLabel value="Service" />
                    <TextInput v-model="resourceForm.service" />
                    <InputError class="mt-1" />
                </div>
                <!-- Encounter Reference -->
                <div class="mt-4">
                    <InputLabel value="Kunjungan" />
                    <TextInput v-model="resourceForm.context" />
                    <InputError class="mt-1" />
                </div>
                <!-- Patient Reference -->
                <div class="mt-4">
                    <InputLabel value="Pasien" />
                    <TextInput v-model="resourceForm.subject" />
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
                    <InputLabel value="Tanggal Input" />
                    <div class="flex pt-1">
                        <VueDatePicker class=" border-[1.5px] rounded-lg border-neutral-grey-0 " required
                            v-model="resourceForm.enteredDate">
                        </VueDatePicker>
                    </div>
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
    service: "Reference/ID",
    context: "Encounter/ID",
    subject: "Patient/ID",
    occurence: {},
    performer: [],
    bodySite: [],
    enterer: 'Practitioner/ID',
    enteredDate: null,
    product: {},
    coding: null,
    codingModifier: null,
})

const chStatus = { 'planned': 'Planned', 'billable': 'Billable', 'not-billable': 'Not Billable', 'aborted': 'Aborted', 'entered-in-error': 'Entered in error', 'billed': 'Billed', 'unknown': 'Unknown' } // Status Invoice
const practitionerList = ref(null)
const chargeItem = ref({})
const modifier = ref([])
const prodecureDetail = ref("")

watch(() => resourceForm.valuecoding, (newValue) => {
    console.log(newValue)
    searchCodingModifier(newValue.modifier)
})

const getResourceById = async (resourceName, variable, id) => {
    try {
        const { data } = await axios.get(`/resources/${resourceName}/${id}`)
        variable.value = data;
        fillForm(resourceForm, chargeItem.value, props)
    } catch (error) {
        console.error('Error fetching resources:', error);
    }
}

const searchCoding = async (query) => {
    const { data } = await axios.get(route('terminologi.kptl.base', { 'search': query }));
    const originalData = data.data;
    return originalData;
};

const searchCodingModifier = async (query) => {
    const { data } = await axios.get(route('terminologi.kptl.modifier'), {
        params: { search: query, category: resourceForm.value.coding.modifier }
    });
    const originalData = data.data;
    return originalData;
}

// TODO 1: Multiple Modifier

const getpractitionerList = async () => {
    const { data } = await axios.get(route('form.index.encounter'));
    practitionerList.value = data;
};

const fillForm = (resForm, data, props) => {
    resForm.value.service = `${props.item_res_type}/${props.item_id} | ${data.code.coding[0].display}`
    resForm.value.context = data?.encounter?.reference
    resForm.value.subject = `${data?.subject?.reference} | ${data?.subject?.display}`
    resForm.value.occurence = data?.performedPeriod
    resForm.value.performer = data?.performer
    resForm.value.bodySite = data?.bodySite
}

const formatDate = (isoString) => {
    const date = new Date(isoString);
    const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const dayOfWeek = daysOfWeek[date.getDay()]; // Get the day of the week
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${dayOfWeek} ${day}/${month}/${year}`;
};

const submit = async () => {

}

onMounted(() => {
    getpractitionerList()
    getResourceById(props.item_res_type, chargeItem, props.item_id)
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

</script>