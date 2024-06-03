<template>
    <div>
        <div class="my-2 w-full">
            <div class="relative overflow-x-auto mb-5">
                <table class="w-full text-base text-left text-neutral-grey-200 ">
                    <tbody>
                        <tr class="bg-original-white-0 border-b">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                Nama
                            </th>
                            <td v-if="patient && patient.name && patient.name[0]" class="px-6 py-4 w-2/3">
                                {{ patient.name[0].text }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0 border-b">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                Tanggal Lahir
                            </th>
                            <td v-if="patient && patient.birthDate" class="px-6 py-4 w-2/3">
                                {{ patient.birthDate }}
                            </td>
                        </tr>

                        <tr class="bg-original-white-0 border-b">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                Gender
                            </th>
                            <td v-if="patient && patient.gender" class="px-6 py-4 w-2/3">
                                {{ patient.gender }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0 border-b">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                Alamat
                            </th>
                            <td v-if="patient && patient.address" class="px-6 py-4 w-2/3">
                                <div v-for="(address, index) in patient.address">
                                    <p class="mt-1"><strong>Alamat {{ (index + 1) }}:</strong> <br> {{ address.line }}</p>
                                </div>
                            </td>
                        </tr>
                        <tr class="bg-original-white-0 border-b">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                Kelahiran Kembar
                            </th>
                            <td v-if="patient && patient.multipleBirthInteger" class="px-6 py-4 w-2/3">
                                <strong>Ya</strong>, kelahiran ke {{ patient.multipleBirthInteger }}
                            </td>
                            <td v-if="patient && patient.multipleBirthBoolean" class="px-6 py-4 w-2/3">
                                Tidak
                            </td>
                        </tr>
                        <tr class="bg-original-white-0 border-b">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                Kontak Lain
                            </th>
                            <td v-if="patient && patient.contact" class="px-6 py-4 w-2/3">
                                <div v-for="(contact, index) in patient.contact">
                                    <p class="mt-3"><strong>Kontak {{ (index + 1) }}</strong></p>
                                    <p><strong>Hubungan:</strong> {{ contact.relationship[0].coding[0].display }}</p>
                                    <p><strong>Nama:</strong> {{ contact.name.text }}</p>
                                    <div v-for="(kontak, index) in contact.telecom">
                                        <p><strong>{{ kontak.system }}:</strong> {{ kontak.value }}</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="bg-original-white-0 border-b">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                Bahasa Komunikasi
                            </th>
                            <td v-if="patient && patient.communication" class="px-6 py-4 w-2/3">
                                <div v-for="(communication, index) in patient.communication">
                                    <p class="mt-3">Bahasa {{communication.language.coding[0].display}} <span v-if="communication.preferred === true">(diutamakan)</span></p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';

const props = defineProps({
    encounter: {
        type: Object,
        required: true
    },
    subject_reference: {
        type: Object,
        required: false
    },
});

const patient = ref({});

// const fetchPatient = async () => {
//     const { data } = await axios.get(route('resources.show', 
//     {
//         'resType': 'Patient',
//         'id': props.encounter.subject.reference.split('/')[1] 
//     }));
//     patient.value = data;
// };

const fetchPatient = async () => {
    try {
        const id = props.subject_reference.reference.split('/')[1]
        props.encounter.subject.display;
        const { data } = await axios.get(route('resources.show', {
            resType: 'Patient',
            id: id
        }));
        console.log(id)
        patient.value = data;
    } catch (error) {
        console.error("Error fetching patient data:", error.message);
    }
};



watch(() => props.encounter, () => {
    fetchPatient();
}, { immediate: true });

</script>