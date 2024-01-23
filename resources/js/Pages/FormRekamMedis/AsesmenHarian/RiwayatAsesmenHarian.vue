<template>
    <div>
        <form @submit.prevent="submit">
            <div class="my-2 w-full">
                <h3 class="font-semibold text-secondhand-orange-300 mt-2">Ubah Kondisi Harian</h3>
                <div class="flex">
                    <div class="w-full md:w-7/12 mr-2">
                        <InputLabel for="code" value="Kondisi" />
                        <div class="flex mimn-h-min">
                            <p v-if="condition !== null">{{ condition.code.coding[0].display }}, {{ condition.note[0].text
                            }}
                            </p>
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="flex mt-3">
                    <div class="w-full">
                        <InputLabel for="note" value="Keterangan" />
                        <div class="flex">
                            <TextInput v-model="resourceForm.note" id="note" type="text" class="text-sm mt-1 block w-full"
                                placeholder="Keterangan" required />
                        </div>
                        <InputError class="mt-1" />
                    </div>
                </div>
            </div>
            <div class="flex justify-between">
                <SecondaryButtonSmall type="button" @click="muatData" class="teal-button-text">Muat data
                </SecondaryButtonSmall>
                <div class="mt-2 mr-3">
                    <MainButtonSmall type="submit" class="teal-button text-original-white-0">Submit</MainButtonSmall>
                </div>
            </div>
            <p v-if="successAlertVisible" class="text-sm text-original-teal-300">Sukses!</p>
            <p v-if="failAlertVisible" class="text-sm text-thirdouter-red-300">Gagal!</p>
        </form>
        <div class="relative overflow-x-auto mb-5 mt-5">
            <table class="w-full text-sm text-center rtl:text-right text-neutral-grey-200">
                <thead class="text-sm text-neutral-black-300 bg-original-white-0 border-b">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-8/12">
                            Kondisi
                        </th>
                        <th scope="col" class="px-6 py-3 w-4/12">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody v-for="(cons, index) in conditionList" :key="index">
                    <tr v-if="cons.clinicalStatus.coding[0].code !== 'resolved'"
                        class="bg-original-white-0 hover:bg-thirdinner-lightteal-300 border-b cursor-pointer"
                        @click="thisCondition(index)">
                        <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap hover:underline w-8/12">
                            <p>{{ cons.code.coding[0].display }}<br> {{ cons.note[0].text }}</p>
                        </th>
                        <td class="px-6 py-4 w-4/12">
                            <p>{{ cons.clinicalStatus.coding[0].display }}</p>
                        </td>
                    </tr>
                    <tr v-else class="bg-original-white-0 hover:bg-thirdinner-lightteal-300 border-b">
                        <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-8/12">
                            <p>{{ cons.code.coding[0].display }}<br> {{ cons.note[0].text }}</p>
                        </th>
                        <td class="px-6 py-4 w-4/12">
                            <p>{{ cons.clinicalStatus.coding[0].display }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import TextInput from '@/Components/TextInput.vue';
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
import SecondaryButtonSmall from '@/Components/SecondaryButtonSmall.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import axios from 'axios';
import { ref } from 'vue';

const props = defineProps({
    encounter_satusehat_id: {
        type: String,
    },
});

const resourceForm = ref({
    note: ''
});

const condition = ref(null);

const conditionList = ref(null);
const getconditionList = async () => {
    const { data } = await axios.get(route('kunjungan.condition', { 'encounter_satusehat_id': props.encounter_satusehat_id }));
    conditionList.value = data['asesmen-harian'];
};

const thisCondition = (index) => {
    condition.value = conditionList.value[index];
    resourceForm.value.note = condition.value.note[0].text;
}

const muatData = () => {
    getconditionList();
};

const successAlertVisible = ref(false);
const failAlertVisible = ref(false);

const submit = () => {
    const currentTime = new Date().toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '');
    condition.value.clinicalStatus.coding[0].code = "resolved";
    condition.value.clinicalStatus.coding[0].display = "Resolved";
    condition.value.abatementDateTime = currentTime;
    condition.value.note[0].text = resourceForm.value.note;


    axios.put(route('integration.update', {
        res_type: 'Condition',
        satusehat_id: condition.value.id
    }), condition.value)
        .then(response => {
            successAlertVisible.value = true;
            setTimeout(() => {
                successAlertVisible.value = false;
            }, 3000);
            getconditionList();
            condition.value = null;
            resourceForm.value.note = '';
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