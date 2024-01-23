<template>
    <div>
        <form>
            <div class="my-2 w-full">
                <div class="flex">
                    <div class="w-full md:w-12/12">
                        <InputLabel for="text" value="Tekanan Darah - Sistol (Tangan Kanan)" />
                        <div class="flex items-center">
                            <TextInput v-model="resourceForm.interpretation" id="text" type="text"
                                class="text-sm mt-1 mr-3 block w-4/6" placeholder="Interpretasi" required />
                            <TextInput v-model="resourceForm.text" id="text" type="number"
                                class="text-sm mt-1 mr-3 block w-1/6" placeholder="Hasil" required />
                            <span class="text-sm w-1/6">mm[Hg]</span>
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
            </div>
            <p v-if="successAlertVisible" class="text-sm text-original-teal-300">Sukses!</p>
            <p v-if="failAlertVisible" class="text-sm text-thirdouter-red-300">Gagal!</p>
        </form>
    </div>
</template>

<script setup>
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import axios from 'axios';
import { ref } from 'vue';

const props = defineProps({
    subject_reference: {
        type: Object,
        required: false
    },
    practitioner_reference: {
        type: Object,
        required: false
    },
    encounter_reference: {
        type: Object,
        required: true
    }
});

defineExpose({
    submit,
});

const resourceForm = ref({
    text: '',
    interpretation: ''
});

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);

function submit() {
    const currentTime = new Date().toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '');
    const submitResource = {
        "resourceType": "Observation",
        "status": "final",
        "category": [
            {
                "coding": [
                    {
                        "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                        "code": "vital-signs",
                        "display": "Vital Signs"
                    }
                ]
            }
        ],
        "code": {
            "coding": [
                {
                    "system": "http://loinc.org",
                    "code": "8480-6",
                    "display": "Systolic blood pressure"
                }
            ]
        },
        "subject": props.subject_reference,
        "performer": [props.practitioner_reference],
        "encounter": props.encounter_reference,
        "effectiveDateTime": currentTime,
        "issued": currentTime,
        "bodySite": {
            "coding": [
                {
                    "system": "http://snomed.info/sct",
                    "code": "368209003",
                    "display": "Right arm"
                }
            ]
        },
        "valueQuantity": {
            "value": parseInt(resourceForm.value.text),
            "unit": "mm[Hg]",
            "system": "http://unitsofmeasure.org",
            "code": "mm[Hg]"
        },
        "interpretation": [
            {
                "text": resourceForm.value.interpretation
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

</script>