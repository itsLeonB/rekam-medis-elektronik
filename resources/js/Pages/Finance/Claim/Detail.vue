<template>
    <AuthenticatedLayout>
        <div class="bg-original-white-0  shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <!-- <div v-if="claimData">{{ claimData }} </div> -->
            <h1 class=" font-bold text-2xl">Detail Claim {{ claimData.id }}</h1>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Resource Type</h2>
                <p class="font-normal text-base">{{ claimData.resourceType }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Resource ID</h2>
                <p class="font-normal text-base">{{ claimData.id }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Status Claim</h2>
                <p class="font-normal text-base">{{ claimData.status }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Pasien</h2>
                <p class="font-normal text-base">{{ claimData.patient?.display }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Funds Reserve</h2>
                <p class="font-normal text-base">{{ claimData.fundsReserve?.coding?.display }}</p>
            </div>

            <div class="mt-4">

            </div>
            <div class="mt-4 justify-end flex gap-1">
                <!-- <Link v-if="['admin', 'keuangan'].includes($page.props.auth.user.roles[0].name)"
                    :href="route('finance.invoice.edit', { 'id': props.id })" as="button"
                    class="inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Cetak Claim
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

const claimData = ref({})
const chargeItemList = ref([])

const fetchClaim = async (id) => {
    try {
        const { data } = await axios.get('/resources/Claim/' + id)
        const originalData = data
        console.log(originalData)
        claimData.value = originalData
    } catch (error) {
        console.error('Error fetching resources:', error)
        claimData.value = {}
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
    fetchClaim(props.id)
    // getChargeItemList(props.id)
})
</script>