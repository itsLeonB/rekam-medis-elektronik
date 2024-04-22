<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Buat Invoice - </title>
        </template>
        <form @submit.prevent="submit">
            <!-- Status Invoice -->
            <div class="mt-4">
                <InputLabel value="Status" />
                <select id="status"
                    class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                    <option v-for="(label, id) in invoiceStatus" :value=id>{{ label }}</option>
                </select>
            </div>
            <!-- Cancelled Reason -->
            <div class="mt-4">
                <InputLabel value="Cancelled Reason" />
                <TextInput />
            </div>
            <!-- Pilih Pasien -->
            <div class="mt-4">
                <InputLabel for="pasien" value="Identitas Pasien" />
                <Multiselect mode="single" placeholder="NIK Pasien" :filter-results="false" :object="true"
                    :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true" :options="searchPatient"
                    label="label" valueProp="satusehatId" track-by="satusehatId" :classes="combo_classes" required />
                <InputError class="mt-1" />
            </div>
            <!-- Pilih Penanggungjawab pasien -->
            <div class="mt-4">
                <InputLabel value="Penanggung Jawab" />
                <TextInput />
            </div>
            <!-- Tanggal Invoice -->
            <div class="mt-4">
                <InputLabel value="Tanggal Invoice" />
                <div class="flex pt-1">
                    <VueDatePicker class=" border-[1.5px] rounded-lg border-neutral-grey-0 " required></VueDatePicker>
                </div>
            </div>
            <!-- Kasir -->
            <div class="mt-4">
                <InputLabel value="Kasir" />
                <Multiselect mode="single" placeholder="Kasir" :object="true" :options="practitionerList" label="name"
                    valueProp="satusehat_id" track-by="satusehat_id" class="mt-1" :classes="combo_classes" required />
                <InputError class="mt-1" />
            </div>
            <!-- Rincian Biaya -->
            <div class="mt-4">
                <InputLabel value="Rincian Biaya" />
                <select
                    class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                    <option>NO. RM</option>
                </select>
                <table class="border w-full h-auto">
                    <tr>
                        <td>No</td>
                        <td>CONTOH</td>
                        <td>CONTOH</td>
                    </tr>
                </table>
            </div>
            <div class="mt-4">
                <InputLabel value="Total Price Component" />
                <TextInput />
            </div>
            <div class="mt-4">
                <InputLabel value="Total Nett" />
                <TextInput />
            </div>
            <div class="mt-4">
                <InputLabel value="Total Gross" />
                <TextInput />
            </div>
            <div class="mt-4">
                <InputLabel value="Payment Terms" />
                <TextInput />
            </div>
            <div class="mt-4">
                <InputLabel value="Note" />
                <TextInput />
            </div>
        </form>
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutNav.vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';

const resourceForm = ref({
    status: 'drafted',
    cancelledReason: null,
    subject: null,
    recipient: null,
    date: null,
    participant: null,
});

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

const invoiceStatus = { 'draft': 'draft', 'issued': 'issued', 'balanced': 'balanced', 'cancelled': 'cancelled', 'entered-in-error': 'entered in error' }

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

onMounted(() => {
    getpractitionerList();
})


</script>
