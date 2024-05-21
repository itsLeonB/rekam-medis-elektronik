<template>
    <AuthenticatedLayout>
        <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <span class="inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M10 4C8.93913 4 7.92172 4.42143 7.17157 5.17157C6.42143 5.92172 6 6.93913 6 8C6 9.06087 6.42143 10.0783 7.17157 10.8284C7.92172 11.5786 8.93913 12 10 12C11.0609 12 12.0783 11.5786 12.8284 10.8284C13.5786 10.0783 14 9.06087 14 8C14 6.93913 13.5786 5.92172 12.8284 5.17157C12.0783 4.42143 11.0609 4 10 4ZM10 6C10.5304 6 11.0391 6.21071 11.4142 6.58579C11.7893 6.96086 12 7.46957 12 8C12 8.53043 11.7893 9.03914 11.4142 9.41421C11.0391 9.78929 10.5304 10 10 10C9.46957 10 8.96086 9.78929 8.58579 9.41421C8.21071 9.03914 8 8.53043 8 8C8 7.46957 8.21071 6.96086 8.58579 6.58579C8.96086 6.21071 9.46957 6 10 6ZM17 12C16.84 12 16.76 12.08 16.76 12.24L16.5 13.5C16.28 13.68 15.96 13.84 15.72 14L14.44 13.5C14.36 13.5 14.2 13.5 14.12 13.6L13.16 15.36C13.08 15.44 13.08 15.6 13.24 15.68L14.28 16.5V17.5L13.24 18.32C13.16 18.4 13.08 18.56 13.16 18.64L14.12 20.4C14.2 20.5 14.36 20.5 14.44 20.5L15.72 20C15.96 20.16 16.28 20.32 16.5 20.5L16.76 21.76C16.76 21.92 16.84 22 17 22H19C19.08 22 19.24 21.92 19.24 21.76L19.4 20.5C19.72 20.32 20.04 20.16 20.28 20L21.5 20.5C21.64 20.5 21.8 20.5 21.8 20.4L22.84 18.64C22.92 18.56 22.84 18.4 22.76 18.32L21.72 17.5V16.5L22.76 15.68C22.84 15.6 22.92 15.44 22.84 15.36L21.8 13.6C21.8 13.5 21.64 13.5 21.5 13.5L20.28 14C20.04 13.84 19.72 13.68 19.4 13.5L19.24 12.24C19.24 12.08 19.08 12 19 12H17ZM10 13C7.33 13 2 14.33 2 17V20H11.67C11.39 19.41 11.19 18.77 11.09 18.1H3.9V17C3.9 16.36 7.03 14.9 10 14.9C10.43 14.9 10.87 14.94 11.3 15C11.5 14.36 11.77 13.76 12.12 13.21C11.34 13.08 10.6 13 10 13ZM18.04 15.5C18.84 15.5 19.5 16.16 19.5 17.04C19.5 17.84 18.84 18.5 18.04 18.5C17.16 18.5 16.5 17.84 16.5 17.04C16.5 16.16 17.16 15.5 18.04 15.5Z"
                        fill="currentColor" />
                </svg>
                <h1 class="text-2xl font-bold text-neutral-black-300">Expert Systems</h1>
            </span>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman Expert Systems Medication.
            </p> 

            <form @submit.prevent="test">
                <div>
                    <InputLabel for="code_keluhan" value="Keluhan" />
                    <div class="flex">
                            <Multiselect v-model="form.keluhan" mode="multiple" placeholder="Keluhan"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="400"
                                :searchable="true" :options="searchKeluhan" label="label" valueProp="code" track-by="code"
                                class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel for="code_diagnosa" value="Diagnosa" />
                    <Multiselect v-model="form.diagnosa" mode="single" placeholder="Diagnosa"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="400"
                                :searchable="true" :options="searchicd10" label="label" valueProp="code" track-by="code"
                                class="mt-1" :classes="combo_classes" required />
                   <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel for="umur_id" value="Umur" />
                    <select id="umur_id" v-model="form.umur_id"
                            class="w-full bg-original-white-0 mr-3 border-1 border-neutral-grey-0 text-neutral-black-300 text-sm rounded-lg focus:ring-original-teal-300 focus:border-original-teal-300 block w-40 px-2.5 h-fit"
                            required>
                    <option disabled value="">Pilih Umur</option>
                    <option v-for="item in umur" :value="item.id">{{ item.label }}</option>
                    </select>
                   <InputError class="mt-1" />
                </div>
                    
                <!-- <div class="mt-4">
                    <InputLabel for="name" value="Obat" />
                    <Multiselect v-model="form.obat" mode="multiple" placeholder="Obat"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="400"
                                :searchable="true" :options="searchMedication" label="name" valueProp="kfa_code"
                                track-by="kfa_code" class="mt-1" :classes="combo_classes" required />
                <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel for="instruksi" value="Instuksi Obat" />
                    <TextArea v-model="form.instruksi" id="text" type="text"
                                class="text-sm mt-1 block w-full" placeholder="Instruksi Obat yang diberikan" required></TextArea>
                <InputError class="mt-1" />
                </div> -->
                <div v-for="(field, index) in form.obatInstruksi" :key="index" class="mt-4">
                    <div>
                        <InputLabel for="name" value="Obat" />
                        <Multiselect
                        v-model="field.obat"
                        mode="multiple"
                        placeholder="Obat"
                        :filter-results="false"
                        :object="true"
                        :min-chars="1"
                        :resolve-on-load="false"
                        :delay="400"
                        :searchable="true"
                        :options="searchMedication"
                        label="name"
                        valueProp="kfa_code"
                        track-by="kfa_code"
                        class="mt-1"
                        :classes="combo_classes"
                        required
                        />
                        <InputError class="mt-1" />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="instruksi" value="Instruksi Obat" />
                        <TextArea
                        v-model="field.instruksi"
                        id="text"
                        type="text"
                        class="text-sm mt-1 block w-full"
                        placeholder="Instruksi Obat yang diberikan"
                        required
                        ></TextArea>
                        <InputError class="mt-1" />
                    </div>
                    <div class="mt-4 flex justify-end">
                        <DeleteButton v-if="form.obatInstruksi.length > 1" @click="removeField(index)" />
                    </div>
                </div>
                <div class="flex justify-between mt-4">
                <SecondaryButtonSmall type="button" @click="addField" class="teal-button-text">+ Tambah Keluhan</SecondaryButtonSmall>
                </div>
                <div class="flex flex-col items-center justify-end mt-10">
                    <MainButton class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0">
                        Tambah
                    </MainButton>
                </div>
                <p v-if="successAlertVisible" class="text-sm text-original-teal-300">Sukses!</p>
                <p v-if="failAlertVisible" class="text-sm text-thirdouter-red-300">Gagal!</p>
                <p v-if="errorMessage" class="text-red-500">{{ errorMessage }}</p>
            </form>
        </div>
       

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutNav.vue';
import MainButton from '@/Components/MainButton.vue';
import { Link } from '@inertiajs/vue3';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
import SecondaryButtonSmall from '@/Components/SecondaryButtonSmall.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import DeleteButton from '@/Components/DeleteButton.vue';
import TextArea from '@/Components/TextArea.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';

