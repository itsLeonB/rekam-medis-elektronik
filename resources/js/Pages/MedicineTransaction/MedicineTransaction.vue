<template>
    <AuthenticatedLayout>
        <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <span class="inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M10 4C8.93913 4 7.92172 4.42143 7.17157 5.17157C6.42143 5.92172 6 6.93913 6 8C6 9.06087 6.42143 10.0783 7.17157 10.8284C7.92172 11.5786 8.93913 12 10 12C11.0609 12 12.0783 11.5786 12.8284 10.8284C13.5786 10.0783 14 9.06087 14 8C14 6.93913 13.5786 5.92172 12.8284 5.17157C12.0783 4.42143 11.0609 4 10 4ZM10 6C10.5304 6 11.0391 6.21071 11.4142 6.58579C11.7893 6.96086 12 7.46957 12 8C12 8.53043 11.7893 9.03914 11.4142 9.41421C11.0391 9.78929 10.5304 10 10 10C9.46957 10 8.96086 9.78929 8.58579 9.41421C8.21071 9.03914 8 8.53043 8 8C8 7.46957 8.21071 6.96086 8.58579 6.58579C8.96086 6.21071 9.46957 6 10 6ZM17 12C16.84 12 16.76 12.08 16.76 12.24L16.5 13.5C16.28 13.68 15.96 13.84 15.72 14L14.44 13.5C14.36 13.5 14.2 13.5 14.12 13.6L13.16 15.36C13.08 15.44 13.08 15.6 13.24 15.68L14.28 16.5V17.5L13.24 18.32C13.16 18.4 13.08 18.56 13.16 18.64L14.12 20.4C14.2 20.5 14.36 20.5 14.44 20.5L15.72 20C15.96 20.16 16.28 20.32 16.5 20.5L16.76 21.76C16.76 21.92 16.84 22 17 22H19C19.08 22 19.24 21.92 19.24 21.76L19.4 20.5C19.72 20.32 20.04 20.16 20.28 20L21.5 20.5C21.64 20.5 21.8 20.5 21.8 20.4L22.84 18.64C22.92 18.56 22.84 18.4 22.76 18.32L21.72 17.5V16.5L22.76 15.68C22.84 15.6 22.92 15.44 22.84 15.36L21.8 13.6C21.8 13.5 21.64 13.5 21.5 13.5L20.28 14C20.04 13.84 19.72 13.68 19.4 13.5L19.24 12.24C19.24 12.08 19.08 12 19 12H17ZM10 13C7.33 13 2 14.33 2 17V20H11.67C11.39 19.41 11.19 18.77 11.09 18.1H3.9V17C3.9 16.36 7.03 14.9 10 14.9C10.43 14.9 10.87 14.94 11.3 15C11.5 14.36 11.77 13.76 12.12 13.21C11.34 13.08 10.6 13 10 13ZM18.04 15.5C18.84 15.5 19.5 16.16 19.5 17.04C19.5 17.84 18.84 18.5 18.04 18.5C17.16 18.5 16.5 17.84 16.5 17.04C16.5 16.16 17.16 15.5 18.04 15.5Z"
                        fill="currentColor" />
                </svg>
                <h1 class="text-2xl font-bold text-neutral-black-300">Transaksi Obat</h1>
            </span>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman admin untuk mengatur transaksi obat aplikasi.</p>
            <Link :href="route('medicinetransaction.tambah')" as="button"
                class="inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Transaksi
            </Link>
        </div>
        <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:pl-10 md:pr-14">
            <!-- Search bar -->
            <div class="flex justify-end items-center mb-5 w-full">
                <form class="mr-3 w-full">
                    <div class="relative p-0 rounded-xl w-full border-none text-neutral-black-300">
                        <div class="absolute inset-y-0 left-0 mx-3 w-5 h-5 my-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="#8f8f8f" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <input v-model="searchNama" id="search-nama" placeholder="Cari Nama"
                            class="pl-9 h-9 block w-full border border-1 border-neutral-grey-0 outline-none focus:border-original-teal-300 focus:ring-original-teal-300 hover:ring-1 hover:ring-original-teal-300 rounded-xl shadow" />
                        <div class="absolute inset-y-0 right-0 mx-3 w-5 h-5 my-auto cursor-pointer" @click="cancelSearch"
                            v-show="hide">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#8f8f8f"
                                class="w-5 h-5 hover:fill-thirdouter-red-200">
                                <path fill-rule="evenodd"
                                    d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z"
                                    clip-rule="evenodd" />
                            </svg>

                        </div>
                    </div>
                </form>
                <MainButton @click="searchMedicineTransactions" class="teal-button text-original-white-0">
                    Cari
                </MainButton>
            </div>
            <div class="relative overflow-x-auto mb-5">
                <table class="w-full text-base text-left rtl:text-right text-neutral-grey-200 ">
                    <thead class="text-base text-neutral-black-300 uppercase bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-1/6">
                                Id Transaksi
                            </th>
                            <th scope="col" class="px-6 py-3 w-1/6">
                                Nama Obat
                            </th>
                            <th scope="col" class="px-6 py-3 w-1/6">
                                Catatan
                            </th>
                            <th scope="col" class="px-6 py-3 w-1/6">
                                Kuantitas
                            </th>
                            <th scope="col" class="px-6 py-3 w-1/6">
                                Di Buat
                            </th>
                            <th scope="col" class="px-6 py-3 w-1/6">
                                Di Ubah
                            </th>
                            <th scope="col" class="px-6 py-3 w-1/6">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody v-for="(medicinetransaction, index) in medicinetransactions.data" :key="medicinetransaction.id">
                        <tr class="bg-original-white-0 hover:bg-thirdinner-lightteal-300"
                            :class="{ 'border-b': index !== (medicinetransactions.data.length - 1) }">
                            <td class="px-6 py-4 w-1/6">
                                <!-- (untuk relasi pasien/transaksi yang membutuhkan obat) -->
                                {{ medicinetransaction.id_transaction }} 
                            </td>
                            <td class="px-6 py-4 w-1/6">
                                {{ medicinetransaction.medicine.name }}
                            </td>
                            <td class="px-6 py-4 w-1/6">
                                {{ medicinetransaction.note }}
                            </td>
                            <td class="px-6 py-4 w-1/6">
                                {{ medicinetransaction.quantity }}
                            </td>
                            <td class="px-6 py-4 w-1/6">
                                {{ moment(medicinetransaction.created_at).format('HH:mm:ss YYYY-MM-DD') }}
                            </td>
                            <td class="px-6 py-4 w-1/6">
                                {{ moment(medicinetransaction.updated_at).format('HH:mm:ss YYYY-MM-DD') }}
                            </td>
                            <td class="px-6 py-4 w-1/6">
                                <Link :href="route('medicinetransaction.tambah')" as="button" :data="{ transaction: medicinetransaction }"   
                                    class="text-blue-600 hover:text-blue-700 underline mr-4">
                                    Edit
                                </Link>
                                <button @click="confirmmedicinetransactionDeletion(medicinetransaction._id  )" class="text-red-600 hover:text-red-700 underline">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <Modal :show="confirmingMedicineTransactionDeletion" @close="closeModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Apakah yakin akan menghapus transaksi ini?
                    </h2>
                    <div class="mt-6 flex justify-end">
                        <MainButton @click="closeModal" class="teal-button text-original-white-0"> Cancel </MainButton>

                        <MainButton class="ml-3 orange-button text-original-white-0" @click="deleteMedicineTransaction(medicinetransaction_id)">
                            Hapus Transaksi
                        </MainButton>
                    </div>
                </div>
            </Modal>
            <nav class="flex justify-end">
                <ul v-for="(link, index) in medicinetransactions.links" class="inline-flex -space-x-px text-base h-10">
                    <li v-if="index === 0">
                        <button @click="fetchPagination((medicinetransactions.current_page - 1) < 1 ? 1 : (medicinetransactions.current_page - 1))"
                            class="flex items-center justify-center px-4 h-10 leading-tight text-neutral-grey-200 bg-original-white-0 border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700">&laquo;</button>
                    </li>
                    <li v-else-if="index !== 0 && index !== (medicinetransactions.links.length - 1) && link.active == false">
                        <button @click="fetchPagination(link.url === null ? medicinetransactions.current_page : link.label)"
                            class="flex items-center justify-center px-4 h-10 text-neutral-grey-200 bg-original-white-0 border border-gray-300 hover:bg-gray-100 hover:text-gray-700 ">{{
                                link.label }}</button>
                    </li>
                    <li v-else-if="index !== 0 && index !== (medicinetransactions.links.length - 1) && link.active == true">
                        <button @click="fetchPagination(link.label)"
                            class="flex items-center justify-center px-4 h-10 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 ">{{
                                link.label }}</button>
                    </li>
                    <li v-else-if="index === (medicinetransactions.links.length - 1)">
                        <button
                            @click="fetchPagination((medicinetransactions.current_page + 1) > medicinetransactions.last_page ? medicinetransactions.last_page : (medicinetransactions.current_page + 1))"
                            class="flex items-center justify-center px-4 h-10 leading-tight text-neutral-grey-200 bg-original-white-0 border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700">&raquo;</button>
                    </li>
                </ul>
            </nav>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutNav.vue';
