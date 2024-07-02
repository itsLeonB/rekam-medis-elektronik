<template>
    <div>
        <form @submit.prevent="submit">
        <div class="my-2 w-full" v-for="(field, index) in riwayatAlergi" :key="index">
            <h3 class="font-semibold text-secondhand-orange-300 mt-2" v-if="index === 0">Riwayat Alergi</h3>
            <h3 class="font-semibold text-secondhand-orange-300 mt-2" v-else>Riwayat Alergi {{ index + 1 }}</h3>

            <div class="flex">
                <div class="w-full md:w-6/12 mr-2">
                    <InputLabel for="category" value="Kategori Alergi" />
                    <div class="flex">
                        <Multiselect v-model="riwayatAlergi[index].category" mode="single" placeholder="Kategori Alergi"
                            :object="true" :options="categoryList" label="code" valueProp="code" track-by="code"
                            class="mt-1" :classes="combo_classes" required />
                    </div>
                    <InputError class="mt-1" />
                </div>

                <template v-if="riwayatAlergi[index].category && riwayatAlergi[index].category.code === 'medication'">
                    <div class="w-full md:w-6/12">
                        <InputLabel for="riwayatAlergiObat" value="Riwayat Alergi" />
                        <Multiselect v-model="riwayatAlergi[index].code" mode="single" placeholder="Obat"
                                    :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="1000"
                                    :searchable="true" :options="searchMedication" label="name" valueProp="kfa_code"
                                    track-by="kfa_code" class="mt-1" :classes="combo_classes" required />
                    </div>
                </template>

                <template v-else>
                    <div class="w-full md:w-6/12">
                        <InputLabel for="riwayatAlergi" value="Riwayat Alergi" />
                        <div class="flex">
                            <Multiselect v-model="riwayatAlergi[index].code" mode="single" placeholder="Alergi"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="400"
                                :searchable="true" :options="searchRiwayatAlergi" label="label" valueProp="code"
                                track-by="code" class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </template>
            </div>

            <div class="flex mt-3">
                <div class="w-full md:w-6/12 mr-2">
                    <InputLabel for="verification_status" value="Verification Status" />
                    <div class="flex">
                        <Multiselect v-model="riwayatAlergi[index].verification" mode="single" placeholder="Status"
                            :object="true" :options="verificationList" label="display" valueProp="code" track-by="code"
                            class="mt-1" :classes="combo_classes" required />
                    </div>
                    <InputError class="mt-1" />
                </div>
                <div class="w-full md:w-6/12">
                    <InputLabel for="clinical_status" value="Clinical Status" />
                    <div class="flex">
                        <Multiselect v-model="riwayatAlergi[index].clinical" mode="single" placeholder="Status"
                            :object="true" :options="clinicalStatusList" label="display" valueProp="code"
                            track-by="code" class="mt-1" :classes="combo_classes" required />
                    </div>
                    <InputError class="mt-1" />
                </div>
            </div>

            <div class="flex mt-3">
                <div class="w-full">
                    <InputLabel for="codeText" value="Keterangan" />
                    <div class="flex">
                        <TextInput v-model="riwayatAlergi[index].codeText" id="codeText" type="text"
                            class="text-sm mt-1 block w-full" required placeholder="Keterangan" />
                        <DeleteButton v-if="index !== 0" @click="removeField(index)" />
                    </div>
                    <InputError class="mt-1" />
                </div>
            </div>
        </div>

        <div class="flex justify-between mt-3">
            <SecondaryButtonSmall type="button" @click="addField" class="teal-button-text">+ Tambah Riwayat Alergi</SecondaryButtonSmall>
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

const riwayatAlergi = ref([{
    code: [null],
    codeText: '',
    clinical: [null],
    verification: [null],
    category: [null]
}]);

const addField = () => {
    let riwayatAlergiData = {
        code: [null],
        codeText: '',
        clinical: [null],
        verification: [null],
        category: [null]
    };
    riwayatAlergi.value.push(riwayatAlergiData);
};

const removeField = (index) => {
    riwayatAlergi.value.splice(index, 1);
};

const submit = () => {
    const currentTime = new Date().toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '');;
    riwayatAlergi.value.forEach(item => {
        item.code = item.code ? (({ label, ...rest }) => rest)(item.code) : item.code;
        item.clinical = item.clinical ? (({ definition, ...rest }) => rest)(item.clinical) : item.clinical;
        item.verification = item.verification ? (({ definition, ...rest }) => rest)(item.verification) : item.verification;

        let codeCoding;
        if (item.category && item.category.code === 'medication') {
            // Jika kategori adalah 'medication', gunakan kfa_code dan name
            codeCoding = {
                "system": "http://sys-ids.kemkes.go.id/kfa",
                "code": item.code.kfa_code,
                "display": item.code.name
            };
        } else {
            // Jika kategori bukan 'medication', gunakan item.code dan item.codeText
            codeCoding = item.code;
        }

        const riwayatAlergiResource = {
            "resourceType": "AllergyIntolerance",
            
            "clinicalStatus": {
                "coding": [item.clinical]
            },
            "verificationStatus": {
                "coding": [item.verification]
            },
            "category": [item.category.code],
            "code": {
                "coding": [codeCoding],
                "text": item.codeText
            },
            "patient": props.subject_reference,
            "encounter": props.encounter_reference,
            "recordedDate": currentTime,
            "recorder": props.practitioner_reference
        };

        axios.post(route('integration.store', { resourceType: riwayatAlergiResource.resourceType }), riwayatAlergiResource)
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

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);

const searchRiwayatAlergi = async (query) => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'AllergyIntolerance',
            'attribute': 'code',
            'search': query
        }
    });
    console.log(data)
    const originalData = data;
    for (const key in originalData) {
        const currentObject = originalData[key];
        const label = `${currentObject.display} | Code: ${currentObject.code}`;
        currentObject.label = label;
    };
    return originalData;


};
const searchMedication = async (query) => {
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
}

const categoryList = ref(null);
const getCategoryList = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'AllergyIntolerance',
            'attribute': 'category'
        }
    });
    categoryList.value = data;
};

const verificationList = ref(null);
const getVerificationList = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'AllergyIntolerance',
            'attribute': 'verificationStatus'
        }
    });
    verificationList.value = data;
};

const clinicalStatusList = ref(null);
const getClinicalStatusList = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'AllergyIntolerance',
            'attribute': 'clinicalStatus'
        }
    });
    clinicalStatusList.value = data;
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
    getCategoryList();
    getVerificationList();
}
);


</script>