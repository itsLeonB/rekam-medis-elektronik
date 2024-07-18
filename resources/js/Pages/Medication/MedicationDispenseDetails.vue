<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Medication Request - </title>
        </template>

        <div class="p-8 bg-original-white-0 overflow-hidden shadow sm:rounded-2xl mb-8">
            <div class="flex justify-between">
                <h1 class="mb-8 px-5 pt-3 text-2xl font-bold text-neutral-black-300">Detail Peresepan Obat</h1>
            </div>
            <!-- <Modal :show="confirmingUserDeletion" @close="closeModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Apakah yakin akan menghapus user ini?
                    </h2>
                    <div class="mt-6 flex justify-end">
                        <MainButton @click="closeModal" class="teal-button text-original-white-0"> Cancel </MainButton>

                        <MainButton class="ml-3 orange-button text-original-white-0" @click="deleteUser(user_id)">
                            Hapus User
                        </MainButton>
                    </div>
                </div>
            </Modal> -->
            <div class="relative overflow-x-auto mb-5">
                <table class="w-full text-base text-left text-neutral-grey-200 ">
                    <tbody>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/4">
                                Peminta resep
                            </th>
                            <td class="px-6 py-4 w-3/4">
                                {{ medication.requester }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/4">
                                Nama Obat
                            </th>
                            <td class="px-6 py-4 w-3/4">
                                {{ medication.medication }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/4">
                                Pasien
                            </th>
                            <td class="px-6 py-4 w-3/4">
                                {{ medication.patient }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/4">
                                Valid date
                            </th>
                            <td class="px-6 py-4 w-3/4">
                                {{ medication.validStart }} - {{ medication.validEnd }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/4">
                                Jumlah
                            </th>
                            <td class="px-6 py-4 w-3/4">
                                {{ medication.quantity }}&nbsp;{{ medication.uom }}
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
            <div class="buttons-submit">
                <MainButton class="mt-4 w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0"
                    @click.prevent="submit">Setujui</MainButton>
                <!-- <MainButton class="mt-4 w-full mb-3 mx-auto max-w-[284px] block orange-button text-original-white-0"
                    @click.prevent="submit">Tolak</MainButton> -->
            </div>
            <Modal :show="creationSuccessModal">
            <div class="p-6">
                <h2 class="text-lg text-center font-medium text-gray-900">
                    Data Obat berhasil ditambahkan. <br> Kembali ke halaman Obat.
                </h2>
                <div class="mt-6 flex justify-end">
                    <Link :href="route('medication.table')"
                        class="mx-auto mb-3 w-fit block justify-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Kembali </Link>
                </div>
            </div>
        </Modal>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import MainButton from '@/Components/MainButton.vue';
import Modal from '@/Components/Modal.vue';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    medication_dispense_id: {
        type: String,
    },
});

const medication_id = props.medication_dispense_id;

const creationSuccessModal = ref(false);
const medication = ref([]);

const form = useForm({
    _id: '',
    id_transaction:  '',
    id_medicine:  '',
    quantity: '',
    note: '',
});

const medicines = ref([]);
let quantityMax = ref(null); 

const searchMedicines = async () => {
    try {
        const response = await axios.get(route('getmedicine', { search: medication.value.medication }));
        medicines.value = response.data;
        
        console.log(medication.value.medication, medicines.value, medicines.value.find(medicine => medicine.name === medication.value.medication))
        updateQuantity();
    } catch (error) {
        console.error(error);
    }
};

const updateQuantity = () => {
    const selectedMedicine = medicines.value.find(medicine => medicine.name === medication.value.medication);
   

    if (selectedMedicine) {
        quantityMax.value = selectedMedicine.quantity;
        form.id_medicine = selectedMedicine._id;
        form.quantity = -(medication.value.quantity);
        form.id_transaction = `${new Date().getUTCDate()}` +`${new Date().getUTCMonth()}` + `${new Date().getUTCFullYear()} `+ `${new Date().getUTCSeconds()}` + `${new Date().getUTCMilliseconds()}`;
        form.note = 'Medication Dispense'
        console.log(selectedMedicine, form)
        if (form.quantity > quantityMax.value) {
            console.error(`Quantity tidak boleh lebih dari ${quantityMax.value}`);
            form.quantity = quantityMax.value;
        }
    }
};

const dispense = async () => {
    if (!form.id_medicine) {
        console.error('Obat tidak ada di stok');
        return;
    }

    if (form.quantity > quantityMax.value) {
        console.error(`Jumlah stok Obat tidak mencukupi ${quantityMax.value}`);
        return;
    }

    const routeName = 'medicinetransactions.store';
    const method = 'post';
    
    try {
        await form[method](route(routeName), {
            preserveScroll: true,
            onFinish: () => form.reset(),
        });
    } catch (error) {
        console.error('Gagal menyimpan transaksi:', error);
    }
};

const fetchMedication = async () => {
    const { data } = await axios.get(route('medicationDispense.show', { 'medicationReq_id': medication_id }));

    medication.value = data[0];
};

const medicationRequest = ref([]);

const fetchMedicationReq = async () => {
    const { data } = await axios.get(route('medicationDispense.show', { 'medicationReq_id': medication_id }));

    medicationRequest.value = data[1];
    console.log(medicationRequest.value)
};

const submit = () => {
    const med = medication.value
    console.log(med.medicationReferenceName);
    const formDataJson = {
        resourceType: 'MedicationDispense',
        identifier: [
            {
                system: 'http://sys-ids.kemkes.go.id/medication/d7c204fd-7c20-4c59-bd61-4dc55b78438c',
                use: 'official',
                value: '123456789'
            }
        ],
        authorizingPrescription: [
            {
                reference: "MedicationRequest/" + med.id
            }
        ],
        status: 'completed',
        medicationReference: {
            reference: med.medicationReferenceId,
            display: med.medicationReferenceName
        },
        subject: {
            reference: med.subjectId,
            display: med.subject
        },
       "context": {
       "reference": med.encounter
   },
        quantity: {
            system: "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
            unit: med.uom,
            code: med.uom_code,
            value: med.quantity
        },
        whenPrepared: new Date().toISOString(),
        whenHandedOver: new Date().toISOString(),
        performer: [
            {
                "actor": {
                    "reference": med.requesterId,
                    "display": med.requester
                }
            }
        ],
    };
    axios.post(route('integration.store', { resourceType: 'MedicationDispense' }), formDataJson)
        .then(response => {
            dispense();
            updateStatus();
            creationSuccessModal.value = true;
            setTimeout(() => {

            }, 3000);
        })
        .catch(error => {
            console.error('Error creating medication dispense:', error);
            // failAlertVisible.value = true;
            // setTimeout(() => {
            //     failAlertVisible.value = false;
            // }, 3000);
        });
};

const updateStatus = () => {
    const med = medicationRequest.value
    console.log(med.id);
    const { _id, status, ...remainingMed } = med;
    const formDataJson = {
      ...remainingMed,
      status: "completed"
    };
    axios.put(route('integration.update', { resourceType: 'MedicationRequest', id: med.id }), formDataJson)
        .catch(error => {
            console.error('Error updating medication request status:', error);
          
        });
};

onMounted(() => {
    fetchMedication();
    searchMedicines();
    fetchMedicationReq();
}
);

</script>

<style>
.buttons-submit {
    display: flex;
    justify-content: flex-end;
}
</style>