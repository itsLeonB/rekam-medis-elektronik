<template>
    <div v-if="object">
        <h1 class="text-lg font-semibold text-secondhand-orange-300">Questionnaire Responses</h1>
        <div v-for="(item, index) in object" class="mb-3"> -->
            <h2 class="text-base font-medium text-secondhand-orange-300">Obat: {{ index + 1 }}</h2>
            <table class="w-full mx-auto text-base text-left text-neutral-grey-200 ">
                <tbody class="w-full">
                    <tr class="bg-original-white-0">
                        <th scope="row" class="px-6 py-2 font-semibold whitespace-nowrap w-1/4">
                           Jenis Obat
                        </th>
                        <td class="px-6 py-2 w-3/4">
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
</script>