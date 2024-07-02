<template>
    <div v-if="object">
    <h1 class="text-lg font-semibold text-secondhand-orange-300">Compositions</h1>
    <table v-for="item in object" class="w-full mx-auto text-base text-left text-neutral-grey-200 ">
        <tbody class="w-full">
            <tr class="bg-original-white-0">
                <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                    Judul
                </th>
                <td v-if="item.title" class="px-6 py-4 w-3/4">
                    {{ item.title }}
                </td>
            </tr>
            <tr class="bg-original-white-0">
                <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/4">
                    Edukasi Diet
                </th>
                <td v-if="item.section" class="px-6 py-4 w-3/4">
                    {{ item.section[0].text.div }}
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