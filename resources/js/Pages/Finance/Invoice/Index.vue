<template>
    <AuthenticatedLayout>
        <div
            class="bg-original-white-0 flex justify-between shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-0 md:px-10">
            <div class="md:py-8">
                <span class="inline-flex">
                    <h1 class="text-2xl font-bold text-neutral-black-300">Daftar Invoice</h1>
                </span>
                <p class="mb-3 text-base font-normal text-neutral-grey-100">Lihat dan cari <i>invoice</i> yang telah
                    dibuat
                </p>
                <Link v-if="['admin', 'keuangan'].includes($page.props.auth.user.roles[0].name)"
                    :href="route('finance.chargeitem.index')" as="button"
                    class="inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Invoice
                </Link>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="flex flex-col md:flex-row md:justify-end md:items-center mb-5 w-full">
            <form class="mr-3 w-full ">
                <div class="relative p-0 rounded-xl w-full border-none text-neutral-black-300">
                    <div class="absolute inset-y-0 left-0 mx-3 w-5 h-5 my-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="#8f8f8f" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <!-- Input query -->
                    <input v-model="searchQuery" id="searchQuery" placeholder="Cari"
                        class="pl-9 h-9 block w-full border border-1 outline-none focus:border-original-teal-300 focus:ring-original-teal-300 hover:ring-1 hover:ring-original-teal-300 rounded-xl shadow" />
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
            <div class="flex mt-4 md:mt-0">
                <select id="searchWith_id" v-model="searchAttribute"
                    class="bg-original-white-0 mr-3 border-1 border-neutral-grey-0 text-neutral-black-300 text-sm rounded-lg focus:ring-original-teal-300 focus:border-original-teal-300 block w-40 px-2.5 h-fit">
                    <option v-for="item in searchWith" :value=item.id>{{ item.label }}</option>
                </select>
                <MainButton @click="searchInvoice" class="teal-button text-original-white-0">
                    Cari
                </MainButton>
            </div>
        </div>

        <!-- Item List -->
        <div class="relative overflow-x-auto mb-5">
            <table class="w-full text-sm text-center rtl:text-right text-neutral-grey-200 ">
                <thead class="text-sm font-thin text-neutral-black-300 bg-gray-50 border-b">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-3/12">
                            Pasien
                        </th>
                        <th scope="col" class="px-6 py-3 w-3/12">
                            Status Invoice
                        </th>
                        <th scope="col" class="px-6 py-3 w-3/12">
                            Total Harga
                        </th>
                        <th scope="col" class="px-6 py-3 w-3/12">
                            Tanggal dikeluarkan Invoice
                        </th>
                    </tr>
                </thead>
                <tbody v-for="(item, index) in invoice" :key="index">
                    <tr class="bg-original-white-0 hover:bg-thirdinner-lightteal-300"
                        :class="{ 'border-b': index !== (invoice.length - 1) }">
                        <Link :href="route('finance.invoice.detail', { 'id': item.id })">
                        <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap hover:underline w-3/12">
                            {{ item.subject.display }}
                        </th>
                        </Link>
                        <td class="px-6 py-4 w-3/12">
                            {{ item.status }}
                        </td>

                        <td class="px-6 py-4 w-3/12">
                            {{ item.totalNet.currency }} {{ item.totalNet.value }}
                        </td>
                        <td class="px-6 py-4 w-3/12">
                            <p>{{ formatTimestamp(item.date).split('/')[0] }}</p>
                            <p>Jam {{ formatTimestamp(item.date).split('/')[1] }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="text-center mt-4" v-if="searchQuery !== '' && invoice.length === 0">Data tidak ditemukan
            </p>
        </div>

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutNav.vue';
import MainButton from '@/Components/MainButton.vue';

import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

// variables
const searchQuery = ref('');
const searchAttribute = ref('id');
const invoice = ref([]);
const searchWith = [
    { "id": "id", "label": "ID Invoice" },
    { "id": "status", "label": "Status" },
    { "id": "subject", "label": "Subject" }
]

// functions
const searchInvoice = async () => {
    const query = searchQuery.value;
    const attribute = searchAttribute.value;
    const { data } = await axios.get('/resources/Invoice');
    const originalData = data
    let filteredData = []
    switch (searchAttribute.value) {
        case "id":
            filteredData = originalData.filter(item => item.id.includes(query))
            invoice.value = filteredData;
            break;
        case "status":
            filteredData = originalData.filter(item => item.status.includes(query))
            invoice.value = filteredData;
            break;
        case "subject":
            filteredData = originalData.filter(item => item.subject.display.includes(query))
            invoice.value = filteredData;
        default:
            break;
    }
}

const fetchInvoice = async () => {
    const { data } = await axios.get('/resources/Invoice');
    invoice.value = data
}

const cancelSearch = async () => {
    hide.value = false;
    searchQuery.value = '';
    fetchInvoice();
};

const formatTimestamp = (timestamp) => {
    const date = new Date(timestamp);
    date.setHours(date.getHours() + 7);

    const daysOfWeek = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    const dayOfWeek = daysOfWeek[date.getUTCDay()];
    const day = date.getUTCDate();
    const month = months[date.getUTCMonth()];
    const year = date.getUTCFullYear();
    const hour = date.getUTCHours().toString().padStart(2, '0');
    const minute = date.getUTCMinutes().toString().padStart(2, '0');

    return `${dayOfWeek}, ${day} ${month} ${year} / ${hour}:${minute}`;
};

onMounted(() => {
    fetchInvoice();
})

</script>