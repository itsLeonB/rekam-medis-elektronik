<template>
    <div>
        <form @submit.prevent="submit">
            <div class="my-2 w-full" v-for="(field, index) in resourceForm" :key="index">
                <h3 v-if="index !== 0" class="font-semibold text-secondhand-orange-300 mt-2">Tindakan {{ (index +
                    1)
                }}
                </h3>
                <h3 v-else class="font-semibold text-secondhand-orange-300 mt-2">Tindakan</h3>
                <div class="flex">
                    <div class="w-full md:w-7/12 mr-2">
                        <InputLabel for="tindakan" value="Tindakan" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm[index].code" mode="single" placeholder="Tindakan"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="1000"
                                :searchable="true" :options="searchicd9" label="label" valueProp="code" track-by="code"
                                class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div class="w-full md:w-5/12">
                        <InputLabel for="category" value="Kategori" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm[index].category" mode="single" placeholder="Kategori"
                                :object="true" :options="categoryList" label="display" valueProp="code" track-by="code"
                                class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3 space-x-2">
                    <div class="w-full md:w-6/12">
                        <InputLabel for="performedPeriodStart" value="Tindakan Dimulai" />
                        <div class="flex pt-1">
                            <VueDatePicker v-model="resourceForm[index].performedPeriodStart"
                                class="border-[1.5px] border-neutral-grey-0 rounded-xl" required></VueDatePicker>
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div class="w-full md:w-6/12">
                        <InputLabel for="performedPeriodEnd" value="Tindakan Selesai" />
                        <div class="flex pt-1">
                            <VueDatePicker v-model="resourceForm[index].performedPeriodEnd"
                                class="border-[1.5px] border-neutral-grey-0 rounded-xl" required></VueDatePicker>
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="reasonCode" value="Alasan Tindakan" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm[index].reasonCode" mode="single" placeholder="Diagnosa"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="1000"
                                :searchable="true" :options="searchicd10" label="label" valueProp="code" track-by="code"
                                class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="note" value="Keterangan" />
                        <div class="flex">
                            <TextInput v-model="resourceForm[index].note" id="note" type="text"
                                class="text-sm mt-1 block w-full" placeholder="Keterangan" required />
                            <DeleteButton v-if="index !== 0" @click="removeField(index)" />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
            </div>
            <div class="flex justify-between">
                <SecondaryButtonSmall type="button" @click="addField" class="teal-button-text">+ Tambah Tindakan
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
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import DeleteButton from '@/Components/DeleteButton.vue';
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
import SecondaryButtonSmall from '@/Components/SecondaryButtonSmall.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';

const props = defineProps({
    practitioner_reference: {
        type: Object,
        required: false
    },
    subject_reference: {
        type: Object,
        required: false
    },
    encounter_reference: {
        type: Object,
        required: true
    },
});

const resourceForm = ref([{
    code: null,
    category: null,
    performedPeriodStart: '',
    performedPeriodEnd: '',
    reasonCode: null,
    note: ''
}]);

const addField = () => {
    let resourceFormData = {
        code: null,
        category: null,
        performedPeriodStart: '',
        performedPeriodEnd: '',
        reasonCode: null,
        note: ''
    };
    resourceForm.value.push(resourceFormData);
};

const removeField = (index) => {
    resourceForm.value.splice(index, 1);
};

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);

const submit = () => {
    resourceForm.value.forEach(item => {
        const submitResource = {
            "resourceType": "Procedure",
            "status": "completed",
            "category": {
                "coding": [
                    {
                        "system": item.category.system,
                        "code": item.category.code,
                        "display": item.category.display
                    }
                ]
            },
            "code": {
                "coding": [
                    {
                        "system": item.code.system,
                        "code": item.code.code,
                        "display": item.code.display
                    }
                ]
            },
            "subject": props.subject_reference,
            "encounter": props.encounter_reference,
            "performedPeriod": {
                "start": new Date(item.performedPeriodStart).toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, ''),
                "end": new Date(item.performedPeriodEnd).toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '')
            },
            "performer": [
                {
                    "actor": props.practitioner_reference
                }
            ],
            "reasonCode": [
                {
                    "coding": [
                        {
                            "system": item.reasonCode.system,
                            "code": item.reasonCode.code,
                            "display": item.reasonCode.display_en
                        }
                    ]
                }
            ],
            "note": [
                {
                    "text": item.note
                }
            ]
        };

        axios.post(route('integration.store', { res_type: submitResource.resourceType }), submitResource)
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

const searchicd10 = async (query) => {
    const { data } = await axios.get(route('terminologi.icd10', { 'search': query }));
    const originalData = data;
    for (const key in originalData) {
        const currentObject = originalData[key];
        const label = `${currentObject.display_id} | Code: ${currentObject.code}`;
        currentObject.label = label;
    }
    return originalData;
};

const searchicd9 = async (query) => {
    const { data } = await axios.get(route('terminologi.icd9cm-procedure', { 'search': query }));
    const originalData = data;
    for (const key in originalData) {
        const currentObject = originalData[key];
        const label = `${currentObject.definition} | Code: ${currentObject.code}`;
        currentObject.label = label;
    }
    return originalData;
};

const categoryList = ref(null);
const getCategoryList = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'Procedure',
            'attribute': 'category'
        }
    });
    categoryList.value = data;
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
    getCategoryList();
}
);


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