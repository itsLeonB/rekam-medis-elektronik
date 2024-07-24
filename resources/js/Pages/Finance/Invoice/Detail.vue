<template>
    <AuthenticatedLayout>
        <div class="bg-original-white-0  shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <!-- <div v-if="invoiceData">{{ invoiceData }} </div> -->
            <h1 class=" font-bold text-2xl">Detail Invoice {{ invoiceData.id }}</h1>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Resource Type</h2>
                <p class="font-normal text-base">{{ invoiceData.resourceType }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Resource ID</h2>
                <p class="font-normal text-base">{{ invoiceData.id }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Status Invoice</h2>
                <p class="font-normal text-base">{{ invoiceData.status }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Pasien</h2>
                <p class="font-normal text-base">{{ invoiceData.subject?.display }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Petugas</h2>
                <p class="font-normal text-base">{{ invoiceData.participant?.display }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Tanggal pembuatan Invoice</h2>
                <p class="font-normal text-base">{{ formatTimestamp(invoiceData.date) }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Penerbit Invoice</h2>
                <p class="font-normal text-base">{{ invoiceData.issuer?.display }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Pembayaran</h2>
                <p class="font-normal text-base">{{ invoiceData.paymentTerms }}, {{ invoiceData.note }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Account Pasien</h2>
                <p class="font-normal text-base">{{ invoiceData.account?.reference }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Total Tagihan</h2>
                <p class="font-normal text-base">{{ invoiceData.totalNet?.currency }} {{ invoiceData.totalNet?.value }}
                </p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Detail Tagihan</h2>
                <table class="w-full text-sm text-left rtl:text-right text-neutral-grey-200 col-span-3 shadow">
                    <thead class="text-sm text-neutral-black-300 uppercase bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-1/6">No.</th>
                            <th scope="col" class="px-6 py-3 w-2/6">Barang tagihan</th>
                            <th scope="col" class="px-6 py-3 w-2/6">Detail</th>
                            <th scope="col" class="px-6 py-3 w-1/6">Harga</th>
                        </tr>
                    </thead>
                    <tbody v-if="chargeItemList" v-for="(item, index) in chargeItemList" :key="index">
                        <tr class="bg-original-white-0 hover:bg-thirdinner-lightteal-300">
                            <td class="px-6 py-4 w-1/6"> {{ index + 1 }}</td>
                            <td class="px-6 py-4 w-2/6">{{ item.id }}</td>
                            <td class="px-6 py-4 w-2/6"> {{ item.context.display }}</td>
                            <td class="px-6 py-4 w-1/6">{{ item.priceOverride.currency }} {{
                                item.priceOverride.value }}</td>
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
                    :href="route('finance.invoice.edit', { 'id': props.id })" as="button"
                    class="inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Sunting Invoice
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

const invoiceData = ref({})
const chargeItemList = ref([])

const fetchInvoice = async (id) => {
    try {
        const { data } = await axios.get('/resources/Invoice/' + id)
        const originalData = data
        invoiceData.value = originalData

        await Promise.all(originalData.lineItem.map(async (item) => {
            try {
                const cId = item.chargeItem.chargeItemReference.reference.split('/')[1];
                const { data } = await axios.get('/resources/ChargeItem/' + cId)
                const cData = data
                chargeItemList.value.push(cData)
            } catch (error) {
                console.error(`Error fetching charge item with ID ${id}:`, error);
            }
        }))
    } catch (error) {
        console.error('Error fetching resources:', error)
        invoiceData.value = {}
    }
}

const getChargeItemList = async (id) => {
    try {
        const { data } = await axios.get('/resources/ChargeItem')
        const originalData = data
        const filteredData = originalData.filter(item => item.context.reference = `Encounter/${id}`)
        chargeItemList.value = filteredData
    } catch (error) {
        console.error('Error fetching resources:', error)
        chargeItemList.value = {}
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
    fetchInvoice(props.id)
    // getChargeItemList(props.id)
})
</script>