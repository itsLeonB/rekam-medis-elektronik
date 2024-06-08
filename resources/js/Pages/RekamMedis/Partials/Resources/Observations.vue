<template>
    <div v-if="object">
        <h1 class="text-lg font-semibold text-secondhand-orange-300">Observations</h1>
        <div v-for="(item, index) in object" class="mb-3">
            <h2 v-if="item.code" class="text-base font-medium text-secondhand-orange-300">Observasi - {{ item.code.coding[0].display }}</h2>
            <h2 v-else class="text-base font-medium text-secondhand-orange-300">Observasi {{ index + 1 }}</h2>
            <table class="w-full mx-auto text-base text-left text-neutral-grey-200 ">
                <tbody class="w-full">
                    <tr v-if="item.bodySite"  class="bg-original-white-0">
                        <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                            Body Site
                        </th>
                        <td class="px-6 py-2 w-3/4">
                            {{ item.bodySite.coding[0].display }}
                        </td>
                    </tr>
                    <tr class="bg-original-white-0">
                        <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                            Hasil Observasi
                        </th>
                        <td class="px-6 py-2 w-3/4">
                            <p v-if="item.valueString">{{ item.valueString }}</p>
                            <p v-if="item.valueBoolean">{{ item.valueBoolean }}</p>
                            <p v-if="item.valueInteger">{{ item.valueInteger }}</p>
                            <p v-if="item.valueQuantity">{{ item.valueQuantity.value }} {{ item.valueQuantity.unit }}</p>
                            <p v-if="item.interpretation">Interpretasi: {{ item.interpretation[0].text }}</p>
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