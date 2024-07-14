<template>
    <div class="mt-2">
        <table v-if="expertSystem && expertSystem.length && expertSystem[0].length" class="w-full text-left rtl:text-right text-neutral-grey-200 ">
            <thead class="text-neutral-black-300 uppercase bg-gray-50 border-b">
                <tr>
                    
                    <th scope="col" class="px-3 py-2 w-4/6 border">Nama</th>
                    <th scope="col" class="px-3 py-2 w-2/6 border">Instruksi</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(group, index) in expertSystem" :key="index">
                    <tr v-for="(medicine, i) in group" :key="i" class="bg-original-white-0 ">
                        
                        <td class="px-3 py-1 w-4/6 border">{{ medicine.display }}</td>
                        <td class="px-3 py-1 w-2/6 border">{{ medicine.dosageInstruction }}</td>
                    </tr>
                </template>
            </tbody>
        </table>
        <p v-else>{{ errorMessage || 'Tidak ada data yang ditemukan.' }}</p>
    </div>
</template>
<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';

const props = defineProps({
    subject_reference: {
        type: Object,
        required: false
    },
    encounter_reference: {
        type: Object,
        required: true
    },
     encounter_satusehat_id: {
        type: String,
    },
});
const expertSystem = ref(null);

const errorMessage = ref('');
const getExpertSystem = async () => {
    try {
        const {data} = await axios.get(route('ruleperesepan.show', {
            rule: 'resepObat',
            id: props.encounter_satusehat_id
        }));
        
        if (Array.isArray(data) && data.length > 0 && data[0].length === 0) {
            expertSystem.value = null;
            errorMessage.value = 'Tidak ada data yang ditemukan.';
        } else {
            expertSystem.value = data;
            errorMessage.value = '';
        }
    } catch (error) {
        if (error.response && error.response.status === 404) {
            errorMessage.value = error.response.data.message;
        } else {
            errorMessage.value = 'Terjadi kesalahan dalam mengambil data.';
        }
        expertSystem.value = null;
    }
};


onMounted(() => {
    getExpertSystem()
});
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
table{
    font-size : 13px;
}
thead{
    font-size: 16px;
}
</style>