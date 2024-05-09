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
                    <h1 class="text-2xl font-bold text-neutral-black-300">Modul Finance</h1>
                </span>
                <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman Daftar Invoice.
                </p>
                <div class="flex flex-col sm:flex-row">
                    <Link v-if="['admin', 'perekammedis'].includes($page.props.auth.user.roles[0].name)"
                        :href="route('finance.newinvoice')" as="button"
                        class="inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Invoice
                    </Link>
                </div>
            </div>
            <!-- <div class="overflow-hidden hidden lg:block lg:h-48">
                <img :src="'storage/images/emer.png'" class="h-full" alt="">
            </div> -->
        </div>
        <div class="relative overflow-x-auto mb-5">
            <div v-if="invoice">
                <table class="w-full text-base text-left rtl:text-right text-neutral-grey-200 ">
                    <thead class="text-base text-neutral-black-300 uppercase bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-2/5">
                                Subjek
                            </th>
                            <th scope="col" class="px-6 py-3 w-2/5">
                                Resipien
                            </th>
                            <th scope="col" class="px-6 py-3 w-1/5">
                                Status
                            </th>
                        </tr>
                    </thead>

                    <tbody v-for="(item, index) in invoice.data" :key="invoice.id">
                        <tr class="bg-original-white-0 hover:bg-thirdinner-lightteal-300"
                            :class="{ 'border-b': index !== (invoice.data.length - 1) }">
                            <!-- <Link :href="route('usermanagement.details', { 'user_id': invoice.id })"> -->
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap hover:underline w-2/5">
                                {{ item.subject }}
                            </th>
                            <!-- </Link> -->
                            <td class="px-6 py-4 w-2/5">
                                {{ item.recipient }}
                            </td>
                            <td v-html="item.status ? 'Sudah' : '<strong>Belum</strong>'" class="px-6 py-4 w-1/5">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-else>Loading..</div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutNav.vue';
import axios from 'axios';

const pageSize = 10;
const endpoint = "/api/invoice"
const invoice = ref();

const getData = async (currentPage = 1) => {
    try {
        const params = {
            page: currentPage,
        }
        const response = await axios.get(endpoint, { params })
        console.log("complete")
        invoice.value = response.data;
    } catch (error) {
        console.log(error);
    }
}

onMounted(() => {
    getData()
})
</script>
