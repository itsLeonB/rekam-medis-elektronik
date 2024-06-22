<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Medication Details - </title>
        </template>

        <div class="p-8 bg-original-white-0 overflow-hidden shadow sm:rounded-2xl mb-8">
            <div class="flex justify-between">
                <h1 class="mb-8 px-5 pt-3 text-2xl font-bold text-neutral-black-300">Detail Medication</h1>
                <div>
                    <Link :href="route('usermanagement.tambah')" as="button"
                        class="mr-3 inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Edit Medication
                    </Link>
                
                </div>
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
                                Kode Obat
                            </th>
                            <td class="px-6 py-4 w-3/4">
                                {{ medication.code }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/4">
                                Nama Obat
                            </th>
                            <td class="px-6 py-4 w-3/4">
                                {{ medication.name }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/4">
                                Tipe Obat
                            </th>
                            <td class="px-6 py-4 w-3/4">
                                {{ medication.form }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/4">
                                Extension
                            </th>
                            <td class="px-6 py-4 w-3/4">
                                {{ medication.extension }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/4">
                                Status
                            </th>
                            <td class="px-6 py-4 w-3/4">
                                {{ medication.status }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/4">
                                Stok Obat
                            </th>
                            <td class="px-6 py-4 w-3/4">
                                10
                            </td>
                        </tr>
                    </tbody>
                    
                </table>
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

const confirmingUserDeletion = ref(false);

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
};

const deleteUser = (user_id) => {
    axios.delete(route('users.destroy' + `/${user_id}`), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal(),
            route('users.index');
        }
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
};

const props = defineProps({
    medication_id: {
        type: String,
    },
});

const medication_id = props.medication_id;

const medication = ref([]);

const fetchMedication = async () => {
    const { data } = await axios.get(route('obat.show', { 'medication_id': medication_id }));
    medication.value = data;
};


onMounted(() => {
    fetchMedication();
}
);

</script>