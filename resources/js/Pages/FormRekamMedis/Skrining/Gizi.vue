<template>
    <div>
        <form @submit.prevent="submit">
            <div class="my-2 w-full">
                <div class="flex flex-col space-y-5">
                    <div class="w-full md:w-12/12">
                        <InputLabel for="pertanyaansatu" value="1. Apakah ada  penurunan BB dalam waktu 6 bulan terakhir?" />
                        <div class="flex">
                            <select id="pertanyaansatu" v-model="resourceForm.pertanyaansatu" required
                                class="text-sm mt-1 block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                                <option value='false'>Tidak</option>
                                <option value='true'>Ya</option>
                            </select>
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div class="w-full md:w-12/12">
                        <InputLabel for="pertanyaandua"
                            value="2. Apakah ada penurunan asupan makanan karena nafsu makan berkurang?" />
                        <div class="flex">
                            <select id="pertanyaandua" v-model="resourceForm.pertanyaandua" required
                                class="text-sm mt-1 block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                                <option value='false'>Tidak</option>
                                <option value='true'>Ya</option>
                            </select>
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div class="w-full md:w-12/12">
                        <InputLabel for="pertanyaantiga" value="3. Apakah mengalami gejala gastrointestinal (seperti mual, muntah, diare, anorexia)?" />
                        <div class="flex">
                            <select id="pertanyaantiga" v-model="resourceForm.pertanyaantiga" required
                                class="text-sm mt-1 block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                                <option value='false'>Tidak</option>
                                <option value='true'>Ya</option>
                            </select>
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div class="w-full md:w-12/12">
                        <InputLabel for="pertanyaanempat"
                            value="4. Apakah memiliki faktor pemberat (komorbid)?" />
                        <div class="flex">
                            <select id="pertanyaanempat" v-model="resourceForm.pertanyaanempat" required
                                class="text-sm mt-1 block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                                <option value='false'>Tidak</option>
                                <option value='true'>Ya</option>
                            </select>
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div class="w-full md:w-12/12">
                        <InputLabel for="pertanyaanlima"
                            value="5. Apakah ada penurunan kapasitas fungsional?" />
                        <div class="flex">
                            <select id="pertanyaanlima" v-model="resourceForm.pertanyaanlima" required
                                class="text-sm mt-1 block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                                <option value='false'>Tidak</option>
                                <option value='true'>Ya</option>
                            </select>
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
    pertanyaansatu: false,
    pertanyaandua: false,
    pertanyaantiga: false,
    pertanyaanempat: false,
    pertanyaanlima: false,
});

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);

const submit = () => {
    const currentTime = new Date().toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '');
    const submitResource = {
        "resourceType": "QuestionnaireResponse",
        "questionnaire": "https://fhir.kemkes.go.id/Questionnaire/Q0008",
        "status": "completed",
        "subject": props.subject_reference,
        "encounter": props.encounter_reference,
        "authored": currentTime,
        "author": props.practitioner_reference,
        "source": props.subject_reference,
        "item": [
            {
                "linkId": "2",
                "text": "Gizi",
                "item": [
                    {
                        "linkId": "2.1",
                        "text": "Apakah ada  penurunan BB dalam waktu 6 bulan terakhir?",
                        "answer": [
                            {
                                "valueBoolean": resourceForm.value.pertanyaansatu
                            }
                        ]
                    },
                    {
                        "linkId": "2.2",
                        "text": "Apakah ada penurunan asupan makanan karena nafsu makan berkurang?",
                        "answer": [
                            {
                                "valueBoolean": resourceForm.value.pertanyaandua
                            }
                        ]
                    },
                    {
                        "linkId": "2.3",
                        "text": "Apakah mengalami gejala gastrointestinal (seperti mual, muntah, diare, anorexia)?",
                        "answer": [
                            {
                                "valueBoolean": resourceForm.value.pertanyaantiga
                            }
                        ]
                    },
                    {
                        "linkId": "2.4",
                        "text": "Apakah memiliki faktor pemberat (komorbid)?",
                        "answer": [
                            {
                                "valueBoolean": resourceForm.value.pertanyaanempat
                            }
                        ]
                    },
                    {
                        "linkId": "2.5",
                        "text": "Apakah ada penurunan kapasitas fungsional?",
                        "answer": [
                            {
                                "valueBoolean": resourceForm.value.pertanyaanlima
                            }
                        ]
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

</script>