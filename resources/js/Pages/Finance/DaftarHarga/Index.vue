<template>
    <AuthenticatedLayout>
        <div
            class="bg-original-white-0 flex justify-between shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-0 md:px-10">
            <div class="md:py-8">
                <span class="inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" viewBox="0 0 24 24" fill="none">
                        <path
                            d="M4 8L6.1 10.8C6.37944 11.1726 6.74179 11.475 7.15836 11.6833C7.57493 11.8916 8.03426 12 8.5 12H20M10 6H14M12 4V8M14 18C14 18.5304 14.2107 19.0391 14.5858 19.4142C14.9609 19.7893 15.4696 20 16 20C16.5304 20 17.0391 19.7893 17.4142 19.4142C17.7893 19.0391 18 18.5304 18 18C18 17.4696 17.7893 16.9609 17.4142 16.5858C17.0391 16.2107 16.5304 16 16 16C15.4696 16 14.9609 16.2107 14.5858 16.5858C14.2107 16.9609 14 17.4696 14 18ZM6 18C6 18.5304 6.21071 19.0391 6.58579 19.4142C6.96086 19.7893 7.46957 20 8 20C8.53043 20 9.03914 19.7893 9.41421 19.4142C9.78929 19.0391 10 18.5304 10 18C10 17.4696 9.78929 16.9609 9.41421 16.5858C9.03914 16.2107 8.53043 16 8 16C7.46957 16 6.96086 16.2107 6.58579 16.5858C6.21071 16.9609 6 17.4696 6 18Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 12V14M12 14L9.5 16.5M12 14L14.5 16.5" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <h1 class="text-2xl font-bold text-neutral-black-300">Katalog Harga Layanan dan Produk</h1>
                </span>
                <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman untuk mengelola harga dan keterangan
                    layanan
                </p>
                <div class="flex flex-col gap-4 sm:flex-row">
                    <Link v-if="['admin', 'perekammedis'].includes($page.props.auth.user.roles[0].name)"
                        :href="route('finance.newinvoice')" as="button"
                        class="inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Layanan/Produk
                    </Link>
                </div>
            </div>
        </div>

        <div
            class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:pl-10 md:pr-14">
            <!-- Search bar -->
            <div class="flex justify-end items-center mb-5 w-full">
                <form class="mr-3 w-full" @submit.prevent="searchItems">
                    <div class="relative p-0 rounded-xl w-full border-none text-neutral-black-300">
                        <div class="absolute inset-y-0 left-0 mx-3 w-5 h-5 my-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="#8f8f8f" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <input v-model="searchItem" id="search-item" placeholder="Cari Layanan/Produk"
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
                <MainButton @click="searchItems" class="teal-button text-original-white-0">
                    Cari
                </MainButton>
            </div>
            <div class="relative overflow-x-auto mb-5">
                <table class="w-full text-base text-left rtl:text-right text-neutral-grey-200 ">
                    <thead class="text-base text-neutral-black-300 uppercase bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-2/5">
                                Nama
                            </th>
                            <th scope="col" class="px-6 py-3 w-2/5">
                                Harga
                            </th>
                        </tr>
                    </thead>
                    <tbody v-for="(item, index) in items.data" :key="item.id">
                        <tr class="bg-original-white-0 hover:bg-thirdinner-lightteal-300"
                            :class="{ 'border-b': index !== (items.data.length - 1) }">
                            <Link :href="route('finance.catalogue.detail', { 'id': item.code })">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap hover:underline w-2/5">
                                {{ item.display }}
                            </th>
                            </Link>
                            <td class="px-6 py-4 w-2/5">
                                Rp{{ item.price.value }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <nav class="flex justify-end">
                <ul v-for="(link, index) in items.links" class="inline-flex -space-x-px text-base h-10">
                    <li v-if="index === 0">
                        <button @click="fetchPagination((items.current_page - 1) < 1 ? 1 : (items.current_page - 1))"
                            class="flex items-center justify-center px-4 h-10 leading-tight text-neutral-grey-200 bg-original-white-0 border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700">&laquo;</button>
                    </li>
                    <li v-else-if="index !== 0 && index !== (items.links.length - 1) && link.active == false">
                        <button @click="fetchPagination(link.url === null ? items.current_page : link.label)"
                            class="flex items-center justify-center px-4 h-10 text-neutral-grey-200 bg-original-white-0 border border-gray-300 hover:bg-gray-100 hover:text-gray-700 ">{{
                                link.label }}</button>
                    </li>
                    <li v-else-if="index !== 0 && index !== (items.links.length - 1) && link.active == true">
                        <button @click="fetchPagination(link.label)"
                            class="flex items-center justify-center px-4 h-10 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 ">{{
                                link.label }}</button>
                    </li>
                    <li v-else-if="index === (items.links.length - 1)">
                        <button
                            @click="fetchPagination((items.current_page + 1) > items.last_page ? items.last_page : (items.current_page + 1))"
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


const items = ref([]);

const hide = ref(false);

const fetchitems = async (page = 1) => {
    const { data } = await axios.get('/catalogue', { params: { page } });
    items.value = data.items;
};

const cancelSearch = async () => {
    hide.value = false;
    searchItem.value = '';
    fetchitems(1);
};

const searchItem = ref('');

const searchItems = async () => {
    hide.value = true;
    const query = searchItem.value;
    const { data } = await axios.get('/catalogue', { params: { name: query } });
    items.value = data.items;
};

const fetchPagination = async (page = 1) => {
    if (searchItem.value == '') {
        const { data } = await axios.get('/catalogue', { params: { page } });
        items.value = data.items;
    } else {
        const query = searchItem.value;
        const { data } = await axios.get('/catalogue', { params: { name: query, page: page } });
        items.value = data.items;
    };
};

onMounted(() => {
    fetchitems();
}
);
</script>