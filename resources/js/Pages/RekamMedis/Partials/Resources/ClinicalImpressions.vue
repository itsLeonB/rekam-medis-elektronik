<template>
    <div v-if="object" >
        <h1 class="text-lg font-semibold text-secondhand-orange-300">Prognosis</h1>
        <table v-for="item in object" class="w-full mx-auto text-base text-left text-neutral-grey-200 ">
            <tbody class="w-full">
                <tr class="bg-original-white-0">
                    <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                        Deskripsi
                    </th>
                    <td v-if="item.description" class="px-6 py-4 w-3/4">
                        {{ item.description }}
                    </td>
                </tr>
                <tr class="bg-original-white-0">
                    <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                        Investigasi
                    </th>
                    <td v-if="item.investigation" class="px-6 py-4 w-3/4">
                        {{ item.investigation[0].code.coding[0].display }}
                    </td>
                </tr>
                <tr class="bg-original-white-0">
                    <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                        Temuan
                    </th>
                    <td v-if="item.finding" class="px-6 py-4 w-3/4">
                        {{ item.finding[0].itemCodeableConcept.coding[0].display }}
                    </td>
                </tr>
                <tr class="bg-original-white-0">
                    <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                        Hasil Prognosis
                    </th>
                    <td v-if="item.prognosisCodeableConcept" class="px-6 py-4 w-3/4">
                        {{ item.prognosisCodeableConcept[0].coding[0].display }}
                    </td>
                </tr>
                <tr class="bg-original-white-0">
                    <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                        Ringkasan
                    </th>
                    <td v-if="item.summary" class="px-6 py-4 w-3/4">
                        {{ item.summary }}
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
</script>