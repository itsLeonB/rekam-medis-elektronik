<template>
    <div class="flex flex-row justify-between">
        <p v-if="successAlert" class="text-md text-original-teal-300">
                Data created successfully
            </p>
        <div v-if="failAlert" class="text-sm text-thirdouter-red-300">
            {{ errorMessage }}
        </div>
    </div>
    
    <div class="flex flex-row justify-between">
        <h2 class="text-xl font-semibold text-secondhand-orange-300">Resep Obat</h2>
         <div class="flex justify-end mr-2">
            <Link :href="route('request-to-stock')" v-if="$page.props.auth.user.roles[0].name === 'poli-umum'" as="button"
                    class="mr-2 inline-flex px-4 py-1.5 border border-transparent rounded-md font-normal text-sm text-white teal-button transition ease-in-out duration-150 hover:shadow-lg">
                    Request Stok Obat
            </Link>
            <form @submit.prevent="ruleSubmit" class="flex items-center">
                <MainButtonSmall v-if="$page.props.auth.user.roles[0].name === 'poli-umum'" type="submit" class="teal-button text-original-white-0 rounded-md">Save Rule</MainButtonSmall>
            </form>
        </div>
       
    </div>
    
    <div>
        <form @submit.prevent="submit">
            <div class="my-2 w-full" v-for="(field, index) in resourceForm" :key="index">
                <h3 v-if="index !== 0" class="font-semibold text-secondhand-orange-300 mt-2">Data Obat {{ (index +
                    1)
                    }}
                </h3>
                <!-- <h3 v-else class="font-semibold text-secondhand-orange-300 mt-2">Data Obat</h3> -->
                <div class="flex">
                    <div class="w-full md:w-6/12 mr-2">
                        <InputLabel for="kodeObat" value="Obat" />
                        <Multiselect v-model="resourceForm[index].medicationReference" mode="single" placeholder="Obat"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="50"
                                :searchable="true" :options="searchMedication" label="label" valueProp="code"
                                track-by="code" class="mt-1" :classes="combo_classes" required />
                    
                    </div>
                     <div class="w-full md:w-6/12 mr-2">
                        <InputLabel for="status" value="Status Peresepan" />
                        <Multiselect v-model="resourceForm[index].status" mode="single" placeholder="Status"
                            :object="true" :options="medicationStatusRequest" label="display" valueProp="code" track-by="code"
                            class="mt-1" :classes="combo_classes" required />
                    </div>
                </div>
            
                <div class="flex mt-3">
                    <div class="w-full mr-2">
                        <InputLabel for="intent" value="Intent/Tujuan Peresepan" />
                        <Multiselect v-model="resourceForm[index].intent" mode="single" placeholder="Intent/Tujuan"
                            :object="true" :options="intentTypeList" label="display" valueProp="code" track-by="code"
                            class="mt-1" :classes="combo_classes" required />
                    </div>

                    <!-- <div class="w-full md:w-4/12 mr-2">
                        <InputLabel for="category" value="Kategori" />
                        <Multiselect v-model="resourceForm[index].category" mode="single" placeholder="Kateogori"
                                :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="50"
                                :searchable="true" :options="medicationReqCategory" label="definition" valueProp="code"
                                track-by="code" class="mt-1" :classes="combo_classes" required />
                    
                    </div> -->
                     <!-- <div class="w-full md:w-4/12 mr-2">
                        <InputLabel for="priority" value="Prioritas" />
                        <Multiselect v-model="resourceForm[index].priority" mode="single" placeholder="Prioritas"
                            :object="true" :options="medicationReqPriority" label="display" valueProp="code" track-by="code"
                            class="mt-1" :classes="combo_classes" required />
                    </div> -->
                </div>
                <div class="flex mt-3">
                    <h6 class="font-semibold text-secondhand-orange-200 mt-2">Instruksi Dosis
                    </h6>
                </div>
                <div class="flex mt-3">
                    <div class="w-full md:w-12/12">
                        <InputLabel for="frequency" value="Frekuensi/Interval" />
                        <div class="flex items-center">
                            <TextInput v-model="resourceForm[index].frequency" id="frequency" type="number"
                                    class="text-sm mt-1 mr-2 block w-1/6 px-3" placeholder="Value" />
                            <TextInput v-model="resourceForm[index].period" id="period" type="number"
                                    class="text-sm mt-1 mr-2 block w-1/6 px-3" placeholder="Periode" />
                    
                            <Multiselect v-model="resourceForm[index].periodUnit" mode="single" placeholder="Unit Periode"
                                :object="true" :options="medicationRequestPeriodUnit" label="display" valueProp="code"
                                track-by="code" class="mt-1" :classes="combo_classes" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-1">
                    <div class="w-full">
                        <InputLabel for="text" value="Instruksi" />
                        <div class="flex">
                            <TextArea v-model="resourceForm[index].text" id="text" type="text"
                                class="text-sm mt-1 block w-full" placeholder="Instruksi Obat yang diberikan" required></TextArea>
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full mr-2">
                        <InputLabel for="route" value="Rute" />
                        <div class="flex">
                            <Multiselect v-model="resourceForm[index].route" mode="single" placeholder="Rute"
                                :object="true" :options="medicationRequestRoute" label="definition" valueProp="code"
                                track-by="code" class="mt-1" :classes="combo_classes" required />
                                <DeleteButton v-if="index !== 0" @click="removeField(index)" />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <h6 class="font-semibold text-secondhand-orange-200 mt-2">Dispense Request
                    </h6>
                </div>
                <div class="my-1 w-full">
                    <div class="flex mt-1">
                        <!-- <div class="w-full md:w-7/12 mr-2">
                                <InputLabel for="dispensevalue" value="Dispense Value" />
                                <div class="flex items-center">
                                    <TextInput v-model="resourceForm[index].dispensevalue" id="dispensevalue" type="number"
                                    class="text-sm mt-1 mr-2 block w-1/6 px-3" placeholder="Value" />
                                    <Multiselect v-model="resourceForm[index].duration" mode="single" placeholder="Durasi"
                                    :object="true" :options="medicationReqDuration" label="display" valueProp="code" track-by="code"
                                    class="mt-1" :classes="combo_classes" />
                                </div>
                                
                            </div> -->
                        <div class="w-full md:w-4/12 mr-2">
                            <InputLabel for="repeat" value="Perulangan" />
                            <TextInput v-model="resourceForm[index].repeat" id="repeat" type="number"
                                class="text-sm mt-1 mr-2 block w-1/6 px-3" placeholder="Value" required />
                        </div>
                  
                        <div class="w-full md:w-8/12 mr-2">
                            <InputLabel for="dispensevalue" value="Dispense QTY" />
                            <div class="flex items-center">
                                <TextInput v-model="resourceForm[index].dispenseQtyValue" id="dispenseQtyUnit" type="number"
                                class="text-sm mt-1 mr-2 block w-1/6 px-3" placeholder="Value" />
                                <Multiselect v-model="resourceForm[index].dispenseQtyUnit" mode="single" placeholder="Unit"
                                :object="true" :options="medicationReqQuantity" label="display" valueProp="code" track-by="code"
                                class="mt-1" :classes="combo_classes" />
                            </div>
                        </div>
                    </div>
                </div>
                
                 
                <!-- <div class="flex mt-3">
                    <div class="w-full md:w-6/12 mr-2">
                        <InputLabel for="performedPeriodStart" value="Validitas Dimulai" />
                        <div class="flex pt-1">
                            <VueDatePicker v-model="resourceForm[index].validityPeriodStart"
                                class="border-[1.5px] border-neutral-grey-0 rounded-xl" required></VueDatePicker>
                        </div>
                        <InputError class="mt-1" />
                    </div>
                    <div class="w-full md:w-6/12">
                        <InputLabel for="performedPeriodEnd" value="Validitas Selesai" />
                        <div class="flex pt-1">
                            <VueDatePicker v-model="resourceForm[index].validityPeriodEnd"
                                class="border-[1.5px] border-neutral-grey-0 rounded-xl" required></VueDatePicker>
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div> -->
                <!-- <div class="flex mt-3">
                    <div class="w-full md:w-6/12 mr-2">
                        <InputLabel for="qtyvalue" value="Kuantitas Value" />
                         <select id="qtyvalue" v-model="resourceForm[index].qtyvalue"
                                class="text-sm mt-1 block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                                <option value='1'>1</option>
                                <option value='2'>2</option>
                                <option value='3'>3</option>
                                <option value='4'>4</option>
                            </select>
                    </div>
                     <div class="w-full md:w-6/12 mr-2">
                        <InputLabel for="qty" value="Kuantitas" />
                        <Multiselect v-model="resourceForm[index].qty" mode="single" placeholder="Kuantitas"
                            :object="true" :options="medicationReqQuantity" label="display" valueProp="code" track-by="code"
                            class="mt-1" :classes="combo_classes" required />
                    </div>
                </div> -->
            </div>
            
            <div class="flex justify-between">
                <SecondaryButtonSmall type="button" @click="addField" class="teal-button-text">+ Tambah Obat
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
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import Multiselect from '@vueform/multiselect';
import Dropdown from '@/Components/Dropdown.vue';
import '@vueform/multiselect/themes/default.css';
import DeleteButton from '@/Components/DeleteButton.vue';
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
import SecondaryButtonSmall from '@/Components/SecondaryButtonSmall.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { Link, usePage} from '@inertiajs/vue3';

