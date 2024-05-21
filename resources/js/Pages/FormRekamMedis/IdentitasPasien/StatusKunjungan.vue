<template>
    <div>
        <div class="my-2 w-full">
            <div class="relative overflow-x-auto mb-5">
                <table class="w-full text-base text-left text-neutral-grey-200 ">
                    <tbody>
                        <tr class="bg-original-white-0 border-b">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                Status Kunjungan
                            </th>
                            <td class="px-6 py-4 w-2/3">
                                {{ encounter.status }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <h3 class="font-semibold text-secondhand-orange-300 mt-2">Ubah status kunjungan</h3>
                <form @submit.prevent="submit">
                    <select id="status" v-model="status_kunjungan"
                        class="block mt-2 w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                        <option v-for="status in status_kunjungan_list" :value=status.id>{{ status.label }}</option>
                    </select>
                    <div class="flex justify-between">
                        <div class="mt-2 mr-3">
                            <MainButtonSmall type="submit" class="teal-button text-original-white-0">Submit
                            </MainButtonSmall>
                        </div>
                    </div>
                    <p v-if="successAlertVisible" class="text-sm text-original-teal-300">Sukses!</p>
                    <p v-if="failAlertVisible" class="text-sm text-thirdouter-red-300">Gagal!</p>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
import axios from 'axios';
import { ref, watch } from 'vue';

const props = defineProps({
    encounter: {
        type: Object,
        required: true
    },
});

const status_kunjungan = ref('');
const status_kunjungan_list = [
    { "id": 'planned', "label": 'Planned' },
    { "id": 'arrived', "label": 'Arrived' },
    { "id": 'triaged', "label": 'Triaged' },
    { "id": 'in-progress', "label": 'In Progress' },
    { "id": 'onleave', "label": 'Onleave' },
    { "id": 'finished', "label": 'Finished' },
    { "id": 'cancelled', "label": 'Cancelled' }
];

const newEncounter = ref({});

watch(() => props.encounter, () => {
    newEncounter.value = props.encounter;
    status_kunjungan.value = newEncounter.value.status
}, { immediate: true });

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);

const submit = async () => {
    const currentTime = new Date().toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '');
    newEncounter.value.statusHistory[newEncounter.value.statusHistory.length - 1].period.end = currentTime;
    newEncounter.value.status = status_kunjungan.value;
    const submitResource = {
        "status": status_kunjungan.value,
        "period": {
            "start": currentTime,
        }

    };
    newEncounter.value.statusHistory.push(submitResource);

    await axios.put(route('integration.update', {
        resourceType: 'Encounter',
        id: props.encounter.id
    }), newEncounter.value)
        .then(response => {
            successAlertVisible.value = true;
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

</script>