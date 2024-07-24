<template>
    <AuthenticatedLayout>
        <div class="bg-original-white-0  shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <h1 class=" font-bold text-2xl">Detail Account {{ accountData.id }}</h1>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Resource Type</h2>
                <p class="font-normal text-base">{{ accountData.resourceType }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Resource ID</h2>
                <p class="font-normal text-base">{{ accountData.id }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Status Account</h2>
                <p class="font-normal text-base">{{ accountData.status }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Pasien</h2>
                <p class="font-normal text-base">{{ accountData.name }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Pengurus Account</h2>
                <p class="font-normal text-base">{{ accountData.owner?.display }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Tanggal berlaku Account</h2>
                <p class="font-normal text-base">{{ formatTimestamp(accountData.servicePeriod?.start) }} - {{
                    formatTimestamp(accountData.servicePeriod?.end) }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Detail Cakupan Account</h2>
                <table class="w-full text-sm text-left rtl:text-right text-neutral-grey-200 col-span-3 shadow">
                    <thead class="text-sm text-neutral-black-300 uppercase bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-1/6">No.</th>
                            <th scope="col" class="px-6 py-3 w-2/6">Tipe Coverage</th>
                            <th scope="col" class="px-6 py-3 w-2/6">Status</th>
                            <th scope="col" class="px-6 py-3 w-1/6">Prioritas</th>
                        </tr>
                    </thead>
                    <tbody v-if="coverageData" v-for="(item, index) in coverageData" :key="index">
                        <tr class="bg-original-white-0 hover:bg-thirdinner-lightteal-300">
                            <td class="px-6 py-4 w-1/6"> {{ index + 1 }}</td>
                            <td class="px-6 py-4 w-2/6">{{ item.type?.coding[0].display }}</td>
                            <td class="px-6 py-4 w-2/6"> {{ item.status }}</td>
                            <td class="px-6 py-4 w-1/6">{{ accountData.coverage[index].priority }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 justify-end flex gap-1">
                <!-- <Link v-if="['admin', 'keuangan'].includes($page.props.auth.user.roles[0].name)"
                    :href="route('finance.invoice.edit', { 'id': props.id })" as="button"
                    class="inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Cetak Invoice
                </Link> -->
                <Link v-if="['admin', 'keuangan'].includes($page.props.auth.user.roles[0].name)"
                    :href="route('finance.account.edit', { 'id': props.id })" as="button"
                    class="inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                Sunting Account
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>

</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import axios from 'axios';
import { ref, onMounted, computed, provide, watch } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    id: {
        type: String,
    },
})

const accountData = ref({})
const coverageData = ref({})

const fetchAccount = async (id) => {
    try {
        const { data } = await axios.get('/resources/Account/' + id)
        const originalData = data
        accountData.value = originalData
        fetchCoverage(originalData.coverage[0].coverage.reference.split('/')[1])
    } catch (error) {
        console.error('Error fetching resources:', error)
        accountData.value = {}
    }
}

const fetchCoverage = async (id) => {
    try {
        const { data } = await axios.get('/resources/Coverage')
        const originalData = data
        coverageData.value = originalData.filter(item => item.id === id)
        // console.log(originalData)
    } catch (error) {
        console.error('Error fetching resources:', error)
        accountData.value = {}
    }
}

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
    fetchAccount(props.id)
})
</script>