const props = defineProps({
    subject_reference: {
        type: Object,
        required: false
    },
    encounter_reference: {
        type: Object,
        required: true
    },
    requester: {
        type: Object,
        required: true
    },
    encounter_satusehat_id: {
        type: String,
    },
});
console.log(props.requester)
const resourceForm = ref([{
    medicationReference : [null],
    status: [null],
    intent: [null],
    // category: [null],
    // priority: [null],
    frequency: [null],
    period: [null],
    periodUnit: [null],
    route: [null],
    text: [null],
    // dispensevalue: [null],
    duration: [null],
    repeat: [null],
    dispenseQtyValue: [null],
    dispenseQtyUnit: [null],
}]);

const addField = () => {
    let resourceFormData = {
        medicationReference : [null],
        status: [null],
        intent: [null],
        // category: [null],
        // priority: [null],
        frequency: [null],
        period: [null],
        periodUnit: [null],
        route: [null],
        text: [null],
        // dispensevalue: [null],
        duration: [null],
        repeat: [null],
        dispenseQtyValue: [null],
        dispenseQtyUnit: [null],
    };
    resourceForm.value.push(resourceFormData);
};

const removeField = (index) => {
    resourceForm.value.splice(index, 1);
};

const organizationRef = ref(null);
const getorganizationRef = async () => {
    const { data } = await axios.get(route('form.ref.organization', {layanan: 'induk'}));
    organizationRef.value = data;
};
const successAlertVisible = ref(false);
const failAlertVisible = ref(false);
const errorMessage = ref('');
const submit = () => {
    resourceForm.value.forEach(item => {
        item.status = item.status ? (({ display, ...rest }) => rest)(item.status) : item.status;
        item.extension = item.extension ? (({ definition, ...rest }) => rest)(item.extension) : item.extension;
        // item.periodUnit = item.periodUnit ? (({ display, ...rest }) => rest)(item.periodUnit) : item.periodUnit;
        const dataResource = {
            "resourceType": "MedicationRequest",
            "identifier": [
                {
                    "system": "http://sys-ids.kemkes.go.id/prescription/d7c204fd-7c20-4c59-bd61-4dc55b78438c",
                    "use": "official",
                    "value": "123456788"
                },
                {
                    "system": "http://sys-ids.kemkes.go.id/prescription-item/d7c204fd-7c20-4c59-bd61-4dc55b78438c",
                    "use": "official",
                    "value": "123456788-1"
                }
            ], 
            "status": item.status.code,
            "intent": item.intent.code,
            // "category": [
            //     {
            //         "coding": [
            //             {
            //                 "system": "http://terminology.hl7.org/CodeSystem/medicationrequest-category",
            //                 "code": item.category.code,
            //                 "display": item.category.display
            //             }
            //         ]
            //     }
            // ],
            // "priority": item.priority.code,

            "medicationReference": {
                "reference": "Medication/" + item.medicationReference.id,
                "display": item.medicationReference.name
            },
            "subject": props.subject_reference,
            "encounter": props.encounter_reference,
            "requester": props.requester,
            "dosageInstruction": [
                {   
                    "text": item.text,
                    "timing": {
                        "repeat": {
                            "frequency": parseInt(item.frequency),
                            "period": parseInt(item.period),
                            "periodUnit": item.periodUnit.code,
                        }
                    },
                    "route": {
                        "coding": [
                            {
                                "system": "http://www.whocc.no/atc",
                                "code": item.route.code,
                                "display": item.route.display
                            }
                        ]
                    },
                }
            ],
            "dispenseRequest": {
                // "dispenseInterval": {
                //     "value": parseInt(item.dispensevalue),
                //     "unit": item.duration.unit,
                //     "system": "http://unitsofmeasure.org",
                //     "code": item.duration.code
                // },
                // "validityPeriod": {
                //     "start": new Date(item.validityPeriodStart).toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, ''),
                //     "end": new Date(item.validityPeriodEnd).toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, ''), 
                // },
                "numberOfRepeatsAllowed": parseInt(item.repeat),
                "quantity": {
                    "value":  parseInt(item.dispenseQtyValue),
                    "unit": item.dispenseQtyUnit.display,
                    "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                    "code": item.dispenseQtyUnit.code
                },
                "performer": {
                    "reference": "Organization/d7c204fd-7c20-4c59-bd61-4dc55b78438c"
                }
            }
        };
       
        axios.post(route('integration.store', { resourceType: dataResource.resourceType }), dataResource)
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
const successAlert = ref(false);
const failAlert = ref(false);
const ruleSubmit = async  () => {
   try {
       const response = await axios.get(route('ruleperesepan.store',{
            id: props.encounter_satusehat_id
        }));
        
       if (response.status === 201) {
            successAlert.value = true;
            failAlert.value = false;
            errorMessage.value = '';

            setTimeout(() => {
                successAlert.value = false;
            }, 3000); 
        } else if (response.status === 204) {
            successAlert.value = false;
            failAlert.value = true;
            errorMessage.value = 'Rule Peresepan Obat Sudah Ada';

            setTimeout(() => {
                failAlert.value = false;
            }, 3000);
        }

   } catch (error) {
        successAlert.value = false;
        failAlert.value = true;

        if (error.response && error.response.status === 500) {
            errorMessage.value = 'Failed to save resource: ' + (error.response.data.message || 'Server Error');
        } else {
            errorMessage.value = 'An error occurred while saving data';
        }
        setTimeout(() => {
            failAlert.value = false;
        }, 3000);
    }
};
const searchMedication = async (query) => {
    const { data } = await axios.get(route('search.medicationOrg', { 'search': query }));
   
    return data.map(item => {
        return {
          ...item,
          label: `${item.name} | Code: ${item.code}`
        };
      });
};


const medicationReqCategory = ref(null);
const getMedicationReqCategory = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'MedicationRequest',
            'attribute': 'category'
        }
    });
