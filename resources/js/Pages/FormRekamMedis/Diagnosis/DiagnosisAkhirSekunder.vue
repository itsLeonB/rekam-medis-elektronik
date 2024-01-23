<template>
    <div>
        <form @submit.prevent="submit">
            <div class="my-2 w-full">
                <h3 class="font-semibold text-secondhand-orange-300 mt-2">Diagnosa Sekunder/Banding</h3>
                <div class="flex">
                    <div class="w-full md:w-7/12 mr-2">
                        <InputLabel for="diagnosa" value="Diagnosa" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm.code" mode="single" placeholder="Diagnosa"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="400"
                                :searchable="true" :options="searchicd10" label="label" valueProp="code" track-by="code"
                                class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div class="w-full md:w-5/12">
                        <InputLabel for="clinical_status" value="Clinical Status" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm.clinicalStatus" mode="single" placeholder="Status"
                                :object="true" :options="clinicalStatusList" label="display" valueProp="code"
                                track-by="code" class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full md:w-5/12">
                        <InputLabel for="verificationStatus" value="Verification Status" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm.verificationStatus" mode="single" placeholder="Status"
                                :object="true" :options="verificationStatusList" label="display" valueProp="code"
                                track-by="code" class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="note" value="Keterangan" />
                        <div class="flex">
                            <TextInput v-model="resourceForm.note" id="note" type="text" class="text-sm mt-1 block w-full"
                                placeholder="Keterangan" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
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
import TextInput from '@/Components/TextInput.vue';
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
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


const resourceForm = ref({
    code: null,
    clinicalStatus: null,
    verificationStatus: null,
    note: ''
});

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);

const submit = () => {
    resourceForm.value.clinicalStatus = resourceForm.value.clinicalStatus ? (({ definition, ...rest }) => rest)(resourceForm.value.clinicalStatus) : resourceForm.value.clinicalStatus;
    const currentTime = new Date().toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '');

    const submitResource = {
        "resourceType": "Condition",
        "clinicalStatus": {
            "coding": [resourceForm.value.clinicalStatus]
        },
        "category": [
            {
                "coding": [
                    {
                        "system": "http://terminology.hl7.org/CodeSystem/condition-category",
                        "code": "encounter-diagnosis",
                        "display": "Encounter Diagnosis"
                    }
                ]
            }
        ],
        "code": {
            "coding": [
                {
                    "system": resourceForm.value.code.system,
                    "code": resourceForm.value.code.code,
                    "display": resourceForm.value.code.display_en
                }
            ]
        },
        "subject": props.subject_reference,
        "encounter": props.encounter_reference,
        "onsetDateTime": currentTime,
        "recordedDate": currentTime,
        "note": [
            {
                "text": resourceForm.value.note
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

const verificationStatusList = ref(null);
const getverificationStatusList = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'Condition',
            'attribute': 'verificationStatus'
        }
    });
    verificationStatusList.value = data;
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
    getverificationStatusList();
}
);


</script>