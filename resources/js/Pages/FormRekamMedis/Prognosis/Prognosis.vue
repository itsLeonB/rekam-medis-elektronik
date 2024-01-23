<template>
    <div>
        <form @submit.prevent="submit">
            <div class="my-2 w-full">
                <h3 class="font-semibold text-secondhand-orange-300 mt-2">Prognosis</h3>
                <div class="flex space-x-2">
                    <div class="w-full md:w-7/12">
                        <InputLabel for="description" value="Deskripsi" />
                        <div class="flex">
                            <TextInput v-model="resourceForm.description" id="description" type="text"
                                class="text-sm mt-1 block w-full" placeholder="Deskripsi" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div class="w-full md:w-5/12">
                        <InputLabel for="investigation" value="Investigasi" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm.investigation" mode="single" placeholder="Investigasi"
                                :object="true" :options="investigationList" label="display" valueProp="code" track-by="code"
                                class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <!-- <div class="flex mt-3">
                    <div class="w-full md:w-full">
                        <InputLabel for="problem" value="Problem" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm.problem" mode="single" placeholder="Problem" :object="true"
                                :options="problemList" label="display" valueProp="code" track-by="code" class="mt-1"
                                :classes="combo_classes" @click="getProblemList" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div> -->
                <div class="flex mt-3 space-x-2">
                    <div class="w-full md:w-7/12">
                        <InputLabel for="finding" value="Temuan" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm.finding" mode="single" placeholder="Temuan"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="1000"
                                :searchable="true" :options="searchicd10" label="label" valueProp="code" track-by="code"
                                class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div class="w-full md:w-5/12">
                        <InputLabel for="prognosisCodeableConcept" value="Hasil Prognosis" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm.prognosisCodeableConcept" mode="single" placeholder="Problem"
                                :object="true" :options="prognosisCodeableConceptList" label="display" valueProp="code"
                                track-by="code" class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="summary" value="Ringkasan" />
                        <div class="flex">
                            <TextInput v-model="resourceForm.summary" id="summary" type="text"
                                class="text-sm mt-1 block w-full" placeholder="Ringkasan" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
            </div>
            <div class="flex justify-between">
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
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
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
    description: '',
    problem: null,
    summary: '',
    finding: null,
    investigation: null,
    prognosisCodeableConcept: null,
}]);

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);

const submit = () => {
    const currentTime = new Date().toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '');
    const submitResource = {
        "resourceType": "ClinicalImpression",
        "status": "completed",
        "description": resourceForm.value.description,
        "subject": props.subject_reference,
        "encounter": props.encounter_reference,
        "effectiveDateTime": currentTime,
        "date": currentTime,
        "assessor": props.practitioner_reference,
        "investigation": [
            {
                "code": {
                    "coding": [
                        {
                            "system": resourceForm.value.investigation.system,
                            "code": resourceForm.value.investigation.code,
                            "display": resourceForm.value.investigation.display
                        }
                    ]
                }
            }
        ],
        "summary": resourceForm.value.summary,
        "finding": [
            {
                "itemCodeableConcept": {
                    "coding": [
                        {
                            "system": resourceForm.value.finding.system,
                            "code": resourceForm.value.finding.code,
                            "display": resourceForm.value.finding.display_en
                        }
                    ]
                }
            }
        ],
        "prognosisCodeableConcept": [
            {
                "coding": [
                    {
                        "system": resourceForm.value.prognosisCodeableConcept.system,
                        "code": resourceForm.value.prognosisCodeableConcept.code,
                        "display": resourceForm.value.prognosisCodeableConcept.display
                    }
                ]
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

const prognosisCodeableConceptList = ref(null);
const getPrognosisCodeableConceptList = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'ClinicalImpression',
            'attribute': 'prognosisCodeableConcept'
        }
    });
    prognosisCodeableConceptList.value = data;
};

// const problemList = ref(null);
// const getProblemList = async () => {
//     const { data } = await axios.get(route('terminologi.get'), {
//         params: {
//             'resourceType': 'ClinicalImpression',
//             'attribute': 'prognosisCodeableConcept'
//         }
//     });
//     problemList.value = data;
// };

const investigationList = ref(null);
const getInvestigationList = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'ClinicalImpressionInvestigation',
            'attribute': 'code'
        }
    });
    investigationList.value = data;
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
    getPrognosisCodeableConceptList();
    getInvestigationList();
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