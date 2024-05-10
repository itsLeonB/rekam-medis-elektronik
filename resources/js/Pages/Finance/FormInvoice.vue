<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Buat Invoice - </title>
        </template>
        <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <h1 class="text-2xl font-bold text-neutral-black-300">Buat Invoice Baru</h1>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman untuk membuat invoice baru.</p>
            <form @submit.prevent="submit">
                <!-- Status Invoice -->
                <div class="mt-4">
                    <InputLabel value="Status" />
                    <select id="status"
                        class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit"
                        v-model="resourceForm.status">
                        <option v-for="(label, id) in invoiceStatus" :value=id>{{ label }}</option>
                    </select>
                </div>
                <!-- Pilih Pasien -->
                <div class="mt-4">
                    <InputLabel for="pasien" value="Identitas Pasien" />
                    <Multiselect mode="single" placeholder="NIK Pasien" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true" :options="searchPatient"
                        label="label" valueProp="satusehatId" track-by="satusehatId" :classes="combo_classes" required
                        v-model="resourceForm.subject" />
                    <InputError class="mt-1" />
                </div>
                <!-- Pilih Penanggungjawab pasien -->
                <div class="mt-4">
                    <InputLabel value="Penanggung Jawab" />
                    <Multiselect mode="single" placeholder="NIK Pasien" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true" :options="searchPatient"
                        label="label" valueProp="satusehatId" track-by="satusehatId" :classes="combo_classes" required
                        v-model="resourceForm.recipient" />
                    <InputError class="mt-1" />
                </div>
                <!-- Tanggal Invoice -->
                <div class="mt-4">
                    <InputLabel value="Tanggal Invoice" />
                    <div class="flex pt-1">
                        <VueDatePicker class=" border-[1.5px] rounded-lg border-neutral-grey-0 " required
                            v-model="resourceForm.date">
                        </VueDatePicker>
                    </div>
                </div>
                <!-- Kasir -->
                <div class="mt-4">
                    <InputLabel value="Kasir" />
                    <Multiselect mode="single" placeholder="Kasir" :object="true" :options="practitionerList"
                        label="name" valueProp="satusehat_id" track-by="satusehat_id" class="mt-1"
                        :classes="combo_classes" required v-model="resourceForm.participant" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4 row col-lg-2">
                    <div>
                        <InputLabel value="Payment Method" />
                        <select id="status"
                            class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit"
                            v-model="resourceForm.bank">
                            <option v-for="(label, id) in paymentMethods" :value=id>{{ label }}</option>
                        </select>
                    </div>
                    <div>
                        <InputLabel value="Payment Details" />
                        <TextInput />
                    </div>
                </div>
                <!-- Rincian Biaya -->
                <div class="mt-4">
                    <InputLabel value="Kunjungan" />
                    <Multiselect mode="single" placeholder="Rincian Biaya" :object="true" :options="encounterList"
                        label="period" valueProp="id" track-by="id" class="mt-1" :classes="combo_classes" required
                        v-on:change="getChargeItemList" />
                    <InputError class="mt-1" />
                    <table class="w-full h-auto mt-2 border">
                        <tr>
                            <th>No</th>
                            <th>Procedure/Observation</th>
                            <th>Harga</th>
                        </tr>
                        <tr class=" text-center" v-for="(item, index) in chargeItemList" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ item.code.coding[0].display }}</td>
                            <td></td>
                        </tr>
                    </table>
                </div>

                <div class="mt-4">
                    <InputLabel value="Total Biaya" />
                    <TextInput v-model="resourceForm.totalPriceComponent" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Total Biaya Kotor" />
                    <TextInput v-model="resourceForm.totalGross" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Total Biaya Nett" />
                    <TextInput v-model="resourceForm.totalNett" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Catatan" />
                    <TextInput v-model="resourceForm.note" />
                </div>
            </form>
        </div>
        <div class="flex flex-col items-center justify-end mt-10">
            <!-- TODO 4: API untuk submit -->
            <MainButton :isLoading="isLoading"
                class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0" type="submit">
                Daftar
            </MainButton>
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutNav.vue';
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';

const resourceForm = ref({
    status: 'drafted',
    cancelledReason: null,
    subject: null,
    recipient: null,
    date: null,
    participant: null,
    bank: null,
    rekening: null,
    note: null,
    totalPriceComponent: null,
    totalNett: null,
    totalGross: null,
    lineItem: null
});

const chargeItem = ref({

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

const submit = async () => {
    isLoading.value = true;
    const currentTime = new Date().toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '');
    const submitResource = {
        "resourceType": "Encounter",
        "status": resourceForm.status,
        "subject": resourceForm.subject,
        "recipient": resourceForm.recipient,
        "date": resourceForm.date,
        "participant": resourceForm.participant
    } // TODO: 3: Atur Resource

    axios.post(route('integration.store', { res_type: "Invoice" }), submitResource)
        .then(response => {
            isLoading.value = false;
            creationSuccessModal.value = true;
        })
        .catch(error => {
            isLoading.value = false;
            console.error('Error creating user:', error);
        });
}

const invoiceStatus = { 'draft': 'draft', 'issued': 'issued', 'balanced': 'balanced', 'cancelled': 'cancelled', 'entered-in-error': 'entered in error' }
const paymentMethods = { 'bank': 'Bank Transfer', 'cash': 'Cash', 'qris': 'QRIS', 'debit': 'Debit Card', 'credit': 'Credit Card' }

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
const encounterList = ref(null);
const procedureList = ref(null);
const medicationList = ref(null);
const organizationList = ref(null);

const chargeItemList = computed(() => {
    if (!medicationList.value || !procedureList.value) {
        return [];
    }
    return [...medicationList.value, ...procedureList.value]
})

const getpractitionerList = async () => {
    const { data } = await axios.get(route('form.index.encounter'));
    practitionerList.value = data;
};

const getResourceList = async (resourceName, list) => {
    const { data } = await axios.get(`/resources/${resourceName}`);
    list.value = data;
}

const getChargeItemList = async () => {
    getResourceList('Procedure', procedureList);
    getResourceList('Medication', medicationList);
}

onMounted(() => {
    getpractitionerList();
    getResourceList('Encounter', encounterList)
})

</script>