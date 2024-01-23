<template>
    <div>
        <form @submit.prevent="submit">
            <div class="my-2 w-full">
                <h3 class="font-semibold text-secondhand-orange-300 mt-2">Pemeriksaan Psikologis</h3>
                <div class="flex">
                    <div class="w-full md:w-12/12">
                        <InputLabel for="text" value="Hasil pemeriksaan" />
                        <div class="flex">
                            <TextInput v-model="resourceForm.text" id="text" type="text" class="text-sm mt-1 block w-full"
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
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
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
    },
});

const resourceForm = ref({
    text: '',
});

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);

const submit = () => {
    const currentTime = new Date().toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '');
    const pemeriksaanPsikologisResource = {
        "resourceType": "Observation",
        "status": "final",
        "category": [
            {
                "coding": [
                    {
                        "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                        "code": "survey",
                        "display": "Survey"
                    }
                ]
            }
        ],
        "code": {
            "coding": [
                {
                    "system": "http://loinc.org",
                    "code": "8693-4",
                    "display": "Mental Status"
                }
            ]
        },
        "subject": props.subject_reference,
        "performer": [props.practitioner_reference],
        "encounter": props.encounter_reference,
        "effectiveDateTime": currentTime,
        "issued": currentTime,
        "valueString": resourceForm.value.text
    };

    axios.post(route('integration.store', { res_type: pemeriksaanPsikologisResource.resourceType }), pemeriksaanPsikologisResource)
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