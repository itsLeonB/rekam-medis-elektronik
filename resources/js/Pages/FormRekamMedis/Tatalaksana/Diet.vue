<template>
    <div>
        <form @submit.prevent="submit">
            <div class="my-2 w-full">
                <h3 class="font-semibold text-secondhand-orange-300 mt-2">Edukasi Diet</h3>
                <div class="flex">
                    <div class="w-full">
                        <InputLabel for="title" value="Judul Edukasi Diet" />
                        <div class="flex">
                            <TextInput v-model="resourceForm.title" id="title" type="text" class="text-sm mt-1 block w-full"
                                placeholder="Judul edukasi yang diberikan" required></TextInput>
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="note" value="Edukasi Diet" />
                        <div class="flex">
                            <TextArea v-model="resourceForm.note" id="note" type="text" class="text-sm mt-1 block w-full"
                                placeholder="Edukasi yang diberikan" required></TextArea>
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
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
import TextArea from '@/Components/TextArea.vue';
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
    }
});

const resourceForm = ref({
    title: '',
    note: ''
});

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);

const organizationRef = ref(null);
const getorganizationRef = async () => {
    const { data } = await axios.get(route('form.ref.organization', { layanan: 'induk' }));
    organizationRef.value = data;
};

const submit = () => {
    const currentTime = new Date().toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '');
    const submitResource = {
        "resourceType": "Composition",
        "status": "final",
        "type": {
            "coding": [
                {
                    "system": "http://loinc.org",
                    "code": "18842-5",
                    "display": "Discharge summary"
                }
            ]
        },
        "category": [
            {
                "coding": [
                    {
                        "system": "http://loinc.org",
                        "code": "LP173421-1",
                        "display": "Report"
                    }
                ]
            }
        ],
        "subject": props.subject_reference,
        "encounter": props.encounter_reference,
        "date": currentTime,
        "author": [props.practitioner_reference],
        "title": resourceForm.value.title,
        "custodian": organizationRef.value,
        "section": [
            {
                "code": {
                    "coding": [
                        {
                            "system": "http://loinc.org",
                            "code": "42344-2",
                            "display": "Discharge diet (narrative)"
                        }
                    ]
                },
                "text": {
                    "status": "additional",
                    "div": resourceForm.value.note
                }
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

onMounted(() => {
    getorganizationRef();
})

</script>