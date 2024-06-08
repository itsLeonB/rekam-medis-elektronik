<template>
    <div v-if="object" >
        <h1 class="text-lg font-semibold text-secondhand-orange-300">Rencana Tindak Lanjut</h1>
        <table v-for="item in object" class="w-full mx-auto text-base text-left text-neutral-grey-200 ">
            <tbody class="w-full">
                <tr class="bg-original-white-0">
                    <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                        Tindak Lanjut
                    </th>
                    <td v-if="item.code" class="px-6 py-4 w-3/4">
                        <p>{{ item.code.coding[0].display }}</p>
                        <p>Keterangan: {{ item.code.text }}</p>
                    </td>
                </tr>
                <tr class="bg-original-white-0">
                    <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                        Prioritas
                    </th>
                    <td v-if="item.priority" class="px-6 py-4 w-3/4">
                        {{ item.priority }}
                    </td>
                </tr>
                <tr class="bg-original-white-0">
                    <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                        Rencana Waktu
                    </th>
                    <td v-if="item.occurrenceDateTime" class="px-6 py-4 w-3/4">
                        {{ formatTimestamp(item.occurrenceDateTime) }}
                    </td>
                </tr>
                <tr class="bg-original-white-0">
                    <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                        Alasan dilakukan Tindak Lanjut
                    </th>
                    <td v-if="item.reasonCode" class="px-6 py-4 w-3/4">
                        <p>{{ item.reasonCode[0].coding[0].display }}</p>
                        <p>Keterangan: {{ item.reasonCode[0].text }}</p>
                    </td>
                </tr>
                <tr class="bg-original-white-0">
                    <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                        Instruksi Tindak Lanjut
                    </th>
                    <td v-if="item.patientInstruction" class="px-6 py-4 w-3/4">
                        {{ item.patientInstruction }}
                    </td>
                </tr>
            </tbody>
        </table>
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