const users = ref([]);

const form = useForm({
    //code_obat: [],
    keluhan: [],
    diagnosa: '',
    umur_id: '',
    obat: [null],
    instruski: [null]
    //instruksi:'',
});
const addField = () => {
    let data = {
        obat: [null],
        instruksi: [null]
    };
    form.value.push(data);
};
const removeField = (index) => {
    form.value.splice(index, 1);
};
const umur = [
    {"id": 'balita', "label": 'Balita (< 6 Tahun)'},
    {"id": 'anak', "label": 'Anak-anak (6-12 Tahun)'},
    {"id": 'dewasa', "label": 'Dewasa (> 12 Tahun)'},
];
const hide = ref(false);
const searchKeluhan = async (query) => {
    const { data } = await axios.get(route('terminologi.condition.keluhan', { 'search': query }));
    const originalData = data;
    for (const key in originalData) {
        const currentObject = originalData[key];
        const label = `${currentObject.display} | Code: ${currentObject.code}`;
        currentObject.label = label;
    }
    return originalData;
};
const searchicd10 = async (query) => {
    const { data } = await axios.get(route('terminologi.icd10', { 'search': query }));
    const originalData = data;
    originalData.data = originalData.data.map(item => {
        return {
                ...item,
                label: `${item.display_id} | Code: ${item.code}`,
                value: item.code
            };
        });
    return originalData.data;
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
    return data.items.data.map(item => ({
          name: item.name,
          kfa_code: item.kfa_code,
        }));
    const originalData = data.items.data;
    return originalData;
}

const fetchPagination = async (page = 1) => {
    if (searchNama.value == '') {
        const { data } = await axios.get(route('users.index', {'page': page}));
        users.value = data.users;
    } else {
        const query = searchNama.value;
        const { data } = await axios.get(route('users.index'), {params: {'name': query, 'page': page}});
        users.value = data.users;
    };
};
const successAlertVisible = ref(false);
const failAlertVisible = ref(false);
const errorMessage = ref('');
const test = async() => {
        let formResource = {
          keluhan: [form.keluhan],
          diagnosa: form.diagnosa,
          umur: form.umur_id
        };
        try {
            const response = await axios.post(route('expertsystems.rule.peresepanobat'), formResource) ;
            console.log(response.data);

            successAlertVisible.value = true;
            failAlertVisible.value = false;
            errorMessage.value = '';
        } catch (error) {
            console.error(error.response ? error.response.data : error.message);
        // Handle error response
            failAlertVisible.value = true;
            successAlertVisible.value = false;

       if (error.response && error.response.data) {
            console.error('Response:', error.response.data); // Display server response data
            // Assign the error message from the server response to the errorMessage property
            errorMessage.value = error.response.data.error || 'Failed to save data';
        } else {
            // If there is no response, assign a general error message
            errorMessage.value = 'An error occurred while saving data';
        }
        }
    };
onMounted(() => {
    
}
);
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
<style>
.dp__theme_light {
    --dp-background-color: #fff;
    --dp-text-color: #323232;
    --dp-hover-color: #f3f3f3;
    --dp-hover-text-color: #323232;
    --dp-hover-icon-color: #499d8c;
    --dp-primary-color: #499d8c;
    --dp-primary-disabled-color: #6db1a3;
    --dp-primary-text-color: #f8f5f5;
    --dp-secondary-color: #499d8c;
    --dp-border-color: #b5b3bc;
    --dp-border-color-hover: #499d8c;
    --dp-menu-border-color: #ddd;
    --dp-highlight-color: #499d8c;

}

:root {
    /*General*/
    --dp-font-family: "Poppins", "Open Sans", "Helvetica Neue", sans-serif;
    --dp-border-radius: 10px;
    --dp-cell-border-radius: 12px;
    --dp-input-padding: 10px 12px;
    --dp-font-size: 0.875rem;
}
</style>