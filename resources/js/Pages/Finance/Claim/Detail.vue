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
                <h2 class="font-bold text-lg">Priority Claim</h2>
                <p class="font-normal text-base">{{ claimData.priority?.coding?.display }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Tipe Claim</h2>
                <p class="font-normal text-base">{{ claimData.type?.coding?.display }}</p>
            </div>
            <div class="mt-4" v-if="claimData?.subType">
                <h2 class="font-bold text-lg">Sub Tipe Claim</h2>
                <p class="font-normal text-base">{{ claimData.subType?.coding?.display }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Use Claim</h2>
                <p class="font-normal text-base">{{ claimData.use }}</p>
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
                <h2 class="font-bold text-lg">Payee</h2>
                <p class="font-normal text-base">{{ claimData.payee?.party?.display }} | {{
                    claimData.payee?.type?.coding?.display }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Insurer</h2>
                <p class="font-normal text-base">{{ claimData.insurer?.display }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Provider</h2>
                <p class="font-normal text-base">{{ claimData.provider?.display }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Facility</h2>
                <p class="font-normal text-base">{{ claimData.facility?.display }}</p>
            </div>
            <div class="mt-4">
                <h2 class="font-bold text-lg">Prescription</h2>
                <p class="font-normal text-base">{{ claimData.prescription?.display }}</p>
            </div>
            <div class="mt-4" v-if="claimData?.originalPrescription">
                <h2 class="font-bold text-lg">Original Prescription (jika dibutuhkan)</h2>
                <p class="font-normal text-base">{{ claimData.originalPrescription?.display }}</p>
            </div>

            <div class="mt-4">
                <h2 class="font-bold text-lg">Waktu penagihan</h2>
                <p class="font-normal text-base">{{ formatTimestamp(claimData.billablePeriod?.start),
                    formatTimestamp(claimData.billablePeriod?.end) }}</p>
            </div>

            <div class="mt-4">
                <h2 class="font-bold text-lg">Total tagihan</h2>
                <p class="font-normal text-base">{{ claimData?.total?.currency }} {{ claimData?.total?.value }}</p>
            </div>

            <div class="mt-4">
                <h2 class="font-bold text-lg">Tindakan Medis</h2>
                <table class="w-full text-sm text-left rtl:text-right text-neutral-grey-200 col-span-3 shadow">
                    <thead class="text-sm text-neutral-black-300 uppercase bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-1/6">No.</th>
                            <th scope="col" class="px-6 py-3 w-2/6">ID Tindakan</th>
                            <th scope="col" class="px-6 py-3 w-1/6">Kode Tindakan</th>
                            <th scope="col" class="px-6 py-3 w-2/6">Detail</th>
                        </tr>
                    </thead>
                    <tbody v-if="procedureList" v-for="(item, index) in procedureList" :key="index">
                        <tr class="bg-original-white-0 hover:bg-thirdinner-lightteal-300">
                            <td class="px-6 py-4 w-1/6"> {{ index + 1 }}</td>
                            <td class="px-6 py-4 w-2/6">{{ item?.procedureReference?.reference }}</td>
                            <td class="px-6 py-4 w-1/6"> {{
                                item.procedureDetail?.code?.coding[0]?.code
                            }}</td>
                            <td class="px-6 py-4 w-2/6"> {{ item.procedureDetail?.code?.coding[0]?.display }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <h2 class="font-bold text-lg">Diagnosis</h2>
                <table class="w-full text-sm text-left rtl:text-right text-neutral-grey-200 col-span-3 shadow">
                    <thead class="text-sm text-neutral-black-300 uppercase bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-1/6">No.</th>
                            <th scope="col" class="px-6 py-3 w-2/6">ID Diagnosis</th>
                            <th scope="col" class="px-6 py-3 w-1/6">Kode Diagnosis</th>
                            <th scope="col" class="px-6 py-3 w-2/6">Detail</th>
                        </tr>
                    </thead>
                    <tbody v-if="diagnosisList" v-for="(item, index) in diagnosisList" :key="index">
                        <tr class="bg-original-white-0 hover:bg-thirdinner-lightteal-300">
                            <td class="px-6 py-4 w-1/6"> {{ index + 1 }}</td>
                            <td class="px-6 py-4 w-2/6">{{ item?.diagnosisReference?.reference }}</td>
                            <td class="px-6 py-4 w-1/6"> {{
                                item.diagnosisDetail?.code?.coding[0]?.code
                            }}</td>
                            <td class="px-6 py-4 w-2/6"> {{ item.diagnosisDetail?.code?.coding[0]?.display }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <h2 class="font-bold text-lg">Care Team</h2>
                <table class="w-full text-sm text-left rtl:text-right text-neutral-grey-200 col-span-3 shadow">
                    <thead class="text-sm text-neutral-black-300 uppercase bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-1/6">No.</th>
                            <th scope="col" class="px-6 py-3 w-2/6">Nama</th>
                            <th scope="col" class="px-6 py-3 w-1/6">Peran</th>
                            <th scope="col" class="px-6 py-3 w-2/6">Penanggungjawab?</th>
                        </tr>
                    </thead>
                    <tbody v-if="claimData.careTeam" v-for="(item, index) in claimData.careTeam" :key="index">
                        <tr class="bg-original-white-0 hover:bg-thirdinner-lightteal-300">
                            <td class="px-6 py-4 w-1/6"> {{ index + 1 }}</td>
                            <td class="px-6 py-4 w-2/6">{{ item?.provider?.display }}</td>
                            <td class="px-6 py-4 w-1/6"> {{
                                item.role?.coding?.display
                            }}</td>
                            <td class="px-6 py-4 w-2/6"> {{ item.responsible ? "Ya" : "Tidak" }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <h2 class="font-bold text-lg">Insurance</h2>
                <table class="w-full text-sm text-left rtl:text-right text-neutral-grey-200 col-span-3 shadow">
                    <thead class="text-sm text-neutral-black-300 uppercase bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-1/6">No.</th>
                            <th scope="col" class="px-6 py-3 w-2/6">ID Insurance</th>
                            <th scope="col" class="px-6 py-3 w-1/6">Jenis</th>
                            <th scope="col" class="px-6 py-3 w-2/6">Focal?</th>
                        </tr>
                    </thead>
                    <tbody v-if="coverageList" v-for="(item, index) in coverageList" :key="index">
                        <tr class="bg-original-white-0 hover:bg-thirdinner-lightteal-300">
                            <td class="px-6 py-4 w-1/6"> {{ index + 1 }}</td>
                            <td class="px-6 py-4 w-2/6">{{ item?.insuranceDetail?.id }}</td>
                            <td class="px-6 py-4 w-1/6"> {{
                                item?.insuranceDetail?.type?.coding[0]?.display
                            }}</td>
                            <td class="px-6 py-4 w-2/6"> {{ item.focal ? "Ya" : "Tidak" }}</td>
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
                Cetak Claim
                </Link> -->
                <Link v-if="['admin', 'keuangan'].includes($page.props.auth.user.roles[0].name)"
                    :href="route('finance.claim.edit', { 'id': props.id })" as="button"
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

const procedureList = ref([])
const diagnosisList = ref([])
const coverageList = ref([])
const claimData = ref({ procedure: [], diagnosis: [], insurance: [] })


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

const getProcedure = async (id) => {
    try {
        const { data } = await axios.get('/resources/Procedure/' + id)
        const originalData = data
        console.log(originalData)

        return originalData
    } catch (error) {
        console.error('Error fetching resources:', error)
        return {}
    }
}

const getCondition = async (id) => {
    try {
        const { data } = await axios.get('/resources/Condition/' + id)
        const originalData = data
        console.log(originalData)
        return originalData
    } catch (error) {
        console.error('Error fetching resources:', error)
        return {}
    }
}

const getCoverage = async (id) => {
    try {
        const { data } = await axios.get('/resources/Coverage/' + id)
        const originalData = data
        console.log(originalData)
        return originalData
    } catch (error) {
        console.error('Error fetching resources:', error)
        return {}
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

onMounted(async () => {
    await fetchClaim(props.id)
    if (claimData.value.procedure) {
        const procedures = await Promise.all(
            claimData.value.procedure.map(async item => ({
                procedureDetail: await getProcedure(item.procedureReference.reference.split("/")[1]),
                ...item,
            }))
        );

        procedureList.value = procedures;
    } else {
        console.error('Procedure data is missing in the fetched data');
    }

    if (claimData.value.diagnosis) {
        const diagnosis = await Promise.all(
            claimData.value.diagnosis.map(async item => ({
                diagnosisDetail: await getCondition(item.diagnosisReference.reference.split("/")[1]),
                ...item,
            }))
        );

        diagnosisList.value = diagnosis;
    } else {
        console.error('Diagnosis data is missing in the fetched data');
    }

    if (claimData.value.insurance) {
        const insurance = await Promise.all(
            claimData.value.insurance.map(async item => ({
                insuranceDetail: await getCoverage(item.coverage.reference.split("/")[1]),
                ...item,
            }))
        );

        coverageList.value = insurance;
    } else {
        console.error('Insurance data is missing in the fetched data');
    }
    // getChargeItemList(props.id)
})
</script>