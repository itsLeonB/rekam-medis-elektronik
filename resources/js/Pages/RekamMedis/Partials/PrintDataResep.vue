<template>
    <div class="bg-original-white-0  mb-8 p-6 md:py-8 md:px-10">
        <div ref="printArea">
            <div class="my-2 w-full text-center">
                <h1 class="text-lg font-semibold">Rumah Sakit Unipdu Jombang</h1>
                <p>Jl. Raya Peterongan-Jogoroto KM. 0,5 (Tambar) Jogoroto, Jombang, 61485 Jawa Timur</p>
                <p>Phone: 081235477781</p>
            </div>
            <hr>
            <div class="my-2 w-full flex justify-end div-date">
                Jombang, {{formattedDate}}  
            </div>
            <div class="w-full flex div-encounter">
                <table class="w-full mx-auto text-lef ">
                    <tbody class="w-full">
                        <tr class="bg-original-white-0">
                            <td scope="row" class="font-semibold whitespace-nowrap w-1/4">
                                Dokter
                            </td>
                            <td class="w-3/4" v-if="getDokter && getDokter[0]" >: {{getDokter[0].participant[0].individual.display}}</td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <td scope="row" class="font-semibold whitespace-nowrap w-1/4">
                                Pasien
                            </td>
                            <td class="w-3/4" v-if="getDokter && getDokter[0]">: {{getDokter[0].subject.display}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="my-2 w-full div-resep">
                <p>Berikut Rincian Resep Obat </p>
                <table class="w-full mx-auto text-base text-left text-neutral-grey-200 data-resep">
                    <tbody class="w-full">
                        <tr>
                            <th class="font-bold">No</th>
                            <th class="font-bold">Nama Obat</th>
                            <th class="font-bold">Dosis</th>
                        </tr>
                        <tr v-for="(item, index) in medrequest" :key="index" style="font-size: 14px;">
                            <td class="font-normal">{{ index + 1 }}.</td>
                            <td class="font-normal">{{ item.display }}</td>
                            <td class="font-normal">{{ item.dosageInstruction }}</td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
            <!-- <div class="my-4 w-full div-pasien">
                <div class="relative overflow-x-auto mb-5">
                    <table class="w-full text-left">
                        <tbody>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-2 py-2 font-normal whitespace-nowrap w-1/3">
                                    Nama
                                </th>
                                <td v-if="patient && patient.name && patient.name[0]" class="px-2 py-2 w-2/3">
                                    : {{ patient.name[0].text }}
                                </td>
                            </tr>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-2 py-2 font-normal whitespace-nowrap w-1/3">
                                    Tanggal Lahir
                                </th>
                                <td v-if="patient && patient.birthDate" class="px-2 py-2 w-2/3">
                                    : {{ patient.birthDate }}
                                </td>
                            </tr>

                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-2 py-2 font-normal whitespace-nowrap w-1/3">
                                    Gender
                                </th>
                                <td v-if="patient && patient.gender" class="px-2 py-2 w-2/3">
                                    : {{ patient.gender }}
                                </td>
                            </tr>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-2 py-2 font-normal whitespace-nowrap w-1/3">
                                    Alamat
                                </th>
                                <td v-if="patient && patient.address" class="px-2 py-2 w-2/3">
                                    <div v-for="(address, index) in patient.address">
                                        <p class="mt-1"><strong>Alamat {{ (index + 1) }}:</strong> <br> {{ address.line }}</p>
                                    </div>
                                </td>
                            </tr>
                            
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-2 py-2 font-normal whitespace-nowrap w-1/3">
                                    Kontak Lain
                                </th>
                                <td v-if="patient && patient.contact" class="px-2 py-2 w-2/3">
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
                        </tbody>
                    </table>
                </div>
            </div> -->
        </div>
        <div class="flex justify-end mt-3">
            <!-- <Link :href="route('usermanagement.tambah')" as="button"
                        class="mr-3 inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-md font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Back
                    </Link> -->
            <button @click="printTable"
                    class="mr-2 inline-flex mb-3 px-4 py-2 border border-transparent rounded-md font-normal text-sm text-white bg-secondhand-orange-300 transition ease-in-out duration-150 hover:shadow-lg">
                Print Resep
            </button>

        </div>
    </div>
</template>

<script setup>
import { ref, onMounted  } from 'vue';

const date = new Date();
const formattedDate = date.toLocaleDateString('id-ID', {year: 'numeric', month: 'long', day: 'numeric' });
const props = defineProps({
    encounter_id: {
        type: String,
    },
});

const errorMessage = ref('');
const medrequest = ref([]);
const getDokter = ref('');

const encounter_id = props.encounter_id;
const fetchMedication = async () => {
  try {
    const { data } = await axios.get(route('medicationRequest.data', { 'section': 'resep', 'encounter_id': encounter_id }));
    medrequest.value = data;
  } catch (error) {
    errorMessage.value = 'Terjadi kesalahan dalam mengambil data.';
    console.error(error);
  }
};

const fetchDokter = async () => {
  try {
    const { data } = await axios.get(route('medicationRequest.data', { 'section': 'encounter', 'encounter_id': encounter_id }));
    console.log(data)
    getDokter.value = data;
  } catch (error) {
    errorMessage.value = 'Terjadi kesalahan dalam mengambil data.';
    console.error(error);
  }
};

const printArea = ref(null);
const printTable = () => {
  const printContents = printArea.value.innerHTML;
  const originalContents = document.body.innerHTML;
  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;
};

onMounted(() => {
  fetchMedication();
  fetchDokter();
});
</script>
<style>
hr{
    border-top: 2px solid black;
}
.data-resep th, .data-resep td {
    border: 1px solid #4e4d4d; 
    padding: 4px;
}
.data-resep th {
    text-align: center;
}
.div-resep p{
   
    padding:8px 0;
}
p{
     font-size: 14px;
}
.div-pasien, .div-encounter, .div-date{
    font-size: 14px;
}
</style>