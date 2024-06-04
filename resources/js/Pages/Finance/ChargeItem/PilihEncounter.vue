<template>
    <AuthenticatedLayout>
        <div class="mt-4">
            <!-- Rincian Biaya -->
            <div class="mt-4">
                <p>Pilih Kunjungan</p>
                <Multiselect mode="single" placeholder="Rincian Biaya" :object="true" :options="encounterList"
                    label="label" valueProp="id" track-by="id" class="mt-1" :classes="combo_classes" required
                    v-model="selectedEncounter" />


                <div class="bg-white border rounded p-1 grid grid-cols-2" v-if="selectedEncounter">
                    <h1 class="font-bold text-neutral-black-300 text-xl col-span-2">Kunjungan {{ selectedEncounter.id }}
                    </h1>
                    <div>Pasien</div>
                    <div>{{ selectedEncounter.subject.display }}</div>
                    <div>Tanggal Kunjugan</div>
                    <div>{{ formatDate(selectedEncounter.period.start) }}</div>
                    <div>Status</div>
                    <div>{{ selectedEncounter.status }}</div>

                    <table class="w-full h-auto mt-2 border col-span-2">
                        <tr>
                            <th>No</th>
                            <th>Procedure/Observation</th>
                            <th>Detail</th>
                        </tr>
                        <tr class="text-center" v-for="(item, index) in chargeItemList" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ item.code ? item.code?.coding[0].display : item.medicationReference.display }}</td>
                            <td>
                                <Link :href="route('finance.chargeitem.create', {'id': item.id, 'resType':item.resourceType})" as="button"
                                    class="inline-flex mb-3 col-span-2 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                                Tambah ke tagihan 
                                </Link>
                            </td>
                        </tr>
                    </table>


                    <Link :href="route('finance.newinvoice')" as="button"
                        class="inline-flex mb-3 col-span-2 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Buat Invoice
                    </Link>
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
const procedureList = ref(null);
const medicationList = ref(null);
const selectedEncounter = ref(null)
const observationList = ref(null)
const selectedEncounterId = ref(null)

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
    procedureList.value = procedureList.value.filter(item => item.encounter.reference === `Encounter/${id}`)
    observationList.value = observationList.value.filter(item => item.encounter.reference === `Encounter/${id}`)
    // medicationList.value = medicationList.value.filter(item => item?.encounter?.reference === `Encounter/${id}`)
}

onMounted(() => {
    getResourceList('Encounter', encounterList);
})

</script>