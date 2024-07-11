<template>
    <AuthenticatedLayout>
        <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <span class="inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M10 4C8.93913 4 7.92172 4.42143 7.17157 5.17157C6.42143 5.92172 6 6.93913 6 8C6 9.06087 6.42143 10.0783 7.17157 10.8284C7.92172 11.5786 8.93913 12 10 12C11.0609 12 12.0783 11.5786 12.8284 10.8284C13.5786 10.0783 14 9.06087 14 8C14 6.93913 13.5786 5.92172 12.8284 5.17157C12.0783 4.42143 11.0609 4 10 4ZM10 6C10.5304 6 11.0391 6.21071 11.4142 6.58579C11.7893 6.96086 12 7.46957 12 8C12 8.53043 11.7893 9.03914 11.4142 9.41421C11.0391 9.78929 10.5304 10 10 10C9.46957 10 8.96086 9.78929 8.58579 9.41421C8.21071 9.03914 8 8.53043 8 8C8 7.46957 8.21071 6.96086 8.58579 6.58579C8.96086 6.21071 9.46957 6 10 6ZM17 12C16.84 12 16.76 12.08 16.76 12.24L16.5 13.5C16.28 13.68 15.96 13.84 15.72 14L14.44 13.5C14.36 13.5 14.2 13.5 14.12 13.6L13.16 15.36C13.08 15.44 13.08 15.6 13.24 15.68L14.28 16.5V17.5L13.24 18.32C13.16 18.4 13.08 18.56 13.16 18.64L14.12 20.4C14.2 20.5 14.36 20.5 14.44 20.5L15.72 20C15.96 20.16 16.28 20.32 16.5 20.5L16.76 21.76C16.76 21.92 16.84 22 17 22H19C19.08 22 19.24 21.92 19.24 21.76L19.4 20.5C19.72 20.32 20.04 20.16 20.28 20L21.5 20.5C21.64 20.5 21.8 20.5 21.8 20.4L22.84 18.64C22.92 18.56 22.84 18.4 22.76 18.32L21.72 17.5V16.5L22.76 15.68C22.84 15.6 22.92 15.44 22.84 15.36L21.8 13.6C21.8 13.5 21.64 13.5 21.5 13.5L20.28 14C20.04 13.84 19.72 13.68 19.4 13.5L19.24 12.24C19.24 12.08 19.08 12 19 12H17ZM10 13C7.33 13 2 14.33 2 17V20H11.67C11.39 19.41 11.19 18.77 11.09 18.1H3.9V17C3.9 16.36 7.03 14.9 10 14.9C10.43 14.9 10.87 14.94 11.3 15C11.5 14.36 11.77 13.76 12.12 13.21C11.34 13.08 10.6 13 10 13ZM18.04 15.5C18.84 15.5 19.5 16.16 19.5 17.04C19.5 17.84 18.84 18.5 18.04 18.5C17.16 18.5 16.5 17.84 16.5 17.04C16.5 16.16 17.16 15.5 18.04 15.5Z"
                        fill="currentColor" />
                </svg>
                <h1 class="text-2xl font-bold text-neutral-black-300">Medication Management</h1>
            </span>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman Apoteker untuk mengelola obat.
            </p>
            <div class="button-group">
                <Link :href="route('medication')" as="button"
                class="mr-2 inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                class="w-5 h-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
            </Link>
            <Link :href="route('medication.tambah')" as="button"
                class="mr-2 inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Obat
            </Link>
            <Link :href="route('medication.prescription')" as="button"
                class="mr-2 inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
            <svg  class="mr-2" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor" stroke="currentColor">
                <path
                    d="m678-134 46-46-64-64-46 46q-14 14-14 32t14 32q14 14 32 14t32-14Zm102-102 46-46q14-14 14-32t-14-32q-14-14-32-14t-32 14l-46 46 64 64ZM735-77q-37 37-89 37t-89-37q-37-37-37-89t37-89l148-148q37-37 89-37t89 37q37 37 37 89t-37 89L735-77ZM200-200v-560 560Zm0 80q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h168q13-36 43.5-58t68.5-22q38 0 68.5 22t43.5 58h168q33 0 56.5 23.5T840-760v245q-20-5-40-5t-40 3v-243H200v560h243q-3 20-3 40t5 40H200Zm280-670q13 0 21.5-8.5T510-820q0-13-8.5-21.5T480-850q-13 0-21.5 8.5T450-820q0 13 8.5 21.5T480-790ZM280-600v-80h400v80H280Zm0 160v-80h400v34q-8 5-15.5 11.5T649-460l-20 20H280Zm0 160v-80h269l-49 49q-8 8-14.5 15.5T474-280H280Z" />
            </svg>
            Permintaan resep
            </Link>
            <Link :href="route('medication.requestStock')" as="button"
            class="mr-2 inline-flex mb-3 justify-center px-4 py-2 secondary-button border border-teal-600 rounded-xl font-semibold text-sm teal-button-text hover:text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mr-2 bi bi-file-earmark-text" viewBox="0 0 16 16" troke-width="1.5">
                    <path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5"/>
                    <path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                </svg>
                Data Request Obat
            </Link>
            </div>
           
        </div>
        <div
            class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:pl-10 md:pr-14">
            <!-- Search bar -->
            <div class="flex flex-col md:flex-row md:justify-end md:items-center mb-5 w-full">
                <form class="mr-3 w-full">
                    <div class="relative p-0 rounded-xl w-full border-none text-neutral-black-300">
                        <div class="absolute inset-y-0 left-0 mx-3 w-5 h-5 my-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="#8f8f8f" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <input v-model="searchQuery" id="searchQuery" @keydown.enter="searchMedications"
                            placeholder="Cari"
                            class="pl-9 h-9 block w-full border border-1 border-neutral-grey-0 outline-none focus:border-original-teal-300 focus:ring-original-teal-300 hover:ring-1 hover:ring-original-teal-300 rounded-xl shadow" />
                        <div class="absolute inset-y-0 right-0 mx-3 w-5 h-5 my-auto cursor-pointer"
                            @click="cancelSearch" v-show="hide">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#8f8f8f"
                                class="w-5 h-5 hover:fill-thirdouter-red-200">
                                <path fill-rule="evenodd"
                                    d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z"
                                    clip-rule="evenodd" />
                            </svg>

                        </div>
                    </div>
                </form>
                <div class="flex mt-4 md:mt-0">
                    <MainButton @click="searchMedications" class="teal-button text-original-white-0">
                        Cari
                    </MainButton>
                </div>
            </div>
            <div class="relative overflow-x-auto mb-5">
                <table class="w-full text-base text-left rtl:text-right text-neutral-grey-200">
                    <thead class="text-base text-neutral-black-300 uppercase bg-gray-50 border-b">
                        <tr>
                            <th @click="sortBy('medicine_code')" class="table-header">Kode <span
                                    v-if="sortKey === 'medicine_code'" class="arrow">{{ sortDirection === 'asc' ? '▲' :
                                        '▼' }}</span>
                            </th>
                            <th @click="sortBy('name')" class="table-header">Nama <span v-if="sortKey === 'name'"
                                    class="arrow">{{ sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            </th>
                            <th @click="sortBy('dosage_form')" class="table-header">Tipe <span
                                    v-if="sortKey === 'dosage_form'" class="arrow">{{ sortDirection === 'asc' ? '▲' :
                                        '▼' }}</span>
                            </th>
                            <th @click="sortBy('quantity')" class="table-header">Jumlah <span
                                    v-if="sortKey === 'quantity'" class="arrow">{{ sortDirection === 'asc' ? '▲' : '▼'
                                    }}</span>
                            </th>
                            <th @click="sortBy('package')" class="table-header">Jenis <span v-if="sortKey === 'package'"
                                    class="arrow">{{ sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            </th>
                            <th @click="sortBy('expiry_date')" class="table-header">Tanggal Kadaluarsa <span
                                    v-if="sortKey === 'expiry_date'" class="arrow">{{ sortDirection === 'asc' ? '▲' :
                                        '▼' }}</span>
                            </th>
                            <th @click="sortBy('prices.base_price')" class="table-header">Harga Dasar <span
                                    v-if="sortKey === 'prices.base_price'" class="arrow">{{ sortDirection === 'asc' ?
                                        '▲' : '▼' }}</span>
                            </th>
                            <th @click="sortBy('prices.purchase_price')" class="table-header">Harga Beli <span
                                    v-if="sortKey === 'prices.purchase_price'" class="arrow">{{ sortDirection === 'asc'
                                        ? '▲' : '▼' }}</span>
                            </th>
                            <th @click="sortBy('prices.treatment_price_1')" class="table-header">Harga Rawat Jalan <span
                                    v-if="sortKey === 'prices.treatment_price_1'" class="arrow">{{ sortDirection ===
                                        'asc' ? '▲' : '▼' }}</span>
                            </th>
                            <th @click="sortBy('prices.treatment_price_2')" class="table-header">Harga Rawat Inap K1
                                <span v-if="sortKey === 'prices.treatment_price_2'" class="arrow">{{ sortDirection ===
                                    'asc' ? '▲' : '▼' }}</span>
                            </th>
                            <th @click="sortBy('prices.treatment_price_3')" class="table-header">Harga Rawat Inap K2
                                <span v-if="sortKey === 'prices.treatment_price_3'" class="arrow">{{ sortDirection ===
                                    'asc' ? '▲' : '▼' }}</span>
                            </th>
                            <th @click="sortBy('prices.treatment_price_4')" class="table-header">Harga Rawat Inap K3
                                <span v-if="sortKey === 'prices.treatment_price_4'" class="arrow">{{ sortDirection ===
                                    'asc' ? '▲' : '▼' }}</span>
                            </th>
                            <th @click="sortBy('prices.treatment_price_5')" class="table-header">Harga Rawat Inap K4
                                <span v-if="sortKey === 'prices.treatment_price_5'" class="arrow">{{ sortDirection ===
                                    'asc' ? '▲' : '▼' }}</span>
                            </th>
                            <th @click="sortBy('prices.treatment_price_6')" class="table-header">Harga Rawat Inap K5
                                <span v-if="sortKey === 'prices.treatment_price_6'" class="arrow">{{ sortDirection ===
                                    'asc' ? '▲' : '▼' }}</span>
                            </th>
                            <th @click="sortBy('prices.treatment_price_7')" class="table-header">Harga Rawat Inap K6
                                <span v-if="sortKey === 'prices.treatment_price_7'" class="arrow">{{ sortDirection ===
                                    'asc' ? '▲' : '▼' }}</span>
                            </th>
                            <th @click="sortBy('prices.treatment_price_8')" class="table-header">Harga Rawat Inap K7
                                <span v-if="sortKey === 'prices.treatment_price_8'" class="arrow">{{ sortDirection ===
                                    'asc' ? '▲' : '▼' }}</span>
                            </th>
                            <th @click="sortBy('prices.treatment_price_9')" class="table-header">Harga Rawat Inap K8
                                <span v-if="sortKey === 'prices.treatment_price_9'" class="arrow">{{ sortDirection ===
                                    'asc' ? '▲' : '▼' }}</span>
                            </th>
                            <th @click="sortBy('ingridients')" class="table-header">Bahan
                                <span v-if="sortKey === 'ingridients    '" class="arrow">{{ sortDirection ===
                                    'asc' ? '▲' : '▼' }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(medication, index) in medications.data" :key="index"
                            class="bg-original-white-0 hover:bg-thirdinner-lightteal-300"
                            :class="{ 'border-b': index !== (medications.length - 1) }">
                            <td class="px-6 py-4 font-normal whitespace-nowrap hover:underline w-1/5">{{
                                medication.medicine_code }}</td>
                            <td class="px-6 py-4 w-3/5">{{ medication.name }}</td>
                            <td class="px-6 py-4 w-2/5">{{ medication.dosage_form }}</td>
                            <td class="px-6 py-4 w-1/5">{{ medication.quantity }}</td>
                            <td class="px-6 py-4 w-1/5">{{ medication.package }}</td>
                            <td class="px-6 py-4 w-1/5">{{ formatDate(medication.expiry_date) }}</td>
                            <template v-for="price in JSON.parse(medication.prices)">
                                <td class="px-6 py-4 w-1/5">{{ price }}</td>
                            </template>
                            <td class="px-6 py-4 w-1/5">
                                <template v-for="ingredient in JSON.parse(medication.ingredients)">
                                    <ul>
                                        <li v-for="ingredientName in ingredient"> {{ ingredientName }}</li>
                                    </ul>
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <nav class="flex justify-end">
                <ul class="inline-flex -space-x-px text-base h-10">
                    <li>
                        <button
                            @click="fetchPagination((medications.current_page - 1) < 1 ? 1 : (medications.current_page - 1))"
                            class="flex items-center justify-center px-4 h-10 leading-tight text-neutral-grey-200 bg-original-white-0 border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700">&laquo;</button>
                    </li>
                    <template v-for="(item, index) in paging">
                        <li v-if="item !== medications.current_page">
                            <button @click="fetchPagination(item === '...' ? medications.current_page : item)"
                                class="flex items-center justify-center px-4 h-10 text-neutral-grey-200 bg-original-white-0 border border-gray-300 hover:bg-gray-100 hover:text-gray-700 ">{{
                                    item }}</button>
                        </li>
                        <li v-else-if="item === medications.current_page">
                            <button @click="fetchPagination(item)"
                                class="flex items-center justify-center px-4 h-10 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 ">{{
                                    item }}</button>
                        </li>
                    </template>
                    <li>
                        <button
                            @click="fetchPagination((medications.current_page + 1) > medications.last_page ? medications.last_page : (medications.current_page + 1))"
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
import { Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const medications = ref([]);

const hide = ref(false);

const fetchMedications = async (page = 1) => {
    try {
        const { data } = await axios.get(route('medicine.index', { 'page': page }));
        medications.value = data;

        generateNumbers(1, data.current_page, data.last_page);
    } catch (error) {
        console.error('Error fetching medications:', error);
    }
};

const fetchPagination = async (page = 1) => {
    if (searchQuery.value == '') {
        const { data } = await axios.get(route('medicine.index'), { params: { 'page': page, 'sort': sortKey, 'direction': sortDirection } });
        medications.value = data;
        generateNumbers(1, medications.value.current_page, medications.value.last_page);
    } else {
        const query = searchQuery.value;
        const { data } = await axios.get(route('medicine.index'), { params: { 'search': query, 'page': page, 'sort': sortKey, 'direction': sortDirection } });
        medications.value = data;
        generateNumbers(1, medications.value.current_page, medications.value.last_page);
    };
};

const cancelSearch = async () => {
    hide.value = false;
    searchQuery.value = '';
    fetchMedications(1);
};

let sortKey = '';
let sortDirection = '';

const sortBy = async (key) => {

    if (sortKey === key) {
        if (sortDirection === 'asc') {
            sortDirection = 'desc';
        } else if (sortDirection === 'desc') {
            sortKey = '';
            sortDirection = 'asc';
        } else {
            sortDirection = 'asc';
        }
    } else {
        sortKey = key;
        sortDirection = 'asc';
    }

    const { data } = await axios.get(route('medicine.index'), { params: { 'page': 1, 'sort': sortKey, 'direction': sortDirection } });
    console.log("Sorted Medications:", medications.value, sortKey);
    medications.value = data;
};

const searchQuery = ref('');

const searchMedications = async (event) => {
    event.preventDefault();
    event.stopPropagation();
    hide.value = true;
    const search = searchQuery.value;
    try {
        const { data } = await axios.get(route('medicine.index', { search }));
        medications.value = data;
        generateNumbers(1, medications.value.current_page, medications.value.last_page);
    } catch (error) {
        console.error("Error fetching medications:", error);
    }
};

const paging = ref([]);

const formatDate = (date) => {
    const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
    return new Date(date).toLocaleDateString('id-ID', options);
};

const checkRequest = async () => {
    try {
        const response = await axios.get(route('obat.checkRequest'));
        console.log(response.data);
        document.getElementById('check').textContent = response.data.message;
    } catch (error) {
        onsole.error('An error occurred:', error);
        document.getElementById('check').textContent = 'An error occurred while checking the request.';
    }
};

const generateNumbers = (firstNumber, currentNumber, lastNumber) => {
    const result = [];
    if (lastNumber > 5 && (currentNumber < 3 || currentNumber > lastNumber - 2)) {
        result.push(firstNumber, firstNumber + 1);
        result.push('...');
        result.push(lastNumber - 1, lastNumber);
    } else if (lastNumber > 5) {
        result.push(firstNumber, firstNumber + 1);
        result.push('...');
        result.push(currentNumber);
        result.push('...');
        result.push(lastNumber - 1, lastNumber);
    } else {
        for (let i = firstNumber; i <= lastNumber; i++) {
            result.push(i);
        }
    }
    paging.value = result;
};

onMounted(() => {
    fetchMedications();
    checkRequest();
}
);

</script>

<style>
.table-header {
    cursor: pointer;
    text-align: center;
    position: relative;
    padding: 20px;
}

.arrow {
    margin-left: 5px;
    position: absolute;
    right: 0;
}

.button-group {
    display: flex;
}
</style>