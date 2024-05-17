<template>
    <div>
        <form @submit.prevent="submit">
            <div class="my-2 w-full" v-for="(field, index) in riwayatPengobatan" :key="index">
                <h3 v-if="index !== 0" class="font-semibold text-secondhand-orange-300 mt-2">Riwayat Pengobatan {{ (index +
                    1)
                }}
                </h3>
                <h3 v-else class="font-semibold text-secondhand-orange-300 mt-2">Riwayat Pengobatan</h3>
                <div class="flex">
                    <div class="w-full md:w-7/12 mr-2">
                        <InputLabel for="riwayatPengobatan" value="Pengobatan" />
                        <div class="flex">
                            <Multiselect v-model="riwayatPengobatan[index].medicationCodeableConcept" mode="single"
                                placeholder="Obat" :filter-results="false" :object="true" :min-chars="1"
                                :resolve-on-load="false" :delay="1000" :searchable="true" :options="searchObat" label="name"
                                valueProp="kfa_code" track-by="kfa_code" class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div class="w-full md:w-5/12">
                        <InputLabel for="status" value="Status" />
                        <div class="flex">
                            <Multiselect v-model="riwayatPengobatan[index].status" mode="single" placeholder="Status"
                                :object="true" :options="statusList" label="display" valueProp="code" track-by="code"
                                class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full md:w-4/12 mr-2">
                        <InputLabel for="status" value="Alasan" />
                        <select id="status" v-model="riwayatPengobatan[index].alasanObat"
                            class="mt-1 text-sm p-2.5 block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                            <option value="keluhan">Keluhan</option>
                            <option value="alergi">Alergi</option>
                            <option value="penyakitPribadi">Penyakit Pribadi</option>
                            <option value="penyakitKeluarga">Penyakit Keluarga</option>
                        </select>
                        <InputError class="mt-1" />
                    </div>
                    <div v-if="riwayatPengobatan[index].alasanObat === 'keluhan'" class="w-full md:w-8/12">
                        <InputLabel for="reasonCode" value="Keluhan" />
                        <div class="flex">
                            <Multiselect v-model="riwayatPengobatan[index].reasonCode" mode="single" placeholder="Keluhan"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="1000"
                                :searchable="true" :options="searchKeluhan" label="display" valueProp="code" track-by="code"
                                class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div v-else-if="riwayatPengobatan[index].alasanObat === 'alergi'" class="w-full md:w-8/12">
                        <InputLabel for="reasonCode" value="Alergi" />
                        <div class="flex">
                            <Multiselect v-model="riwayatPengobatan[index].reasonCode" mode="single" placeholder="Alergi"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="1000"
                                :searchable="true" :options="searchRiwayatAlergi" label="display" valueProp="code"
                                track-by="code" class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div v-else-if="riwayatPengobatan[index].alasanObat === 'penyakitPribadi'" class="w-full md:w-8/12">
                        <InputLabel for="reasonCode" value="Riwayat Penyakit Pribadi" />
                        <div class="flex">
                            <Multiselect v-model="riwayatPengobatan[index].reasonCode" mode="single" placeholder="Penyakit"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="400"
                                :searchable="true" :options="searchRiwayatPenyakitPribadi" label="label" valueProp="code"
                                track-by="code" class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div v-else-if="riwayatPengobatan[index].alasanObat === 'penyakitKeluarga'" class="w-full md:w-8/12">
                        <InputLabel for="reasonCode" value="Riwayat Penyakit Keluarga" />
                        <div class="flex">
                            <Multiselect v-model="riwayatPengobatan[index].reasonCode" mode="single" placeholder="Penyakit"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="400"
                                :searchable="true" :options="searchRiwayatPenyakitKeluarga" label="label" valueProp="code"
                                track-by="code" class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full md:w-5/12  mr-2">
                        <InputLabel for="category" value="Kategori" />
                        <div class="flex">
                            <Multiselect v-model="riwayatPengobatan[index].category" mode="single" placeholder="Status"
                                :object="true" :options="categoryList" label="display" valueProp="code" track-by="code"
                                class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div class="w-full md:w-7/12">
                        <InputLabel for="effectiveDateTime" value="Waktu" />
                        <div class="flex pt-1">
                            <VueDatePicker v-model="riwayatPengobatan[index].effectiveDateTime"
                                class="border-[1.5px] border-neutral-grey-0 rounded-xl" required></VueDatePicker>
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="dosage" value="Dosis Obat" />
                        <div class="flex">
                            <TextInput v-model="riwayatPengobatan[index].dosageText" id="dosage" type="text"
                                class="text-sm mt-1 block w-full" placeholder="Dosis Obat" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="note" value="Keterangan" />
                        <div class="flex">
                            <TextInput v-model="riwayatPengobatan[index].note" id="note" type="text"
                                class="text-sm mt-1 block w-full" placeholder="Keterangan" required />
                            <DeleteButton v-if="index !== 0" @click="removeField(index)" />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
            </div>
            <div class="flex justify-between">
                <SecondaryButtonSmall type="button" @click="addField" class="teal-button-text">+ Tambah Riwayat Penyakit
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

