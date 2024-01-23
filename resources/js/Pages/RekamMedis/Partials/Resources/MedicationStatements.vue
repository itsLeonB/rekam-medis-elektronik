<template>
    <div v-if="object">
        <h1 class="text-lg font-semibold text-secondhand-orange-300">Riwayat Pengobatan</h1>
        <div v-for="(item, index) in object" class="mb-3">
            <h2 class="text-base font-medium text-secondhand-orange-300">Obat: {{ index + 1 }}</h2>
            <table class="w-full mx-auto text-base text-left text-neutral-grey-200 ">
                <tbody class="w-full">
                    <tr class="bg-original-white-0">
                        <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                           Jenis Obat
                        </th>
                        <td v-if="item.medicationCodeableConcept" class="px-6 py-2 w-3/4">
                            {{ item.medicationCodeableConcept.coding[0].display }} | Kode KFA: {{ item.medicationCodeableConcept.coding[0].code }}
                        </td>
                    </tr>
                    <tr v-if="item.bodySite"  class="bg-original-white-0">
                        <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                            Clinical Status
                        </th>
                        <td v-if="item.status" class="px-6 py-2 w-3/4">
                            {{ item.status }}
                        </td>
                    </tr>
                    <tr class="bg-original-white-0">
                        <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                            Alasan Konsumsi Obat
                        </th>
                        <td v-if="item.reasonCode" class="px-6 py-2 w-3/4">
                            {{ item.reasonCode[0].coding[0].display }}
                        </td>
                    </tr>
                    <tr class="bg-original-white-0">
                        <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                           Kategori
                        </th>
                        <td v-if="item.category" class="px-6 py-2 w-3/4">
                            {{ item.category.coding[0].display }}
                        </td>
                    </tr>
                    <tr class="bg-original-white-0">
                        <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                           Waktu Konsumsi Obat
                        </th>
                        <td v-if="item.effectiveDateTime" class="px-6 py-2 w-3/4">
                            {{ formatTimestamp(item.effectiveDateTime) }}
                        </td>
                    </tr>
                    <tr class="bg-original-white-0">
                        <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                           Dosis Obat
                        </th>
                        <td v-if="item.dosage" class="px-6 py-2 w-3/4">
                            {{ item.dosage[0].text }}
                        </td>
                    </tr>
                    <tr class="bg-original-white-0">
                        <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                           Keterangan
                        </th>
                        <td v-if="item.note" class="px-6 py-2 w-3/4">
                            {{ item.note[0].text }}
                        </td>
                    </tr>
                </tbody>
            </table>
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