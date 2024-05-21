<template>
    <div>
        <form @submit.prevent="submit">
            <div class="my-2 w-full">
                <h3 class="font-semibold text-secondhand-orange-300 mt-2">Cara Keluar</h3>
                <div class="flex">
                    <div class="w-full">
                        <InputLabel for="diagnosismasuk" value="Diagnosis Masuk" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm.diagnosismasuk" mode="single" placeholder="Diagnosis"
                                :object="true" :options="conditionList" label="codeDisplay" valueProp="id" track-by="id"
                                class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="diagnosisprimer" value="Diagnosis Primer/Kerja" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm.diagnosisprimer" mode="single" placeholder="Diagnosis"
                                :object="true" :options="conditionList" label="codeDisplay" valueProp="id" track-by="id"
                                class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="diagnosissekunder" value="Diagnosis Sekunder/Banding" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm.diagnosissekunder" mode="single" placeholder="Diagnosis"
                                :object="true" :options="conditionList" label="codeDisplay" valueProp="id" track-by="id"
                                class="mt-1" :classes="combo_classes" />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="dischargeDisposition" value="Cara Keluar" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm.dischargeDisposition" mode="single" placeholder="Diagnosis"
                                :object="true" :options="dischargeDispositionList" label="display" valueProp="code"
                                track-by="code" class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="note" value="Keterangan" />
                        <div class="flex">
                            <TextInput v-model="resourceForm.dischargeDispositiontext" id="note" type="text"
                                class="text-sm mt-1 block w-full" placeholder="Keterangan" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
            </div>
            <div class="flex justify-between">
                <SecondaryButtonSmall type="button" @click="muatData" class="teal-button-text">Muat data
                </SecondaryButtonSmall>
                <div class="mt-2 mr-3">
                    <MainButtonSmall type="submit" :is-loading="isLoading" class="teal-button text-original-white-0">Tutup Kunjungan
                    </MainButtonSmall>
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
import SecondaryButtonSmall from '@/Components/SecondaryButtonSmall.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';

const props = defineProps({
    encounter_satusehat_id: {
        type: String,
    },
});

const encounter = ref({});
const fetchEncounter = async () => {
    const { data } = await axios.get(route('local.encounter.show', { 'satusehat_id': props.encounter_satusehat_id }));
    encounter.value = data;
};

const conditionList = ref(null);
const getconditionList = async () => {
    const { data } = await axios.get(route('kunjungan.condition', { 'encounter_satusehat_id': props.encounter_satusehat_id }));
    conditionList.value = data.diagnosis.map(diagnosis => ({
        id: diagnosis.id,
        codeDisplay: diagnosis.code.coding[0].display
    }));
};

const dischargeDispositionList = ref(null);
const getdischargeDispositionList = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'EncounterHospitalization',
            'attribute': 'dischargeDisposition'
        }
    });
    dischargeDispositionList.value = data;
};

const muatData = () => {
    fetchEncounter();
    getconditionList();
};

const resourceForm = ref({
    diagnosismasuk: null,
    diagnosisprimer: null,
    diagnosissekunder: null,
    dischargeDisposition: null,
    dischargeDispositiontext: '',
});

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);
const isLoading = ref(false);

const submit = async () => {
    isLoading.value = true;
    const currentTime = new Date().toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '');
    encounter.value.status = 'finished';
    encounter.value.period.end = currentTime;
    encounter.value.statusHistory[encounter.value.statusHistory.length - 1].period.end = currentTime;
    encounter.value.location[encounter.value.location.length - 1].period.end = currentTime;
    encounter.value.statusHistory.push({
        "status": 'finished',
        "period": {
            "start": currentTime,
            "end": currentTime,
        }
    });
    encounter.value.diagnosis = [];
    encounter.value.hospitalization = {
        "dischargeDisposition": {
            "coding": [
                {
                    "system": resourceForm.value.dischargeDisposition.system,
                    "code": resourceForm.value.dischargeDisposition.code,
                    "display": resourceForm.value.dischargeDisposition.display
                }
            ],
            "text": resourceForm.value.dischargeDispositiontext
        }
    }

    if (resourceForm.value.diagnosismasuk !== null) {
        encounter.value.diagnosis.push({
            "condition": {
                "reference": "Condition/" + resourceForm.value.diagnosismasuk.id,
                "display": resourceForm.value.diagnosismasuk.codeDisplay
            },
            "use": {
                "coding": [
                    {
                        "system": "http://terminology.hl7.org/CodeSystem/diagnosis-role",
                        "code": "AD",
                        "display": "Admission diagnosis"
                    }
                ]
            }
        });
    };

    if (resourceForm.value.diagnosisprimer !== null) {
        encounter.value.diagnosis.push({
            "condition": {
                "reference": "Condition/" + resourceForm.value.diagnosisprimer.id,
                "display": resourceForm.value.diagnosisprimer.codeDisplay
            },
            "use": {
                "coding": [
                    {
                        "system": "http://terminology.hl7.org/CodeSystem/diagnosis-role",
                        "code": "DD",
                        "display": "Discharge diagnosis"
                    }
                ]
            },
            "rank": 1
        });
    };

    if (resourceForm.value.diagnosissekunder !== null) {
        encounter.value.diagnosis.push({
            "condition": {
                "reference": "Condition/" + resourceForm.value.diagnosissekunder.id,
                "display": resourceForm.value.diagnosissekunder.codeDisplay
            },
            "use": {
                "coding": [
                    {
                        "system": "http://terminology.hl7.org/CodeSystem/diagnosis-role",
                        "code": "DD",
                        "display": "Discharge diagnosis"
                    }
                ]
            },
            "rank": 2
        });
    };

    axios.put(route('integration.update', {
        resourceType: 'Encounter',
        id: props.encounter_satusehat_id
    }), encounter.value)
        .then(response => {
            successAlertVisible.value = true;
            setTimeout(() => {
                successAlertVisible.value = false;
            }, 3000);
            isLoading.value = false;
        })
        .catch(error => {
            console.error('Error creating user:', error);
            failAlertVisible.value = true;
            setTimeout(() => {
                failAlertVisible.value = false;
            }, 3000);
            isLoading.value = false;
        });
};

onMounted(() => {
    getdischargeDispositionList();
})

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