medicationReqCategory.value = data;
};


const medicationReqPriority = ref(null);
const getMedicationReqPriority = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'MedicationRequest',
            'attribute': 'priority'
        }
    });
medicationReqPriority.value = data;
};

const medicationStatusRequest = ref(null);
const getMedicationStatusRequest = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'MedicationRequest',
            'attribute': 'status',
        }
    });
medicationStatusRequest.value = data;
};

const intentTypeList = ref(null);
const getIntentTypeList = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'MedicationRequest',
            'attribute': 'intent'
        }
    });
    intentTypeList.value = data;
};

const categoryRequestList = ref(null);
const getCategoryRequestList = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'MedicationRequest',
            'attribute': 'category'
        }
    });
    categoryRequestList.value = data;
};


const reasonList = ref(null);
const getReasonList = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'MedicationRequest',
            'attribute': 'statusReason'
        }
    });
    reasonList.value = data;
};

const therapyTypeList = ref(null);
const getTherapyTypeList = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'MedicationRequest',
            'attribute': 'courseOfTherapyType'
        }
    });
    therapyTypeList.value = data;
};

const medicationRequestPeriodUnit = ref(null);
const getMedicationRequestPeriodUnit = async () =>{
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'TimingRepeat',
            'attribute': 'periodUnit'
        }
    });
    medicationRequestPeriodUnit.value = data;
};

