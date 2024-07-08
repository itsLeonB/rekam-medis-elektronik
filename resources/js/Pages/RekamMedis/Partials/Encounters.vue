<template>
    <div v-show="isEncounterSelected === false" class="relative overflow-x-auto mb-5">
        <table class="w-full text-sm text-center rtl:text-right text-neutral-grey-200">
            <thead class="text-sm text-neutral-black-300 bg-original-white-0 border-b">
                <tr>
                    <th scope="col" class="px-6 py-3 w-3/12">
                        Jenis Kunjungan
                    </th>
                    <th scope="col" class="px-6 py-3 w-3/12">
                        Tanggal
                    </th>
                    <th scope="col" class="px-6 py-3 w-3/12">
                        Diagnosis
                    </th>
                    <th scope="col" class="px-6 py-3 w-3/12">
                        DPJP
                    </th>
                </tr>
            </thead>
            <tbody v-for="(item, index) in encounters">
                <tr class="bg-original-white-0 hover:bg-thirdinner-lightteal-300 border-b">
                    <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap hover:underline 3/12 cursor-pointer" 
                        @click="selectEncounter(index)">
                        <p>Kunjungan {{ item.encounter.class.code == 'AMB' ? 'Rawat Jalan' : item.encounter.class.code ==
                            'IMP' ? 'Rawat Inap' :
                            item.encounter.class.code == 'EMER' ? 'IGD' : '' }}</p>
                        <p>Status {{ item.encounter.status }}</p>
                    </th>
                    <td class="px-6 py-4 w-3/12">
                        <p><strong>Mulai</strong> <br> {{ formatTimestamp(item.encounter.period.start).split('/')[0] }} {{
                            formatTimestamp(item.encounter.period.start).split('/')[1] }}</p>
                        <p v-if="item.encounter.status === 'finished' || item.encounter.status === 'cancelled'">
                            <strong>Berakhir</strong> <br> {{ formatTimestamp(item.encounter.period.end).split('/')[0] }} {{
                                formatTimestamp(item.encounter.period.end).split('/')[1] }}
                        </p>
                    </td>
                    <td class="px-6 py-4 w-3/12">
                        <div v-if="item.encounter.status === 'finished'">
                            <p v-for="(diag, index) in item.encounter.diagnosis">- {{ item.encounter.status }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 w-3/12">
                        {{ item.encounter.participant[0].individual.display }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div v-show="isEncounterSelected === true">
        <div>
            <MainButtonSmall class="teal-button text-original-white-0" @click="backToEncounter" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3 "
                    stroke="currentColor" class="w-4 h-4 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Back
            </MainButtonSmall>
        </div>
        <EncounterDetails ref="section" :encounter="encounterSelected"></EncounterDetails>
    </div>
</template>
<script setup>
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
import EncounterDetails from '@/Pages/RekamMedis/Partials/EncounterDetails.vue';
import { ref } from 'vue';

const props = defineProps({
    encounters: {
        type: Object,
        required: true
    },
});

const section = ref(null);

const encounterSelected = ref({});
const isEncounterSelected = ref(false);

const selectEncounter = (index) => {
    encounterSelected.value = props.encounters[index];
    isEncounterSelected.value = true;
};

const backToEncounter = () => {
    isEncounterSelected.value = false;
    encounterSelected.value = {};
    section.value.formSection = 1;
};

const formatTimestamp = (timestamp) => {
    const date = new Date(timestamp);
    date.setHours(date.getHours() + 7);

    const daysOfWeek = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    const dayOfWeek = daysOfWeek[date.getUTCDay()];
    const day = date.getUTCDate();
    const month = months[date.getUTCMonth()];
    const year = date.getUTCFullYear();
    const hour = date.getUTCHours().toString().padStart(2, '0');
    const minute = date.getUTCMinutes().toString().padStart(2, '0');

    return `${dayOfWeek}, ${day} ${month} ${year} / ${hour}:${minute}`;
};
</script>