<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Tambah Obat - </title>
        </template>
        <Modal :show="creationSuccessModal">
            <div class="p-6">
                <h2 class="text-lg text-center font-medium text-gray-900">
                    Data Obat berhasil ditambahkan. <br> Kembali ke halaman Obat.
                </h2>
                <div class="mt-6 flex justify-end">
                    <Link :href="route('medication.tambah')"
                        class="mx-auto mb-3 w-fit block justify-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Ok </Link>
                </div>
            </div>
        </Modal>
        <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <div class="header">
                <h1 class="text-2xl font-bold text-neutral-black-300">Tambah Obat</h1>
                <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman untuk menambahkan obat.</p>
                <!-- <MainButton @click.prevent="isMedicineInAPI"
                    class="w-full mb-3 mx-auto  block teal-button text-original-white-0">
                    {{ MedicineAPI ? 'Tidak menemukan obat yang dicari?' : 'Gunakan data obat?' }}
                </MainButton> -->
            </div>
            <form @submit.prevent="addMedication">
                <div v-if="MedicineAPI">
                    <InputLabel for="code_obat" value="Kode Obat" />
                    <Multiselect v-model="form.code_obat" mode="single" placeholder="Obat" :filter-results="false"
                        :object="true" :min-chars="1" :resolve-on-load="false" :delay="1000" :searchable="true"
                        :options="searchMedication" label="name" valueProp="kfa_code" track-by="kfa_code" class="mt-1"
                        :classes="combo_classes" required />
                </div>
                <div v-if="!MedicineAPI">
                    <div>
                        <InputLabel for="code_obat" value="Kode Obat" />
                        <FieldInput v-model="form.medicine_code" placeholder="Kode Obat" required />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="nama obat" value="Nama Obat" />
                        <FieldInput v-model="form.name" placeholder="Nama Obat" required />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="uom" value="Unit of Measurement" />
                        <FieldInput v-model="form.uom" placeholder="Satuan" required />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="package" value="Kemasan Obat" />
                        <FieldInput v-model="form.package" placeholder="Kemasan Obat" required />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="dosage_form" value="Bentuk Dosis" />
                        <FieldInput v-model="form.dosage_form" placeholder="Bentuk Dosis" required />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="manufacturer" value="Produsen" />
                        <FieldInput v-model="form.manufacturer" placeholder="Produsen" required />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="is_active" value="Memiliki Bahan Aktif" />
                        <input v-model="form.medicine_code" type="checkbox" />
                    </div>

                </div>
                <div class="mt-4">
                    <InputLabel for="is_fast_moving" value="Fast Moving" />
                    <input v-model="form.is_fast_moving" type="checkbox" id="is_fast_moving" />
                </div>
                <div class="mt-4">
                    <InputLabel for="exp_date" value="Tanggal Kadaluarsa" />
                    <TextInput v-model="form.expiry_date" type="date" placeholder="Expiry Date" required />
                </div>
                <div class="mt-4">
                    <InputLabel for="quantity" value="Jumlah" />
                    <FieldInput v-model="form.quantity" type="number" placeholder="Jumlah" />
                </div>
                <div class="mt-4">
                    <InputLabel for="minimum_quantity" value="Jumlah stok minimum" />
                    <FieldInput v-model="form.minimum_quantity" type="number" placeholder="Jumlah" />
                </div>
                <div class="mt-4">
                    <InputLabel for="amount_per_package" value="Jumlah per Kemasan" />
                    <FieldInput v-model="form.amount_per_package" type="number" placeholder="Jumlah" />
                </div>
                <div class="mt-4">
                    <label for="base_price">Harga Dasar</label>
                    <FieldInput v-model="form.prices.base_price" type="number" placeholder="Harga Dasar" />
                </div>
                <div class="mt-4">
                    <label for="purchase_price">Harga Beli</label>
                    <FieldInput v-model="form.prices.purchase_price" type="number" placeholder="Harga Beli" />
                </div>

                <div class="mt-4" v-for="(price, index) in additionalPrices" :key="index">
                    <label :for="'treatment_price_' + (index + 1)">Harga Pengobatan {{ index + 1 }}</label>
                    <FieldInput v-model="price.value" type="number" :placeholder="'Harga Pengobatan ' + (index + 1)" />
                </div>
                <MainButton class="mt-4 w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0"
                    @click.prevent="addPrice" :disabled="additionalPrices.length >= 9">Tambahkan Harga</MainButton>

                <div v-if="!MedicineAPI" class="mt-4" v-for="(ingredient, index) in form.ingredients" :key="index">
                    <label :for="'ingredient_' + (index + 1)">Bahan {{ index + 1 }}</label>
                    <FieldInput v-model="ingredient.ingredient_name" type="text"
                        :placeholder="'Bahan ' + (index + 1)" />
                </div>
                <MainButton v-if="!MedicineAPI" :disabled="form.ingredients.length >= 9"
                    class="mt-4 w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0"
                    @click.prevent="addIngredient">Tambahkan Bahan Aktif</MainButton>

                <div class="flex flex-col items-center justify-end mt-10">
                    <MainButton type="submit"
                        class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0">
                        Tambah
                    </MainButton>
                </div>
                <p v-if="successAlertVisible" class="text-sm text-original-teal-300">Sukses!</p>
                <p v-if="failAlertVisible" class="text-sm text-thirdouter-red-300">Gagal!</p>
                <p v-if="errorMessage" class="text-red-500">{{ errorMessage }}</p>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import MainButton from '@/Components/MainButton.vue';