const riwayatPengobatan = ref([{
    alasanObat: 'keluhan',
    status: '',
    category: '',
    medicationCodeableConcept: '',
    reasonCode: '',
    effectiveDateTime: '',
    dosageText: '',
    note: ''
}]);

const addField = () => {
    let riwayatPengobatanData = {
        alasanObat: 'keluhan',
        status: '',
        category: '',
        medicationCodeableConcept: '',
        reasonCode: '',
        effectiveDateTime: '',
        dosageText: '',
        note: ''
    };
    riwayatPengobatan.value.push(riwayatPengobatanData);
};

const removeField = (index) => {
    riwayatPengobatan.value.splice(index, 1);
};

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);

const submit = () => {
    const currentTime = new Date().toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '');
    riwayatPengobatan.value.forEach(item => {
        item.reasonCode = item.reasonCode ? (({ label, ...rest }) => rest)(item.reasonCode) : item.reasonCode;
        item.category = item.category ? (({ definition, ...rest }) => rest)(item.category) : item.category;

        const riwayatPengobatanResource = {
            "resourceType": "MedicationStatement",
            "status": item.status.code,
            "category": {
                "coding": [item.category]
            },
            "medicationCodeableConcept": {
                "coding": [
                    {
                        "system": "http://sys-ids.kemkes.go.id/kfa",
                        "code": item.medicationCodeableConcept.kfa_code,
                        "display": item.medicationCodeableConcept.name
                    }
                ]
            },
            "subject": props.subject_reference,
            "dosage": [
                {
                    "text": item.dosageText
                }
            ],
            "reasonCode": [
                {
                    "coding": [item.reasonCode]
                }
            ],
            "effectiveDateTime": new Date(item.effectiveDateTime).toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, ''),
            "dateAsserted": currentTime,
            "informationSource": props.subject_reference,
            "context": props.encounter_reference,
            "note": [
                {
                    "text": item.note
                }
            ]
        };

        axios.post(route('integration.store', { res_type: riwayatPengobatanResource.resourceType }), riwayatPengobatanResource)
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

const searchObat = async (query) => {
    const { data } = await axios.get(route('terminologi.medication'), {
        params: {
            'page': 1,
            'size': 10,
            'product_type': 'farmasi',
            'keyword': query
        }
    });
    const originalData = data.items.data;
    return originalData;
};

const statusList = ref(null);
const getstatusList = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'MedicationStatement',
            'attribute': 'status'
        }
    });
    statusList.value = data;
};


const categoryList = ref(null);
const getCategoryList = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'MedicationStatement',
            'attribute': 'category'
        }
    });
    categoryList.value = data;
};

const searchKeluhan = async (query) => {
    const { data } = await axios.get(route('terminologi.condition.keluhan', { 'search': query }));
    const originalData = data;
    for (const key in originalData) {
        const currentObject = originalData[key];
        const label = `${currentObject.display} | Code: ${currentObject.code}`;
        currentObject.label = label;
    }
    return originalData;
};

const searchRiwayatAlergi = async (query) => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'AllergyIntolerance',
            'attribute': 'code',
            'search': query
        }
    });
    const originalData = data;
    for (const key in originalData) {
        const currentObject = originalData[key];
        const label = `${currentObject.display} | Code: ${currentObject.code}`;
        currentObject.label = label;
    };
    return originalData;
};

const searchRiwayatPenyakitPribadi = async (query) => {
    const { data } = await axios.get(route('terminologi.condition.riwayat-pribadi', { 'search': query }));
    const originalData = data;
    for (const key in originalData) {
        const currentObject = originalData[key];
        const label = `${currentObject.display} | Code: ${currentObject.code}`;
        currentObject.label = label;
    }
    return originalData;
};

const searchRiwayatPenyakitKeluarga = async (query) => {
    const { data } = await axios.get(route('terminologi.condition.riwayat-keluarga', { 'search': query }));
    const originalData = data;
    for (const key in originalData) {
        const currentObject = originalData[key];
        const label = `${currentObject.display} | Code: ${currentObject.code}`;
        currentObject.label = label;
    }
    return originalData;
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
    getstatusList();
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