<template>
    <div v-if="object" class="space-y-5">
        <div v-if="object.diagnosis">
            <h1 class="text-lg font-semibold text-secondhand-orange-300">Diagnosis</h1>
            <div v-for="(diag, index) in object.diagnosis" class="mb-3">
                <h2 class="text-base font-medium text-secondhand-orange-300">Diagnosis {{ index + 1 }}</h2>
                <table class="w-full mx-auto text-base text-left text-neutral-grey-200 ">
                    <tbody class="w-full">
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                                Clinical Status
                            </th>
                            <td v-if="diag.clinicalStatus" class="px-6 py-2 w-3/4">
                                {{ diag.clinicalStatus.coding[0].display }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                                Verification Status
                            </th>
                            <td v-if="diag.verificationStatus" class="px-6 py-2 w-3/4">
                                {{ diag.verificationStatus.coding[0].display }}

                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                                Diagnosis
                            </th>
                            <td v-if="diag.code" class="px-6 py-2 w-3/4">
                                {{ diag.code.coding[0].display }}

                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                                Catatan
                            </th>
                            <td v-if="diag.note" class="px-6 py-2 w-3/4">
                                {{ diag.note[0].text }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-if="object.lainnya">
            <h1 class="text-lg font-semibold text-secondhand-orange-300">Keluhan, Riwayat Penyakit, dan Kondisi lainnya</h1>
            <div v-for="(diag, index) in object.lainnya" class="mb-3">
                <h2 class="text-base font-medium text-secondhand-orange-300">Kondisi {{ index + 1 }}</h2>
                <table class="w-full mx-auto text-base text-left text-neutral-grey-200 ">
                    <tbody class="w-full">
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                                Clinical Status
                            </th>
                            <td v-if="diag.clinicalStatus" class="px-6 py-2 w-3/4">
                                {{ diag.clinicalStatus.coding[0].display }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                                Keluhan/Riwayat Penyakit
                            </th>
                            <td v-if="diag.code" class="px-6 py-2 w-3/4">
                                {{ diag.code.coding[0].display }} | Code: {{ diag.code.coding[0].code }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-if="object['asesmen-harian']">
            <h1 class="text-lg font-semibold text-secondhand-orange-300">Asesmen Harian</h1>
            <div v-for="(diag, index) in object['asesmen-harian']" class="mb-3">
                <h2 class="text-base font-medium text-secondhand-orange-300">Kondisi {{ index + 1 }}</h2>
                <table class="w-full mx-auto text-base text-left text-neutral-grey-200 ">
                    <tbody class="w-full">
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                                Clinical Status
                            </th>
                            <td v-if="diag.clinicalStatus" class="px-6 py-2 w-3/4">
                                {{ diag.clinicalStatus.coding[0].display }}
                                <span v-if="diag.clinicalStatus.coding[0].code === 'resolved'"> 
                                {{ formatTimestamp(diag.abatementDateTime) }}</span>
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                                Keluhan/Riwayat Penyakit
                            </th>
                            <td v-if="diag.code" class="px-6 py-2 w-3/4">
                                {{ diag.code.coding[0].display }} | Code: {{ diag.code.coding[0].code }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                                Catatan
                            </th>
                            <td v-if="diag.note" class="px-6 py-2 w-3/4">
                                {{ diag.note[0].text }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script setup>
const props = defineProps({
    object: {
        type: Object,
        required: false
    },
});

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

    return `${dayOfWeek}, ${day} ${month} ${year} ${hour}:${minute}`;
};
</script>