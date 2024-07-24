<template>
    <AuthenticatedLayout>
        <Modal :show="updateSuccessModal">
            <div class="p-6">
                <h2 class="text-lg text-center font-medium text-gray-900">
                    Data katalog telah berhasil diperbaharui. <br> Kembali ke halaman Detail Katalog.
                </h2>
                <div class="mt-6 flex justify-end">
                    <Link :href="route('finance.catalogue.detail', { 'id': item.code })"
                        class="mx-auto mb-3 w-fit block justify-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Kembali </Link>
                </div>
            </div>
        </Modal>
        <div v-if="item" class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <h1 class="text-2xl font-bold text-neutral-black-300">Sunting Katalog </h1>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">{{ item.display }}</p>

            <div class="relative overflow-x-auto mb-5">
                <form @submit.prevent="submit">
                    <table class="w-full text-base text-left text-neutral-grey-200 ">
                        <tbody>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                    Nama
                                </th>
                                <td class="px-6 py-4 w-2/3">
                                    <TextInput v-if="item" id="display" v-model="form.display" />
                                </td>
                            </tr>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                    Kode
                                </th>
                                <td class="px-6 py-4 w-2/3">
                                    <TextInput id="code" v-model="form.code" />
                                </td>
                            </tr>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                    Harga
                                </th>
                                <td class="px-6 py-4 w-2/3">
                                    <TextInput id="value" v-model="form.price.value" />
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex flex-col items-center justify-end mt-10">
                        <MainButton class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0"
                            type="submit">
                            Submit
                        </MainButton>
                    </div>
                </form>

            </div>

        </div>

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import MainButton from '@/Components/MainButton.vue';
import TextInput from '@/Components/TextInput.vue';
import axios from 'axios';
import Modal from '@/Components/Modal.vue';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const item = ref(null);
const updateSuccessModal = ref(false);
const form = ref({
    code: '',
    display: '',
    price: {
        currency: "IDR",
        value: 0,
    },
})

const props = defineProps({
    item_id: {
        type: String,
    },
});

const fetchItem = async () => {
    const { data } = await axios.get('/catalogue/' + props.item_id);
    item.value = data
    form.value.code = item.value.code
    form.value.display = item.value.display
    form.value.price.value = item.value.price.value
}

const submit = async () => {
    const submitForm = {
        "code": form.value.code,
        "display": form.value.display,
        "price": {
            "currency": form.value.price.currency,
            "value": form.value.price.value
        }
    }
    try {
        const response = await axios.put('/catalogue/' + props.item_id, submitForm)
            .then(response => {
                updateSuccessModal.value = true;
            })
    } catch (error) {
        console.error(error);
    }
};

onMounted(() => {
    fetchItem();
})

</script>