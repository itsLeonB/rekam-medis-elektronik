<template>
    <AuthenticatedLayout>
        <div class="mt-4">
            <!-- Rincian Biaya -->
            <div class="mt-4">
                <p>Pilih Kunjungan</p>
                <Multiselect mode="single" placeholder="Rincian Biaya" :object="true" :options="encounterList"
                    label="label" valueProp="id" track-by="id" class="mt-1" :classes="combo_classes" required
                    v-model="selectedEncounter" />
                <div v-if="selectedEncounter"
                    class="bg-original-white-0 grid mt-2 grid-cols-4 overflow-hidden shadow rounded-xl md:rounded-2xl mb-2 p-2 md:py-8 md:pl-10 md:pr-14">
                    <h1 class="font-bold text-neutral-black-300 text-xl col-span-4 mb-2">Kunjungan {{ selectedEncounter.id }}
                    </h1>
                    <div class="grid-cols-2 grid col-span-2">
                        <div class="font-bold">Pasien</div>
                        <div>{{ selectedEncounter.subject.display }}</div>
                        <div class="font-bold">Tanggal Kunjugan</div>
                        <div>{{ formatDate(selectedEncounter.period.start) }}</div>
                        <div class="font-bold">Status</div>
                        <div>{{ selectedEncounter.status }}</div>
                    </div>
                    <div class="col-span-2">
                        <table class="w-full text-sm text-left rtl:text-right text-neutral-grey-200 col-span-3 shadow">
                            <thead class="text-sm text-neutral-black-300 uppercase bg-gray-50 border-b">
                                <tr>
                                    <th scope="col" class="px-6 py-3 w-1/2">Potential Charge Item</th>
                                    <th scope="col" class="px-6 py-3 w-1/2">Detail</th>
                                </tr>
                            </thead>
                            <tbody v-for="(item, index) in chargeItemList" :key="index">
                                <tr class="bg-original-white-0 hover:bg-thirdinner-lightteal-300">
                                    <td class="px-6 py-4 w-1/2">{{ item.code ? item.code?.coding[0].display :
                                        item.medicationReference.display
                                        }}</td>
                                    <td>
                                        <Link
                                            :href="route('finance.chargeitem.create', { 'id': item.id, 'resType': item.resourceType })"
                                            as="button"
                                            class="inline-block mb-3 col-span-2 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                                        Tambah ke tagihan
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                </div>

                <div v-if="selectedEncounter"
                    class="bg-original-white-0 grid grid-cols-3 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-2 md:py-6 md:pl-8 md:pr-12">
                    <div class="col-span-2">
                        <h2 class="font-bold text-neutral-black-300 text-lg mt-2 col-span-3">Tagihan</h2>
                        <p class="mb-3 text-sm font-normal text-neutral-grey-100 col-span-3">Tagihan kunjungan ini</p>
                    </div>
                    <div class="grid items-center justify-end">
                        <Link :href="route('finance.chargeitem.createblank', { 'id': selectedEncounter.id })"
                            as="button"
                            class="inline-block mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                        Tambah item tagihan
                        </Link>
                    </div>

                    <table class="w-full text-sm text-left rtl:text-right text-neutral-grey-200 col-span-3 shadow">
                        <thead class="text-sm text-neutral-black-300 uppercase bg-gray-50 border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3 w-1/6">No.</th>
                                <th scope="col" class="px-6 py-3 w-2/6">Barang tagihan</th>
                                <th scope="col" class="px-6 py-3 w-2/6">Detail</th>
                                <th scope="col" class="px-6 py-3 w-1/6">Harga</th>
                            </tr>
                        </thead>
                        <tbody v-if="resourceChargeItemList" v-for="(item, index) in resourceChargeItemList" :key="index">
                            <tr class="bg-original-white-0 hover:bg-thirdinner-lightteal-300">
                                <td class="px-6 py-4 w-1/6"> {{ index + 1 }}</td>
                                <td class="px-6 py-4 w-2/6">{{ item.id }}</td>
                                <td class="px-6 py-4 w-2/6"> {{ item.context.display }}</td>
                                <td class="px-6 py-4 w-1/6">{{ item.priceOverride.currency }} {{
                                    item.priceOverride.value }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-2">
                        <Link :href="route('finance.newinvoice', { 'id': selectedEncounter.id })" as="button"
                            class="inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg me-1">
                        Buat Invoice
                        </Link>
                        <Link :href="route('finance.claim.new', { 'id': selectedEncounter.id })" as="button"
                            class="inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                        Buat Claim
                        </Link>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';
import MainButton from '@/Components/MainButton.vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

const encounterList = ref([]);
const procedureList = ref([]);
const medicationList = ref([]);
const selectedEncounter = ref(null)
const observationList = ref([])
const selectedEncounterId = ref(null)
const resourceChargeItemList = ref([])

watch(selectedEncounter, (newValue) => {
    getChargeItemList(newValue.id)
});

const getResourceList = async (resourceName, list) => {
    try {
        const { data } = await axios.get(`/resources/${resourceName}`);

        if (resourceName == 'Encounter') {
            list.value = data.map((item, index) => ({
                ...item,
                label: `${item.subject.display} | ${formatDate(item.period.start)}`
            }));
        } else {
            list.value = data
        }
        console.log(list.value)
    } catch (error) {
        console.error('Error fetching resources:', error);
    }
}

const formatDate = (isoString) => {
    const date = new Date(isoString);

    const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const dayOfWeek = daysOfWeek[date.getDay()]; // Get the day of the week

    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();

    return `${dayOfWeek} ${day}/${month}/${year}`;
};

// List ChargeItem = procedure + medication
const chargeItemList = computed(() => {
    // if (!medicationList.value || !procedureList.value || !observationList.value) { MedicationDispense not yet implemented
    if (!procedureList.value || !observationList.value) {
        return [];
    }
    return [...procedureList.value, ...observationList.value]
});

const getChargeItemList = async (id) => {
    await getResourceList('Procedure', procedureList);
    // await getResourceList('MedicationDispense', medicationList); MedicationDispense Not yet implemented
    await getResourceList('Observation', observationList);
    await getResourceList('ChargeItem', resourceChargeItemList);
    console.log(resourceChargeItemList)
    procedureList.value = procedureList.value.filter(item => item.encounter.reference === `Encounter/${id}`)
    observationList.value = observationList.value.filter(item => item.encounter.reference === `Encounter/${id}`)
    resourceChargeItemList.value = resourceChargeItemList.value.filter(item => item.context.reference = `Encounter/${id}`)
    // medicationList.value = medicationList.value.filter(item => item?.encounter?.reference === `Encounter/${id}`)
}

onMounted(() => {
    getResourceList('Encounter', encounterList);
})

</script>