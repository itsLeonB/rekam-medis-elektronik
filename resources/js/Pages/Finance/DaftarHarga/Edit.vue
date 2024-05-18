<template>
    <AuthenticatedLayout>
        <div v-if="item" class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <h1 class="text-2xl font-bold text-neutral-black-300">Sunting Katalog </h1>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">{{ item.display }}</p>

            <div class="relative overflow-x-auto mb-5">
                <form>
                    <table class="w-full text-base text-left text-neutral-grey-200 ">
                        <tbody>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                    Nama
                                </th>
                                <td class="px-6 py-4 w-2/3">
                                    <TextInput v-if="item" v-model="resourceForm.display" />
                                </td>
                            </tr>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                    Kode
                                </th>
                                <td class="px-6 py-4 w-2/3">
                                    {{ item.code }}
                                </td>
                            </tr>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                    Harga
                                </th>
                                <td class="px-6 py-4 w-2/3">
                                    {{ item.price.currency }} {{ item.price.value }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="flex flex-col items-center justify-end mt-10">
                <MainButton
                    class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0" type="submit">

                </MainButton>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import MainButton from '@/Components/MainButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, onMounted } from 'vue';

const item = ref(null)

const resourceForm = ref({
    code: '',
    display: '',
    price: 0,
});

const props = defineProps({
    item_id: {
        type: String,
    },
});

const fetchItem = async () => {
    const { data } = await axios.get('/catalogue/' + props.item_id);
    item.value = data
    resourceForm.code = item.value.code
    resourceForm.display = item.value.display
    resourceForm.price = item.value.price
    console.log(resourceForm)
}

onMounted(() => {
    fetchItem();
})
</script>