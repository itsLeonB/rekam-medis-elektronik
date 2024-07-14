<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>{{ isEditMode ? 'Edit Transaksi' : 'Tambah Transaksi' }} - </title>
        </template>
        <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <h1 class="text-2xl font-bold text-neutral-black-300">{{ isEditMode ? 'Edit Transaksi' : 'Tambah Transaksi' }}</h1>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">{{ isEditMode ? 'Halaman untuk mengedit transaksi obat.' : 'Halaman untuk menambahkan transaksi obat.' }}</p>
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="id_transaction" value="ID Transaksi" />
                    <TextInput id="id_transaction" type="text" class="mt-1 block w-full" required v-model="form.id_transaction"
                        placeholder="Masukkan ID Transaksi" />
                    <InputError class="mt-1" :message="form.errors.id_transaction" />
                </div>

                <div class="mt-4">
                    <InputLabel for="id_medicine" value="Nama Obat" />
                    <input type="text" v-model="searchQuery" @input="searchMedicines" placeholder="Cari obat..." class="mt-1 block w-full" />
                    <!-- <div>Selected: {{ form.id_medicine }}</div> -->
                    <select v-model="form.id_medicine" @change="updateQuantity" class="mt-1 block w-full" required>
                        <option disabled value="">Pilih Obat</option>
                        <option v-for="medicine in medicines" :key="medicine._id" :value="medicine._id">{{ medicine.name }}</option>
                    </select>
                    <!-- <div>Selected: {{ form.id_medicine }}</div> -->
                </div>

                <div class="mt-4">
                    <InputLabel for="quantity" :value="'Quantity (maksimum '+ quantityMax +')'" />
                    <TextInput id="quantity" type="number" class="mt-1 block w-full" required v-model="form.quantity"
                        :min="quantityMax" :placeholder="'Masukkan Quantity (maksimum ' + quantityMax + ')'"
                         />
                    <InputError class="mt-1" :message="form.errors.quantity" />
                </div>

                <div class="mt-4">
                    <InputLabel for="note" value="Catatan" />
                    <TextInput id="note" type="text" class="mt-1 block w-full" required v-model="form.note"
                        placeholder="Masukkan Catatan" />
                    <InputError class="mt-1" :message="form.errors.note" />
                </div>

                <div class="flex flex-col items-center justify-end mt-10">
                    <MainButton class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0" type="submit">
                        Simpan
                    </MainButton>
                </div>
            </form>
        </div>
        <Modal :show="creationSuccessModal">
            <div class="p-6">
                <h2 class="text-lg text-center font-medium text-gray-900">
                    Data transaksi berhasil ditambahkan. <br> Kembali ke halaman transakasi.
                </h2>
                <div class="mt-6 flex justify-end">
                    <Link :href="route('medicinetransaction')"
                        class="mx-auto mb-3 w-fit block justify-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Kembali </Link>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import MainButton from '@/Components/MainButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

const form = useForm({
    _id: '',
    id_transaction:  '',
    id_medicine:  '', // Initialize id_medicine
    quantity: '',
    note: '',
});

const medicines = ref([]);
const searchQuery = ref('');
let quantityMax = ref(null); 
let isEditMode = ref(false);

const searchMedicines = async () => {
    try {
        const response = await axios.get(route('getmedicine', { search: searchQuery.value }));
        medicines.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

onMounted(() => {
    searchMedicines();
    const data = window.location.search;
    if(data.length > 10){
        isEditMode = true;
        getSearchParamURL(data);
    }

});

const getSearchParamURL = (data) => {
    // Mengambil query parameters dari URL
    const queryString = data;
    const urlParams = new URLSearchParams(queryString);
    const transactionData = {};

    for (const [key, value] of urlParams.entries()) {
        if (key.startsWith('transaction[')) {
            // If the key starts with 'transaction[', parse it accordingly
            const keyWithoutPrefix = key.replace('transaction[', '').replace(']', '');
            const nestedKeys = keyWithoutPrefix.split('][');
            let currentObject = transactionData;

            for (let i = 0; i < nestedKeys.length; i++) {
                const nestedKey = nestedKeys[i];
                if (i === nestedKeys.length - 1) {
                    // Last nested key, assign the value
                    currentObject[nestedKey] = value;
                } else {
                    // Nested object doesn't exist yet, create it
                    if (!currentObject[nestedKey]) {
                        currentObject[nestedKey] = {};
                    }
                    currentObject = currentObject[nestedKey];
                }
            }
        } else {
            // Handle other query parameters if needed
            transactionData[key] = value;
        }
    }
    form._id = transactionData._id;
    form.id_transaction = transactionData.id_transaction;
    form.id_medicine = transactionData.id_medicine; // Access as object property
    form.quantity = transactionData.quantity;
    form.note = transactionData.note;
    quantityMax.value = parseInt(transactionData['medicine[quantity]']) + parseInt(transactionData.quantity);
    // console.log('Transaction Data:', transactionData['medicine[quantity]']);
}
const updateQuantity = () => {
    const selectedMedicine = medicines.value.find(medicine => medicine._id === form.id_medicine);

    if (selectedMedicine) {
        // Set quantityMax to selectedMedicine.quantity
        quantityMax.value = -(selectedMedicine.quantity);

        // Validate quantity
        // if (form.quantity > quantityMax.value) {
        //     console.error(`Quantity tidak boleh lebih dari ${quantityMax.value}`);
        //     // Set form.quantity to quantityMax if it exceeds
        //     form.quantity = quantityMax.value;
        // }
    }
};

const submit = async () => {
    // Validate id_medicine
    if (!form.id_medicine) {
        console.error('Pilih obat terlebih dahulu');
        return;
    }

    // Validate quantity against quantityMax
    // if (form.quantity > quantityMax.value) {
    //     console.error(`Quantity tidak boleh lebih dari ${quantityMax.value}`);
    //     return;
    // }

    const routeName = isEditMode === true ? 'medicinetransactions.update' : 'medicinetransactions.store';
    const method = isEditMode === true ? 'put' : 'post';
    
    try {
        await form[method](route(routeName, { id: form._id ? form._id : undefined }), {
            preserveScroll: true,
            onFinish: () => form.reset(),
        });
    } catch (error) {
        console.error('Gagal menyimpan transaksi:', error);
        // Handle error, show error message, etc.
    }
};
</script>

