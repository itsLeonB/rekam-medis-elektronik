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
                        <InputLabel for="riwayatPengobatan" value="Riwayat Pengobatan" />
                        <div class="flex">
                            <Multiselect v-model="riwayatPengobatan[index].code" mode="single" placeholder="Obat"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="1000"
                                :searchable="true" :options="searchObat" label="name" valueProp="kfa_code"
                                track-by="kfa_code" class="mt-1" :classes="combo_classes" required />
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
                    <div class="w-full md:w-6/12 mr-2">
                        <InputLabel for="form" value="Bentuk Obat" />
                        <div class="flex">
                            <Multiselect v-model="riwayatPengobatan[index].form" mode="single" placeholder="Bentuk"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="1000"
                                :searchable="true" :options="searchForm" label="display" valueProp="code" track-by="code"
                                class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div class="w-full md:w-6/12">
                        <InputLabel for="medication_type" value="Tipe Obat" />
                        <div class="flex">
                            <Multiselect v-model="riwayatPengobatan[index].medType" mode="single" placeholder="Tipe"
                                :object="true" :options="medicationTypeList" label="display" valueProp="code"
                                track-by="code" class="mt-1" :classes="combo_classes" required />
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
        </form>
    </div>
</template>

<script setup>
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';
import DeleteButton from '@/Components/DeleteButton.vue';
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
import SecondaryButtonSmall from '@/Components/SecondaryButtonSmall.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
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
    code: [null],
    status: [null],
    form: [null],
    medType: [null]
}]);

const addField = () => {
    let riwayatPengobatanData = {
        code: [null],
        status: [null],
        form: [null],
        medType: [null]
    };
    riwayatPengobatan.value.push(riwayatPengobatanData);
};

const removeField = (index) => {
    riwayatPengobatan.value.splice(index, 1);
};

const submit = () => {
    riwayatPengobatan.value.forEach(item => {
        item.status = item.status ? (({ definition, ...rest }) => rest)(item.status) : item.status;
        item.form = item.form ? (({ definition, ...rest }) => rest)(item.form) : item.form;
        item.medType = item.medType ? (({ definition, ...rest }) => rest)(item.medType) : item.medType;

        const riwayatPengobatanResource = {
            "resourceType": "Medication",
            "code": {
                "coding": [
                    {
                        "system": "http://sys-ids.kemkes.go.id/kfa",
                        "code": item.code.kfa_code,
                        "display": item.code.name
                    }
                ]
            },
            "status": item.status.code,
            "form": {
                "coding": [item.form]
            },
            "extension": [
                {
                    "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType",
                    "valueCodeableConcept": {
                        "coding": [item.medType]
                    }
                }
            ]
        };

        console.log(riwayatPengobatanResource);
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
            'resourceType': 'Medication',
            'attribute': 'status'
        }
    });
    statusList.value = data;
};

const searchForm = async (query) => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'Medication',
            'attribute': 'form'
        }
    });
    const originalData = data;
    const searchResults = (query) => {
        const lowerQuery = query.toLowerCase();
        return originalData.filter(item => item.display.toLowerCase().includes(lowerQuery));
    };

    const filteredData = searchResults(query);
    return filteredData;
};

const medicationTypeList = ref(null);
const getMedicationTypeList = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'Medication',
            'attribute': 'medicationType'
        }
    });
    medicationTypeList.value = data;
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
    // getFormList();
    getMedicationTypeList();
}
);


</script>