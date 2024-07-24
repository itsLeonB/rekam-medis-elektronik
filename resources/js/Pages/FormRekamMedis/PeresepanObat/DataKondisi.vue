<template>
    <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-1/3 py-2 lg:pr-7 lg:pb-0">
            <h2 class="text-md font-semibold text-secondhand-orange-300">a. Keluhan</h2>
            <p v-if="error">{{ errorMessage }}</p>
            <ul v-else>
                <li v-for="(item, index) in keluhans" :key="index">
                    {{ item.code.coding[0].display }}
                </li>
            </ul>
        </div>
        <div class="w-full lg:w-1/3 py-2 lg:pr-7 lg:pb-0">
            <h2 class="text-md font-semibold text-secondhand-orange-300">b. Riwayat Alergi</h2>
            <p v-if="error">{{ errorMessage }}</p>
            <ul v-else>
                <li v-if="isAlergiEmpty">-</li>
                <li v-else v-for="(item, index) in alergi" :key="index">
                    {{item.category[0]}} - {{ item.code.coding[0].display }}
                </li>
            </ul>
        </div>
        <div class="w-full lg:w-1/3 py-2 lg:pr-7 lg:pb-0">
            <h2 class="text-md font-semibold text-secondhand-orange-300">c. Diagnosa</h2>
            <p v-if="error">{{ errorMessage }}</p>
            <ul v-else>
                <li v-for="(item, index) in diagnosis" :key="index">
                    {{ item.code.coding[0].display }}
                </li>
            </ul>
        </div>
    </div>
</template>
<script setup>
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';

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
const keluhans = ref(null);
const diagnosis = ref(null);
const alergi = ref(null);

const errorMessage = ref('');
const fetchKeluhan = async () => {
    try {
        const { data } = await axios.get(route('showForConditionPatient', 
        { 
            section: 'keluhan',
            id: props.encounter_satusehat_id 
        }));
        keluhans.value = data;
      } catch (error) {
        errorMessage.value = 'Terjadi kesalahan dalam mengambil data.';
        console.error(error);
      }
};
const fetchDiagnosa = async () => {
    try {
        const { data } = await axios.get(route('showForConditionPatient', 
        { 
            section: 'diagnosa',
            id: props.encounter_satusehat_id 
        }));
        diagnosis.value = data;
      } catch (error) {
        errorMessage.value = 'Terjadi kesalahan dalam mengambil data.';
        console.error(error);
      }
};
const fetchAlergi = async () => {
    try {
        const { data } = await axios.get(route('showForConditionPatient', 
        { 
            section: 'alergi',
            id: props.encounter_satusehat_id 
        }));
        
        alergi.value = data;
      } catch (error) {
        errorMessage.value = 'Terjadi kesalahan dalam mengambil data.';
        console.error(error);
      }
};

onMounted(() => {
    fetchKeluhan();
    fetchDiagnosa();
    fetchAlergi();
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
ul{
    font-size : 12px;
}
</style>