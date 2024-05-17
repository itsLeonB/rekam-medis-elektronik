<template>
    <div>
        <form @submit.prevent="submit">
            <div class="my-2 w-full">
                <h3 class="font-semibold text-secondhand-orange-300 mt-2">Rencana Tindak Lanjut</h3>
                <div class="flex">
                    <div class="w-full md:w-6/12 mr-2">
                        <InputLabel for="tindakLanjut" value="Tindak Lanjut" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm.tindakLanjut" mode="single" placeholder="Tindak Lanjut"
                                :object="true" :options="tindakLanjutList" label="label" valueProp="id" track-by="id"
                                class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div class="w-full md:w-6/12">
                        <InputLabel for="priority" value="Prioritas" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm.priority" mode="single" placeholder="Kategori" :object="true"
                                :options="priorityList" label="code" valueProp="code" track-by="code" class="mt-1"
                                :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="codetext" value="Keterangan Tindak Lanjut" />
                        <div class="flex">
                            <TextInput v-model="resourceForm.codetext" id="codetext" type="text"
                                class="text-sm mt-1 block w-full" placeholder="Keterangan" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="occurrenceDateTime" value="Rencana Waktu" />
                        <div class="flex pt-1">
                            <VueDatePicker v-model="resourceForm.occurrenceDateTime"
                                class="border-[1.5px] border-neutral-grey-0 rounded-xl" required></VueDatePicker>
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="reasonCode" value="Alasan" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm.reasonCode" mode="single" placeholder="Diagnosa"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="1000"
                                :searchable="true" :options="searchicd10" label="label" valueProp="code" track-by="code"
                                class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="reasonCodetext" value="Keterangan Alasan" />
                        <div class="flex">
                            <TextInput v-model="resourceForm.reasonCodetext" id="reasonCodetext" type="text"
                                class="text-sm mt-1 block w-full" placeholder="Keterangan" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="patientInstruction" value="Instruksi Tindak Lanjut" />
                        <div class="flex">
                            <TextInput v-model="resourceForm.patientInstruction" id="patientInstruction" type="text"
                                class="text-sm mt-1 block w-full" placeholder="Instruksi" required />
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
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
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

const resourceForm = ref({
    priority: null,
    tindakLanjut: null,
    codetext: '',
    occurrenceDateTime: '',
    reasonCode: null,
    reasonCodetext: '',
    patientInstruction: '',
});

const tindakLanjutList = [
    { "id": "rawatinap", "label": "Rawat Inap" },
    { "id": "kontrol", "label": "Kontrol" },
    { "id": "rujukan", "label": "Rujukan" }];

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);

const submit = () => {
    const currentTime = new Date().toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '');
    const submitResource = ref({
        "resourceType": "ServiceRequest",
        "status": "active",
        "intent": "original-order",
        "priority": resourceForm.value.priority.code,
        "category": [],
        "code": {
            "coding": [],
            "text": resourceForm.value.codetext
        },
        "subject": props.subject_reference,
        "encounter": props.encounter_reference,
        "occurrenceDateTime": currentTime,
        "requester": props.practitioner_reference,
        "performer": [props.practitioner_reference],
        "reasonCode": [
            {
                "coding": [
                    {
                        "system": resourceForm.value.reasonCode.system,
                        "code": resourceForm.value.reasonCode.code,
                        "display": resourceForm.value.reasonCode.display_en
                    }
                ],
                "text": resourceForm.value.reasonCodetext
            }
        ],
        "patientInstruction": resourceForm.value.patientInstruction
    });

    if (resourceForm.value.tindakLanjut.id === "rawatinap") {
        submitResource.value.category.push({
            "coding": [
                {
                    "system": "http://snomed.info/sct",
                    "code": "3457005",
                    "display": "Patient referral"
                }
            ]
        });
        submitResource.value.code.coding.push({
            "system": "http://snomed.info/sct",
            "code": "737481003",
            "display": "Inpatient care management"
        });

    } else if (resourceForm.value.tindakLanjut.id === "kontrol") {
        submitResource.value.category.push({
            "coding": [
                {
                    "system": "http://snomed.info/sct",
                    "code": "306098008",
                    "display": "Self-referral"
                }
            ]
        });
        submitResource.value.category.push({
            "coding": [
                {
                    "system": "http://snomed.info/sct",
                    "code": "11429006",
                    "display": "Consultation"
                }
            ]
        });
        submitResource.value.code.coding.push({
            "system": "http://snomed.info/sct",
            "code": "185389009",
            "display": "Follow-up visit"
        });
        submitResource.value.locationCode = [];
        submitResource.value.locationCode.push({});
        submitResource.value.locationCode[0].coding = []
        submitResource.value.locationCode[0].coding.push({
            "system": "http://terminology.hl7.org/CodeSystem/v3-RoleCode",
            "code": "OF",
            "display": "Outpatient Facility"
        });

    } else if (resourceForm.value.tindakLanjut.id === "rujukan") {
        submitResource.value.category.push({
            "coding": [
                {
                    "system": "http://snomed.info/sct",
                    "code": "3457005",
                    "display": "Patient referral"
                }
            ]
        });
        submitResource.value.category.push({
            "coding": [
                {
                    "system": "http://snomed.info/sct",
                    "code": "11429006",
                    "display": "Consultation"
                }
            ]
        });
        submitResource.value.code.coding.push({
            "system": "http://snomed.info/sct",
            "code": "3457005",
            "display": "Patient referral"
        });
        submitResource.value.locationCode = [];
        submitResource.value.locationCode.push({});
        submitResource.value.locationCode[0].coding = []
        submitResource.value.locationCode[0].coding.push({
            "system": "http://terminology.hl7.org/CodeSystem/v3-RoleCode",
            "code": "AMB",
            "display": "Ambulance"
        });
    };

    axios.post(route('integration.store', { res_type: submitResource.value.resourceType }), submitResource.value)
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

const priorityList = ref(null);
const getpriorityList = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'ServiceRequest',
            'attribute': 'priority'
        }
    });
    priorityList.value = data;
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
    getpriorityList();
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