<template>
    <div v-if="object">
        <h1 class="text-lg font-semibold text-secondhand-orange-300">Resep Obat</h1>
        <div class="mb-3" v-for="(item, index) in object" >
            <h4 class="text-base font-medium text-secondhand-orange-300">Obat {{ index + 1 }}</h4>
            <table class="w-full mx-auto text-base text-left text-neutral-grey-200 ">
                <tbody class="w-full">
                     <tr class="bg-original-white-0">
                        <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                            Nama Obat
                        </th>
                        <td v-if="item.medicationReference" class="px-6 py-2 w-3/4">
                            {{ item.medicationReference.display }}
                        </td>
                    </tr>
                    <tr class="bg-original-white-0">
                        <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                            Jenis Obat
                        </th>
                        <td v-if="item.dosageInstruction" class="px-6 py-2 w-3/4">
                             {{ item.dosageInstruction[0].route.coding[0].display }}
                        </td>
                    </tr>
                    <tr class="bg-original-white-0">
                        <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                            Dosis
                        </th>
                        <td v-if="item.dosageInstruction" class="px-6 py-2 w-3/4">
                             {{ item.dosageInstruction[0].timing.repeat.frequency }} x {{ item.dosageInstruction[0].timing.repeat.period }}/{{ item.dosageInstruction[0].timing.repeat.periodUnit }}
                        </td>
                    </tr>
                    <tr class="bg-original-white-0">
                        <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                            Keterangan
                        </th>
                        <td v-if="item.dosageInstruction" class="px-6 py-2 w-3/4">
                             {{ item.dosageInstruction[0].text }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>
</template>
<script setup>
import { Link, usePage} from '@inertiajs/vue3';
import { ref } from 'vue';
const props = defineProps({
    object: {
        type: Object,
        required: false
    },
});

function getEncounterCode(reference) {
    return reference.split('/')[1];
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

    return `${dayOfWeek}, ${day} ${month} ${year} ${hour}:${minute}`;
};

</script>