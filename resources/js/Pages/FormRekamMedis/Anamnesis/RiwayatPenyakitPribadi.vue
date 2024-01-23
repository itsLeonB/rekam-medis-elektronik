<template>
    <div>
        <form @submit.prevent="submit">
            <div class="my-2 w-full" v-for="(field, index) in riwayatPenyakit" :key="index">
                <h3 v-if="index !== 0" class="font-semibold text-secondhand-orange-300 mt-2">Riwayat Penyakit Pribadi {{
                    (index + 1) }}</h3>
                <h3 v-else class="font-semibold text-secondhand-orange-300 mt-2">Riwayat Penyakit Pribadi</h3>
                <div class="flex">
                    <div class="w-full md:w-7/12 mr-2">
                        <InputLabel for="riwayatPenyakit" value="Riwayat Penyakit" />
                        <div class="flex">
                            <Multiselect v-model="riwayatPenyakit[index].code" mode="single" placeholder="Penyakit"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="400"
                                :searchable="true" :options="searchRiwayatPenyakit" label="label" valueProp="code"
                                track-by="code" class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div class="w-full md:w-5/12">
                        <InputLabel for="clinical_status" value="Clinical Status" />
                        <div class="flex">
                            <Multiselect v-model="riwayatPenyakit[index].clinical" mode="single" placeholder="Status"
                                :object="true" :options="clinicalStatusList" label="display" valueProp="code"
                                track-by="code" class="mt-1" :classes="combo_classes" required />
                            <DeleteButton v-if="index !== 0" @click="removeField(index)" />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
            </div>
            <div class="flex justify-between">
                <SecondaryButtonSmall type="button" @click="addField" class="teal-button-text">+ Tambah Riwayat Penyakit
                </SecondaryButtonSmall>
                <div class="mt-2 mr-3">
                    <MainButtonSmall type="submit" class="teal-button text-original-white-0">Submit</MainButtonSmall>
                </div>
            </div>
            <p v-if="successAlertVisible" class="text-sm text-original-teal-300">Sukses!</p>
            <p v-if="failAlertVisible" class="text-sm text-thirdouter-red-300">Gagal!</p>
        </form>
    </div>
</template>

<script setup>
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';
import DeleteButton from '@/Components/DeleteButton.vue';
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
import SecondaryButtonSmall from '@/Components/SecondaryButtonSmall.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
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
});

const riwayatPenyakit = ref([{
    code: [null],
    clinical: [null]
}]);

const addField = () => {
    let riwayatPenyakitData = {
        code: [null],
        clinical: [null]
    };
    riwayatPenyakit.value.push(riwayatPenyakitData);
};

const removeField = (index) => {
    riwayatPenyakit.value.splice(index, 1);
};

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);


const submit = () => {
    riwayatPenyakit.value.forEach(item => {
        item.code = item.code ? (({ label, ...rest }) => rest)(item.code) : item.code;
        item.clinical = item.clinical ? (({ definition, ...rest }) => rest)(item.clinical) : item.clinical;

        const riwayatPenyakitPribadiResource = {
            "resourceType": "Condition",
            "clinicalStatus": {
                "coding": [item.clinical]
            },
            "category": [
                {
                    "coding": [
                        {
                            "system": "http://terminology.hl7.org/CodeSystem/condition-category",
                            "code": "problem-list-item",
                            "display": "Problem List Item"
                        }
                    ]
                }
            ],
            "code": {
                "coding": [item.code]
            },
            "subject": props.subject_reference,
            "encounter": props.encounter_reference
        };

        axios.post(route('integration.store', { res_type: riwayatPenyakitPribadiResource.resourceType }), riwayatPenyakitPribadiResource)
            .then(response => {
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

const searchRiwayatPenyakit = async (query) => {
    const { data } = await axios.get(route('terminologi.condition.riwayat-pribadi', { 'search': query }));
    const originalData = data;
    for (const key in originalData) {
        const currentObject = originalData[key];
        const label = `${currentObject.display} | Code: ${currentObject.code}`;
        currentObject.label = label;
    }
    return originalData;
};

const clinicalStatusList = ref(null);

const getClinicalStatusList = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'Condition',
            'attribute': 'clinicalStatus'
        }
    });
    clinicalStatusList.value = data;
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

onMounted(() => {
    getClinicalStatusList();
}
);


</script>