import '@vueform/multiselect/themes/default.css';
import Multiselect from '@vueform/multiselect';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SecondaryButtonSmall from '@/Components/SecondaryButtonSmall.vue';
import DeleteButton from '@/Components/DeleteButton.vue';
import { Link } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import FieldInput from '@/Components/FieldInput.vue';

const form = useForm({
    code_obat: '',
    medicine_code: '',
    name: '',
    expiry_date: '',
    quantity: '',
    package: '',
    uom: '',
    amount_per_package: '',
    manufacturer: '',
    is_fast_moving: false,
    is_active: false,
    minimum_quantity: '',
    dosage_form: '',
    extension: '',
    prices: {
        base_price: '',
        purchase_price: '',
        treatment_price_1: '',
        treatment_price_2: '',
        treatment_price_3: '',
        treatment_price_4: '',
        treatment_price_5: '',
        treatment_price_6: '',
        treatment_price_7: '',
        treatment_price_8: '',
        treatment_price_9: '',
    },
    ingredients: [
        { ingredient_name: '' }
    ]

});

const MedicineAPI = ref(true);

const isMedicineInAPI = () => {
    MedicineAPI.value = !MedicineAPI.value;
}

const additionalPrices = ref([]);

const addPrice = () => {
    if (additionalPrices.value.length < 9) {
        additionalPrices.value.push({ value: '' });
    }
};

const addIngredient = () => {
    form.ingredients.push({ ingredient_name: '' });
};

const searchMedication = async (query) => {
    const { data } = await axios.get(route('terminologi.medication'), {
        params: {
            'page': 1,
            'size': 10,
            'product_type': 'farmasi',
            'keyword': query
        }
    });
    const originalData = data.items.data;
    return originalData;
}

const organizationRef = ref(null);
const getorganizationRef = async () => {
    const { data } = await axios.get(route('form.ref.organization', { layanan: 'induk' }));
    organizationRef.value = data;
};
const medicationExtension = ref(null);
const getMedicationExtension = async () => {
    const { data } = await axios.get(route('terminologi.get'), {
        params: {
            'resourceType': 'Medication',
            'attribute': 'medicationType'
        }
    });
    medicationExtension.value = data;
};

onMounted(() => {
    getMedicationExtension();
    getorganizationRef();
});

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);
const errorMessage = ref('');
const creationSuccessModal = ref(false);
const isLoading = ref(false);

const submitForm = async () => {
    isLoading.value = true;
    additionalPrices.value.forEach((price, index) => {
        form.prices[`treatment_price_${index + 1}`] = price.value;
    });
    let formDataJson = {
        medicine_code: form.code_obat.kfa_code,
        name: form.code_obat.name,
        expiry_date: form.expiry_date,
        quantity: Number(form.quantity),
        package: form.code_obat.uom.name,
        uom: form.code_obat.uom.volume_uom_name,
        amount_per_package: Number(form.amount_per_package),
        manufacturer: form.code_obat.manufacturer || '-',
        is_fast_moving: form.is_fast_moving,
        is_active: form.code_obat.active,
        minimum_quantity: Number(form.minimum_quantity),
        dosage_form: form.code_obat.dosage_form.name,
        prices: {
            base_price: Number(form.prices.base_price),
            purchase_price: Number(form.prices.purchase_price),
            treatment_price_1: Number(form.prices.treatment_price_1),
            treatment_price_2: Number(form.prices.treatment_price_2),
            treatment_price_3: Number(form.prices.treatment_price_3),
            treatment_price_4: Number(form.prices.treatment_price_4),
            treatment_price_5: Number(form.prices.treatment_price_5),
            treatment_price_6: Number(form.prices.treatment_price_6),
            treatment_price_7: Number(form.prices.treatment_price_7),
            treatment_price_8: Number(form.prices.treatment_price_8),
            treatment_price_9: Number(form.prices.treatment_price_9),
        },
        ingredients: form.code_obat.active_ingredients.map(ingredient => (
            { ingredient_name: ingredient.zat_aktif }
        ))
    };

    try {
        await axios.post(route('medicine.store'), formDataJson).then(() => {
            searchMedicines().then(() => {
                dispense();
            })

            creationSuccessModal.value = true;
            failAlertVisible.value = false;
            errorMessage.value = '';
        });

    } catch (error) {
        console.error(error.response ? error.response.data : error.message);
        failAlertVisible.value = true;
        creationSuccessModal.value = true;

        if (error.response && error.response.data) {
            console.error('Response:', error.response.data);
            errorMessage.value = error.response.data.error || 'Failed to save data';
        } else {
            errorMessage.value = 'An error occurred while saving data';
        }
    }
};

