<template>
    <div>
        <form @submit.prevent="submit">
            <div class="my-2 w-full" v-for="(field, index) in resourceForm" :key="index">
                <h3 v-if="index !== 0" class="font-semibold text-secondhand-orange-300 mt-2">Edukasi {{ (index +
                    1)
                }}
                </h3>
                <h3 v-else class="font-semibold text-secondhand-orange-300 mt-2">Edukasi</h3>
                <!-- <div class="flex">
                    <div class="w-full md:w-5/12">
                        <InputLabel for="category" value="Kategori" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm[index].category" mode="single" placeholder="Kategori"
                                :object="true" :options="categoryList" label="display" valueProp="code" track-by="code"
                                class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div> -->
                <div class="flex mt-3 space-x-2">
                    <div class="w-full md:w-6/12">
                        <InputLabel for="performedPeriodStart" value="Edukasi Dimulai" />
                        <div class="flex pt-1">
                            <VueDatePicker v-model="resourceForm[index].performedPeriodStart"
                                class="border-[1.5px] border-neutral-grey-0 rounded-xl" required></VueDatePicker>
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div class="w-full md:w-6/12">
                        <InputLabel for="performedPeriodEnd" value="Edukasi Selesai" />
                        <div class="flex pt-1">
                            <VueDatePicker v-model="resourceForm[index].performedPeriodEnd"
                                class="border-[1.5px] border-neutral-grey-0 rounded-xl" required></VueDatePicker>
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="note" value="Edukasi" />
                        <div class="flex">
                            <TextArea v-model="resourceForm[index].note" id="note" type="text"
                                class="text-sm mt-1 block w-full" placeholder="Edukasi yang diberikan" required></TextArea>
                            <DeleteButton v-if="index !== 0" @click="removeField(index)" />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
            </div>
            <div class="flex justify-between">
                <SecondaryButtonSmall type="button" @click="addField" class="teal-button-text">+ Tambah Edukasi
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
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import DeleteButton from '@/Components/DeleteButton.vue';
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
import SecondaryButtonSmall from '@/Components/SecondaryButtonSmall.vue';
import TextArea from '@/Components/TextArea.vue';
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
    performedPeriodStart: '',
    performedPeriodEnd: '',
    note: ''
}]);

const addField = () => {
    let resourceFormData = {
        performedPeriodStart: '',
        performedPeriodEnd: '',
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
                        "system": "http://snomed.info/sct",
                        "code": "409073007",
                        "display": "Education"
                    }
                ],
                "text": "Education"
            },
            "code": {
                "coding": [
                    {
                        "system": "http://snomed.info/sct",
                        "code": "84635008",
                        "display": "Disease process or condition education "
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