import MainButton from '@/Components/MainButton.vue';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import moment from 'moment';
import Modal from '@/Components/Modal.vue';

const medicinetransactions = ref([]);

const hide = ref(false);

const fetchMedicineTransactions = async (page = 1) => {
    const { data } = await axios.get(route('medicinetransactions.index', {'page': page}));
    medicinetransactions.value = data.medicinetransactions;
};

const cancelSearch = async () => {
    hide.value = false;
    searchNama.value = '';
    fetchMedicineTransactions(1);
};

const searchNama = ref('');

const searchMedicineTransactions = async () => {
    hide.value = true;
    const query = searchNama.value;
    const { data } = await axios.get(route('medicinetransactions.index', {'name': query}));
    medicinetransactions.value = data.medicinetransactions;
};

const fetchPagination = async (page = 1) => {
    if (searchNama.value == '') {
        const { data } = await axios.get(route('medicinetransactions.index', {'page': page}));
        medicinetransactions.value = data.medicinetransactions;
    } else {
        const query = searchNama.value;
        const { data } = await axios.get(route('medicinetransactions.index'), {params: {'name': query, 'page': page}});
        medicinetransactions.value = data.medicinetransactions;
    };
};

const confirmingMedicineTransactionDeletion = ref(false);
let medicinetransaction_id = ref("");
const confirmmedicinetransactionDeletion = (id) => {
    confirmingMedicineTransactionDeletion.value = true;
    medicinetransaction_id = id;
};
const closeModal = () => {
    confirmingMedicineTransactionDeletion.value = false;
};
const deleteMedicineTransaction = async (id) => {
    confirmingMedicineTransactionDeletion.value = false
    try {
        await axios.delete(`/medicinetransactions/${id}`);
        
        // Eksekusi fetchMedicineTransactions() tanpa memperhatikan respons status
        fetchMedicineTransactions();
    } catch (error) {
        console.error('Error deleting MedicineTransaction:', error.message);
        // Eksekusi fetchMedicineTransactions() dalam kasus error jika diperlukan
        fetchMedicineTransactions();
    }
};

onMounted(() => {
    fetchMedicineTransactions();
});
</script>