const addMedication = () => {
    const formDataJson = {
        resourceType: 'Medication',
        identifier: [
            {
                system: 'http://sys-ids.kemkes.go.id/medication/d7c204fd-7c20-4c59-bd61-4dc55b78438c',
                use: 'official',
                value: '123456789'
            }
        ],
        // identifier: [organizationRef.value],
        meta: {
            profile: [
                'https://fhir.kemkes.go.id/r4/StructureDefinition/Medication'
            ]
        },
        code: {
            coding: [{
                system: 'http://sys-ids.kemkes.go.id/kfa',
                code: form.code_obat.kfa_code,
                display: form.code_obat.name,
            }],
        },

        status: form.code_obat.active ? 'active' : 'inactive',
        form: {
            coding: [{
                system: 'http://terminology.kemkes.go.id/CodeSystem/medication-form',
                code: form.code_obat.dosage_form.code,
                display: form.code_obat.dosage_form.name,
            }],
        },
        extension: [
            {
                url: 'https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType',
                valueCodeableConcept: {
                    coding: [
                        {
                            system: 'http://terminology.kemkes.go.id/CodeSystem/medication-type',
                            code: 'NC',
                            display: 'Non-compound'
                        }
                    ]
                }
            }
        ],
        ingredient: form.code_obat.active_ingredients.map(ingredient => ({
            itemCodeableConcept: {
                coding: [{
                    system: 'http://sys-ids.kemkes.go.id/kfa',
                    code: ingredient.kfa_code,
                    display: ingredient.zat_aktif
                }]
            },
            isActive: ingredient.active,
        }))
    };
    axios.post(route('integration.store', { resourceType: 'Medication' }), formDataJson)
        .then(response => {
            submitForm();
            creationSuccessModal.value = true;
            setTimeout(() => {
                successAlertVisible.value = false;
            }, 3000);
        })
        .catch(error => {
            console.error('Error creating user:', error);
            failAlertVisible.value = true;
            setTimeout(() => {
                failAlertVisible.value = false;
            }, 3000);
        });
};

const formStock = useForm({
    _id: '',
    id_transaction: '',
    id_medicine: '',
    quantity: '',
    note: '',
});

const medicines = ref([]);
let quantityMax = ref(null);

const searchMedicines = async () => {
    try {
        const response = await axios.get(route('getmedicine', { search: form.code_obat.name || form.name }));
        medicines.value = response.data;

        console.log(form.code_obat.name || form.name, medicines.value, medicines.value.find(medicine => medicine.name === (form.code_obat.name || form.name)), 'daffa')
        updateQuantity();
    } catch (error) {
        console.error(error);
    }
};

const updateQuantity = () => {
    const selectedMedicine = medicines.value.find(medicine => medicine.name === (form.code_obat.name || form.name));


    if (selectedMedicine) {
        quantityMax.value = selectedMedicine.quantity;
        formStock.id_medicine = selectedMedicine._id;
        formStock.quantity = form.quantity;
        formStock.id_transaction = `${new Date().getUTCSeconds()}` + `${new Date().getUTCMilliseconds()}` + `${new Date().getUTCDate()}` + `${new Date().getUTCMonth()}` + `${new Date().getUTCFullYear()}`;
        formStock.note = 'Medication Dispense'
        console.log(selectedMedicine, formStock)
        if (formStock.quantity > quantityMax.value) {
            console.error(`Quantity tidak boleh lebih dari ${quantityMax.value}`);
            formStock.quantity = quantityMax.value;
        }
    }
};

const dispense = async () => {
    if (!formStock.id_medicine) {
        console.error('Obat tidak ada di stok');
        return;
    }

    if (formStock.quantity > quantityMax.value) {
        console.error(`Jumlah stok Obat tidak mencukupi ${quantityMax.value}`);
        return;
    }

    const routeName = 'medicinetransactions.store';
    const method = 'post';

    try {
        await formStock[method](route(routeName), {
            preserveScroll: true,
            onFinish: () => formStock.reset(),
        });
    } catch (error) {
        console.error('Gagal menyimpan transaksi:', error);
    }
};

const combo_classes = {
    container: 'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer border-2 border-neutral-grey-0 ring-0 shadow-sm rounded-xl bg-white text-sm leading-snug outline-none',
    search: 'w-full absolute inset-0 outline-none border-0 ring-0 focus:ring-original-teal-300 focus:ring-2 appearance-none box-border text-sm font-sans bg-white rounded-xl pl-3.5 rtl:pl-0 rtl:pr-3.5',
    placeholder: 'flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-500 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
    option: 'flex items-center justify-start box-border text-left cursor-pointer text-sm leading-snug py-1.5 px-3',
    optionPointed: 'text-white bg-original-teal-300',
    optionSelected: 'text-white bg-original-teal-300',
    optionDisabled: 'text-gray-300 cursor-not-allowed',
    optionSelectedPointed: 'text-white bg-original-teal-300 opacity-90',
    optionSelectedDisabled: 'text-green-100 bg-original-teal-300 bg-opacity-50 cursor-not-allowed',
};
</script>
