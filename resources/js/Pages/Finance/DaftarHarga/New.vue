<template>
    <AuthenticatedLayout>
        <Modal :show="updateSuccessModal">
            <div class="p-6">
                <h2 class="text-lg text-center font-medium text-gray-900">
                    Data katalog telah berhasil dibuat. <br> Kembali ke Dashboard.
                </h2>
                <div class="mt-6 flex justify-end">
                    <Link :href="route('finance')" class="mx-auto mb-3 w-fit block justify-center px-4 py-2 border border-transparent rounded-lg
                    font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150
                    hover:shadow-lg">
                    Kembali </Link>
                </div>
            </div>
        </Modal>
        <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <h1 class="text-2xl font-bold text-neutral-black-300">Buat Katalog Baru </h1>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman untuk membuat item katalog baru</p>

            <div class="relative overflow-x-auto mb-5">
                <form @submit.prevent="submit">
                    <table class="w-full text-base text-left text-neutral-grey-200 ">
                        <tbody>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                    Kode Obat (KFA)
                                </th>
                                <td class="px-6 py-4 w-2/3">
                                    <Multiselect mode="single" placeholder="Kode Obat (KFA)" :filter-results="false"
                                        :object="true" :min-chars="1" :resolve-on-load="false" :delay="300"
                                        :searchable="true" :options="getKFA" label="display" valueProp="code"
                                        track-by="code" :classes="combo_classes" v-model="preset" />
                                    <InputError class="mt-1" />
                                </td>
                            </tr>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                    ICD 9CM
                                </th>
                                <td class="px-6 py-4 w-2/3">
                                    <Multiselect mode="single" placeholder="Kode Tindakan" :filter-results="false"
                                        :object="true" :min-chars="1" :resolve-on-load="false" :delay="300"
                                        :searchable="true" :options="getICD9CM" label="display" valueProp="code"
                                        track-by="code" :classes="combo_classes" v-model="preset" />
                                    <InputError class="mt-1" />
                                </td>
                            </tr>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                    Nama
                                </th>
                                <td class="px-6 py-4 w-2/3">
                                    <TextInput id="display" v-model="resourceForm.display" />
                                </td>
                            </tr>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                    Kode
                                </th>
                                <td class="px-6 py-4 w-2/3">
                                    <TextInput id="code" v-model="resourceForm.code" />
                                </td>
                            </tr>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                    Harga
                                </th>
                                <td class="px-6 py-4 w-2/3">
                                    <TextInput id="value" v-model="resourceForm.price.value" />
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex flex-col items-center justify-end mt-10">
                        <MainButton :is-loading="isLoading"
                            class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0"
                            type="submit">
                            Submit
                        </MainButton>
                    </div>
                </form>

            </div>

        </div>
    </AuthenticatedLayout>


</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import MainButton from '@/Components/MainButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';
import Modal from '@/Components/Modal.vue';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

const updateSuccessModal = ref(false);
const preset = ref({})
const isLoading = ref(false)
const resourceForm = ref({
    preset: {},
    code: 'Hello',
    display: 'Hello',
    definition: 'Hello',
    price: {
        currency: "IDR",
        value: 0,
    },
    item: {},
})

watch(preset, (newValue) => {
    resourceForm.value.code = newValue.code;
    resourceForm.value.display = newValue.display;
    resourceForm.value.definition = newValue.definition;
});

const getICD9CM = async (query) => {
    try {
        const response = await axios.get(route('terminologi.icd9cm-procedure'), {
            params: {
                search: query
            }
        });
        console.log(response.data.data)
        return response.data.data;
    } catch (error) {
        console.error('Error fetching ICD9CM Procedures:', error);
    }
};

const getKFA = async (query) => {
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


const submit = async () => {
    isLoading.value = true;
    const submitForm = {
        "code": resourceForm.value.code,
        "display": resourceForm.value.display,
        "definition": resourceForm.value.definition,
        "price": {
            "currency": resourceForm.value.price.currency,
            "value": resourceForm.value.price.value
        }
    }

    try {
        const response = await axios.post(route('catalogue.store'), submitForm)
        // const response = await axios.post(route('resources.store', {resType: resourceType}), submitResource)
        console.log(response.data)
        isLoading.value = false;
        updateSuccessModal.value = true;
    } catch (error) {
        console.error(error.response ? error.response.data : error.message);
        isLoading.value = false;
    }
};

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