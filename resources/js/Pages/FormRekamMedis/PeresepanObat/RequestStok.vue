<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Request Stok Obat - </title>
        </template>
        <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-12 sm:py-16 md:px-10 ">
            <h1 class="text-2xl font-bold text-neutral-black-300">Request Stok Obat</h1>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman untuk meminta stok obat.</p>
            <form @submit.prevent="test">
                <div class="my-2 w-full" v-for="(field, index) in form" :key="index">
                    <h3 v-if="index !== 0" class="font-semibold text-secondhand-orange-300 mt-2">Request {{ (index + 1) }}
                    </h3>
                    <h3 v-else class="font-semibold text-secondhand-orange-300 mt-2 mb-1">Request</h3>
                        <div class="w-full mb-3">
                            <InputLabel for="name" value="Nama Obat" />
                            <div class="flex">
                                <Multiselect v-model="form[index].code_obat" mode="single" placeholder="Obat"
                                    :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="100"
                                    :searchable="true" :options="searchMedication" label="name" valueProp="kfa_code"
                                    track-by="kfa_code" class="mt-1" :classes="combo_classes" required />
                                    <DeleteButton v-if="index !== 0" @click="removeField(index)" />
                            </div>
                        </div>           
                </div>
                 <div class="flex justify-between">
                    <SecondaryButtonSmall type="button" @click="addField" class="teal-button-text">+ Tambah Obat
                    </SecondaryButtonSmall>
                    <div class="mt-2 mr-3">
                        <MainButtonSmall type="submit" class="teal-button text-original-white-0">Submit</MainButtonSmall>
                    </div>
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
import SecondaryButtonSmall from '@/Components/SecondaryButtonSmall.vue';
import DeleteButton from '@/Components/DeleteButton.vue';
import { Link } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';

const form = ref([{
    code_obat: [null]
}]);

const addField = () => {
    let medicationData = {
        code_obat: [null]
    };
    form.value.push(medicationData);
}
const removeField = (index) => {
    form.value.splice(index, 1);
};
const searchMedication = async (query) => {
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

const organizationRef = ref(null);
const getorganizationRef = async () => {
    const { data } = await axios.get(route('form.ref.organization', {layanan: 'induk'}));
    console.log(data)
    organizationRef.value = data;
};

onMounted(() => {
    getorganizationRef();
});

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);
const errorMessage = ref('');
const isLoading = ref(false);

const test = () => {
    form.value.forEach(item => {
        const formDataJson = {
            code: {
                code_kfa: item.code_obat.kfa_code,
                display: item.code_obat.name,
            }
        };
        console.log('Sending data:', formDataJson);
        axios.post(route('request-to-stock.store'), formDataJson)
            .then(response => {
                console.log('Response:', response.data); 
                successAlertVisible.value = true;
                setTimeout(() => {
                    successAlertVisible.value = false;
                }, 3000);
            })
            .catch(error => {
                console.error('Error creating user:', error);
                failAlertVisible.value = true;
                setTimeout(() => {
                    failAlertVisible.value = false;
                }, 3000);
            });
    });
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