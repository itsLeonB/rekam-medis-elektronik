<template>
    <table v-if="object" class="w-full mx-auto text-base text-left text-neutral-grey-200 ">
        <tbody class="w-full">
            <tr class="bg-original-white-0">
                <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                    Jenis Kunjungan
                </th>
                <td v-if="object.class" class="px-6 py-4 w-3/4">
                    {{
                        object.class.code == 'AMB' ? 'Rawat Jalan' : object.class.code ==
                            'IMP' ? 'Rawat Inap' :
                        object.class.code == 'EMER' ? 'IGD' : '' }}
                </td>
            </tr>
            <tr class="bg-original-white-0">
                <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                    Poli/Ruangan
                </th>
                <td v-if="object.serviceType" class="px-6 py-4 w-3/4">
                    <p>{{ object.serviceType.coding[0].display }}</p>
                    <div v-if="object.location">
                        <p v-for="loc in object.location">{{ loc.display }}</p>
                    </div>
                </td>
            </tr>
            <tr class="bg-original-white-0">
                <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                    DPJP
                </th>
                <td v-if="object.participant" class="px-6 py-4 w-3/4">
                    <div v-for="item in object.participant" class="mb-2">
                        <p>{{ item.individual.display }}</p>
                    </div>
                </td>
            </tr>
            <tr class="bg-original-white-0">
                <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                    Status
                </th>
                <td v-if="object.status" class="px-6 py-4 w-3/4">
                    {{ object.status }}
                </td>
            </tr>
            <tr class="bg-original-white-0">
                <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                    Riwayat Status
                </th>
                <td v-if="object.statusHistory" class="px-6 py-4 w-3/4">
                    <div v-for="item in object.statusHistory" class="mb-2">
                        <p>{{ item.status }}</p>
                        <p>Dimulai {{ formatTimestamp(item.period.start) }}</p>
                        <p v-if="item.period.end">Selesai {{ formatTimestamp(item.period.end) }}</p>
                    </div>
                </td>
            </tr>
            <tr class="bg-original-white-0">
                <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                    Discharge Disposition
                </th>
                <td v-if="object.hospitalization" class="px-6 py-4 w-3/4">
                    <p>{{ object.hospitalization.dischargeDisposition.coding[0].display }}</p>
                    <p>Catatan: {{ object.hospitalization.dischargeDisposition.text }}</p>
                </td>
            </tr>
        </tbody>
    </table>
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