const medicationRequestRoute = ref(null);
const getMedicationRequestRoute = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'Dosage',
            'attribute': 'route'
        }
    });
    medicationRequestRoute.value = data;
};

const medicationReqDuration = ref(null);
const getMedicationReqDuration = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'MedicationRequestDispenseRequst',
            'attribute': 'dispenseInterval'
        }
    });
    medicationReqDuration.value = data;
};

const medicationReqQuantity = ref(null);
const getMedicationReqQuantity = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'MedicationRequestDispenseRequst',
            'attribute': 'quantityUnit'
        }
    });
    medicationReqQuantity.value = data;
};

const expertSystem = ref(null);
const getExpertSystem = async () => {
    const {data} = await axios.get(route('ruleperesepan.show', {
            resourceType : 'Condition',
            id: 'e9315d39-f4c2-414c-b222-a5053326a24a'
    }));
    expertSystem.value = data;
    console.log(expertSystem.value);
};

onMounted(() => {
    getIntentTypeList();
    getCategoryRequestList();
    getReasonList();
    getTherapyTypeList();
    getMedicationStatusRequest();
    getMedicationRequestPeriodUnit();
    getMedicationRequestRoute();
    getMedicationReqPriority();
    getMedicationReqCategory();
    getorganizationRef();
    getMedicationReqDuration();
    getMedicationReqQuantity();
    getExpertSystem()
});

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