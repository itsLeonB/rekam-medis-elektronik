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
                <MainButton class="mt-4 w-full mb-3 mx-auto max-w-[284px] block orange-button text-original-white-0"
                @click.prevent="submit">Tolak</MainButton>
            </div>

        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import MainButton from '@/Components/MainButton.vue';
import Modal from '@/Components/Modal.vue';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    medication_dispense_id: {
        type: String,
    },
});

const medication_id = props.medication_dispense_id;

const medication = ref([]);

const fetchMedication = async () => {
    const { data } = await axios.get(route('medicationDispense.show', { 'medicationReq_id': medication_id }));

    medication.value = data;
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
        status: 'active',
        medicationReference: {
            reference: med.medicationReferenceId,
            display: med.medicationReferenceName
        },
        subject: {
            reference: med.subject,
            display: med.requester
        },
        context: {
            reference: med.encounter
        },
        quantity: {
            system: "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
            unit: med.uom,
            code: med.uom_code,
            value: med.quantity
        },
        whenPrepared: new Date().toISOString(),
        whenHandover: new Date().toISOString(),
        performer: {
            reference: "Organization/d7c204fd-7c20-4c59-bd61-4dc55b78438c"
        }
    };
    axios.post(route('integration.store', { resourceType: 'MedicationDispense' }), formDataJson)
        // .then(response => {
        //     creationSuccessModal.value = true;
        //     setTimeout(() => {
        //         successAlertVisible.value = false;
        //     }, 3000);
        // })
        .catch(error => {
            console.error('Error creating medication dispense:', error);
            // failAlertVisible.value = true;
            // setTimeout(() => {
            //     failAlertVisible.value = false;
            // }, 3000);
        });
};

onMounted(() => {
    fetchMedication();
    console.log(props);
}
);

</script>

<style>
.buttons-submit {
    display: flex;
    justify-content: flex-end;
}
</style>