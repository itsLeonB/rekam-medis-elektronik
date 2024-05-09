<template>
   <AuthenticatedLayout>
      <template #apphead>
         <title>Daftar Rawat Inap - </title>
      </template>
      <Modal :show="creationSuccessModal">
         <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
               Data Rekam Medis telah berhasil dibuat. Kembali ke halaman Rekam Medis.
            </h2>
            <div class="mt-6 flex justify-end">
               <Link :href="route('rekammedis')"
                  class="mx-auto mb-3 w-fit block justify-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
               Kembali </Link>
            </div>
         </div>
      </Modal>
      <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
         <h1 class="text-2xl font-bold text-neutral-black-300">Daftar Rekam Medis</h1>
         <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman pendaftaran rekam medis.</p>
         <div class="w-1/6">
            <InputLabel for="barulahir" value="Jenis Pasien" />
            <select id="barulahir" v-model="barulahir"
               class="mt-1 block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
               <option value="search">Cari Satusehat</option>
               <option :value=false>Anak/Dewasa</option>
               <option :value=true>Bayi baru lahir</option>
            </select>
         </div>
         <div v-if="barulahir === 'search'">
            <form>
               <div class="mt-4">
                  <InputLabel for="nik" value="Cari Patient ID" />
                  <div class="w-full flex">
                     <TextInput id="nik" type="text" class="mt-1 block w-full mr-3" v-model="nikPatient" autofocus
                        placeholder="Masukkan NIK" />
                     <MainButtonSmall @click="cariPatientID" class="teal-button text-original-white-0" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                           stroke="currentColor" class="w-5 h-5">
                           <path stroke-linecap="round" stroke-linejoin="round"
                              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                     </MainButtonSmall>
                  </div>
                  <InputError class="mt-1" />
               </div>
            </form>
            <p v-show="!resultsFound" class="text-center mt-5"> Data tidak ditemukan.</p>
            <div class="flex mt-5 " v-if="showPatientDetails">
               <div class="relative w-full pl-14 overflow-x-auto mb-5">
                  <table class="w-full mx-auto text-base text-left text-neutral-grey-200 ">
                     <tbody class="w-full">
                        <tr class="bg-original-white-0">
                           <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/3">
                              Nama
                           </th>
                           <td v-if="patient.resource.name" class="px-6 py-4 w-2/3">
                              {{ patient.resource.name[0].text }}
                           </td>
                        </tr>
                        <tr class="bg-original-white-0">
                           <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/3">
                              Tanggal Lahir
                           </th>
                           <td class="px-6 py-4 w-full">
                              {{ patient.resource.birthDate }}
                           </td>
                        </tr>
                        <tr class="bg-original-white-0">
                           <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/3">
                              Gender
                           </th>
                           <td class="px-6 py-4 w-full">
                              {{ patient.resource.gender }}
                           </td>
                        </tr>
                        <tr class="bg-original-white-0">
                           <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/3">
                              Identifier
                           </th>
                           <td class="px-6 py-4 w-2/3">
                              <p v-for="item in patient.resource.identifier">{{ item.system }}: {{ item.value }}
                              </p>
                           </td>
                        </tr>
                        <tr class="bg-original-white-0">
                           <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/3">
                              Alamat
                           </th>
                           <td v-if="patient.resource.address" class="px-6 py-4 w-2/3">
                              {{ patient.resource.address[0].line[0] }}
                           </td>
                        </tr>
                        <tr class="bg-original-white-0">
                           <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/3">
                              Kota
                           </th>
                           <td v-if="patient.resource.address" class="px-6 py-4 w-2/3">
                              {{ patient.resource.address[0].city }}
                           </td>
                        </tr>
                        <tr class="bg-original-white-0">
                           <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/3">
                              Kontak
                           </th>
                           <td v-if="patient.resource.telecom" class="px-6 py-4 w-2/3">
                              <p v-for="telecom in patient.resource.telecom">{{ telecom.system }}: {{
                                 telecom.value }}</p>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="flex flex-col items-center justify-end mt-10">
               <MainButton class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0"
                  @click="submitSatusehat">
                  Tambah
               </MainButton>
            </div>
         </div>
        <form v-if="barulahir !== 'search'" @submit.prevent="submit">
            <h2 class="font-semibold text-secondhand-orange-300 mt-4">Identitas Diri</h2>
            <div class="w-full flex">
               <div class="w-3/6 mr-2">
                  <InputLabel for="name" value="Nama" />
                  <TextInput id="name" type="text" class="mt-1 block w-full" required v-model="resourceForm.nama"
                     placeholder="Masukkan Nama Pasien" />
               </div>
               <div class="w-1/6">
                  <InputLabel for="gender" value="Gender" />
                  <select id="gender" v-model="resourceForm.gender" required
                     class="mt-1 block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                     <option value="male">Laki-laki</option>
                     <option value="female">Perempuan</option>
                     <option value="other">Lainnya</option>
                     <option value="unknown">Tidak Diketahui</option>
                  </select>
               </div>
            </div>
            <div class="w-full flex mt-2">
               <div class="w-1/4 mr-2">
                  <InputLabel v-if="barulahir === false" for="nik" value="NIK" />
                  <InputLabel v-if="barulahir === true" for="nik" value="NIK Ibu" />
                  <TextInput id="nik" type="text" class="mt-1 block w-full" v-model="resourceForm.nik"
                     placeholder="Masukkan NIK" />
               </div>
               <div class="w-1/4 mr-2">
                  <InputLabel for="paspor" value="No Paspor" />
                  <TextInput id="paspor" type="text" class="mt-1 block w-full" v-model="resourceForm.paspor"
                     placeholder="Masukkan No Paspor" />
               </div>
               <div class="w-1/4">
                  <InputLabel for="name" value="No Kartu Keluarga" />
                  <TextInput id="name" type="text" class="mt-1 block w-full" v-model="resourceForm.kk"
                     placeholder="Masukkan No KK" />
               </div>
            </div>
            <div class="w-full flex mt-2">
               <div class="w-1/4 mr-2">
                  <InputLabel for="negaralahir" value="Negara Tempat Lahir" />
                  <Multiselect v-model="resourceForm.negaralahir" mode="single" placeholder="Negara Tempat Lahir"
                     :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="300"
                     :searchable="true" :options="searchNegara" label="display" valueProp="code" track-by="code"
                     :classes="combo_classes" class="mt-1" required />
               </div>
               <div class="w-1/4 mr-2">
                  <InputLabel for="kotalahir" value="Kota Tempat Lahir" />
                  <Multiselect v-if="resourceForm.negaralahir.code === 'ID'" v-model="resourceForm.kotalahir" mode="single"
                     placeholder="Kota Tempat Lahir" :filter-results="false" :object="true" :min-chars="1"
                     :resolve-on-load="false" :delay="300" :searchable="true" :options="searchKotaLahir" label="label"
                     valueProp="kode_kabko" track-by="kode_kabko" :classes="combo_classes" class="mt-1" />
                  <TextInput v-else id="kotalahir" type="text" class="mt-1 block w-full" v-model="resourceForm.kotalahir"
                     placeholder="Kota Tempat Lahir" />
               </div>
               <div class="w-1/4">
                  <InputLabel for="tanggallahir" value="Tanggal Lahir" />
                  <div class="flex pt-1">
                     <VueDatePicker v-model="resourceForm.tanggallahir"
                        class="border-[1.5px] border-neutral-grey-0 rounded-xl" required></VueDatePicker>
                  </div>
               </div>
            </div>
            <div class="w-full flex mt-2 space-x-2">
               <div class="w-[30%]">
                  <InputLabel for="kewarganegaraan" value="Kewarganegaraan" />
                  <select id="kewarganegaraan" v-model="resourceForm.kewarganegaraan" required
                     class="mt-1 block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                     <option value="WNI">Warga Negara Indonesia (WNI)</option>
                     <option value="WNA">Warga Negara Asing (WNA)</option>
                  </select>
               </div>
               <div class="w-1/5">
                  <InputLabel for="status" value="Status Nikah" />
                  <Multiselect v-model="resourceForm.statusnikah" mode="single" placeholder="Status" :object="true"
                     :options="maritalList" label="display" valueProp="code" track-by="code" class="mt-1"
                     :classes="combo_classes" required />
               </div>
               <div class="w-1/4">
                  <InputLabel for="statusnikahtext" value="Keterangan Status Nikah" />
                  <TextInput id="statusnikahtext" type="text" class="mt-1 block w-full"
                     v-model="resourceForm.statusnikahtext" placeholder="Keterangan" required />
               </div>
            </div>
            <div class="w-full mt-2">
               <InputLabel for="kelahirankembar" value="Kelahiran Kembar (jika tidak kembar, maka 0)" />
               <div class="w-1/6">
                  <TextInput id="statusnikahtext" type="number" class="mt-1 block w-full"
                     v-model="resourceForm.kelahirankembar" placeholder="Kembar ke-" required />
               </div>
            </div>

            <h2 class="font-semibold text-secondhand-orange-300 mt-4">Alamat Rumah di Indonesia</h2>
            <div class="w-full flex space-x-2">
               <div class="w-1/4">
                  <InputLabel for="provinsi" value="Provinsi" />
                  <Multiselect v-model="resourceForm.provinsi" mode="single" placeholder="Provinsi" :filter-results="false"
                     :object="true" :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true"
                     :options="searchProvinsi" label="nama_provinsi" valueProp="kode_provinsi" track-by="kode_provinsi"
                     :classes="combo_classes" class="mt-1" required />
               </div>
               <div class="w-1/4">
                  <InputLabel for="kota" value="Kabupaten/Kota" />
                  <Multiselect v-model="resourceForm.kota" mode="single" placeholder="Kabupaten/Kota"
                     :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="300"
                     :searchable="true" :options="searchKota" label="nama_kabko" valueProp="kode_kabko"
                     track-by="kode_kabko" :classes="combo_classes" class="mt-1" required />
               </div>
               <div class="w-1/4">
                  <InputLabel for="kecamatan" value="Kecamatan" />
                  <Multiselect v-model="resourceForm.kecamatan" mode="single" placeholder="Kecamatan"
                     :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="300"
                     :searchable="true" :options="searchKecamatan" label="nama_kecamatan" valueProp="kode_kecamatan"
                     track-by="kode_kecamatan" :classes="combo_classes" class="mt-1" required />
               </div>
            </div>
            <div class="w-full mt-2 flex space-x-2">
               <div class="w-1/4">
                  <InputLabel for="kelurahan" value="Kelurahan" />
                  <Multiselect v-model="resourceForm.keluarahan" mode="single" placeholder="Kelurahan"
                     :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="300"
                     :searchable="true" :options="searchKelurahan" label="nama_kelurahan" valueProp="kode_kelurahan"
                     track-by="kode_kelurahan" :classes="combo_classes" class="mt-1" required />
               </div>
               <div class="w-1/12">
                  <InputLabel for="rt" value="RT" />
                  <TextInput id="rt" type="number" class="mt-1 block w-full" v-model="resourceForm.rt" placeholder="RT"
                     required />
               </div>
               <div class="w-1/12">
                  <InputLabel for="rw" value="RW" />
                  <TextInput id="rw" type="number" class="mt-1 block w-full" v-model="resourceForm.rw" placeholder="RW"
                     required />
               </div>
               <div class="w-1/5">
                  <InputLabel for="kodepos" value="Kode Pos" />
                  <TextInput id="kodepos" type="number" class="mt-1 block w-full" v-model="resourceForm.kodepos"
                     placeholder="Kode Pos" required />
               </div>
            </div>
            <div class="w-full mt-2">
               <div class="w-3/4">
                  <InputLabel for="alamat" value="Alamat" />
                  <TextInput id="alamat" type="text" class="mt-1 block w-full" v-model="resourceForm.alamat"
                     placeholder="Alamat" required />
               </div>
            </div>

            <h2 class="font-semibold text-secondhand-orange-300 mt-4">Kontak Pribadi</h2>
            <div class="w-full flex space-x-2">
               <div class="w-1/4">
                  <InputLabel for="nohp" value="No HP" />
                  <TextInput id="nohp" type="text" class="mt-1 block w-full" v-model="resourceForm.nohp"
                     placeholder="No HP" />
               </div>
               <div class="w-1/4">
                  <InputLabel for="notelprumah" value="No Telp Rumah" />
                  <TextInput id="notelprumah" type="text" class="mt-1 block w-full" v-model="resourceForm.notelprumah"
                     placeholder="No Telp Rumah" />
               </div>
               <div class="w-1/4">
                  <InputLabel for="email" value="Email" />
                  <TextInput id="email" type="text" class="mt-1 block w-full" v-model="resourceForm.email"
                     placeholder="Email" />
               </div>
            </div>

            <h2 class="font-semibold text-secondhand-orange-300 mt-4">Bahasa Komunikasi</h2>
            <div class="w-full flex space-x-2">
               <div class="w-1/4">
                  <InputLabel for="bahasasatu" value="Bahasa Komunikasi 1" />
                  <Multiselect v-model="resourceForm.bahasasatu" mode="single" placeholder="Bahasa Komunikasi"
                     :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="300"
                     :searchable="true" :options="searchBahasa" label="definition" valueProp="code" track-by="code"
                     :classes="combo_classes" class="mt-1" required />
               </div>
               <div class="w-1/6">
                  <InputLabel for="bahasasatupreferred" value="Diutamakan?" />
                  <select id="bahasasatupreferred" required v-model="resourceForm.bahasasatupreferred"
                     class="mt-1 block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                     <option value="true">Ya</option>
                     <option value="false">Tidak</option>
                  </select>
               </div>
            </div>
            <div class="w-full mt-2 flex space-x-2">
               <div class="w-1/4">
                  <InputLabel for="bahasadua" value="Bahasa Komunikasi 1" />
                  <Multiselect v-model="resourceForm.bahasadua" mode="single" placeholder="Bahasa Komunikasi"
                     :filter-results="false" :object="true" :min-chars="1" :resolve-on-load="false" :delay="300"
                     :searchable="true" :options="searchBahasa" label="definition" valueProp="code" track-by="code"
                     :classes="combo_classes" class="mt-1" />
               </div>
               <div class="w-1/6">
                  <InputLabel for="bahasaduapreferred" value="Diutamakan?" />
                  <select id="bahasaduapreferred" v-model="resourceForm.bahasaduapreferred"
                     class="mt-1 block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                     <option value="true">Ya</option>
                     <option value="false">Tidak</option>
                  </select>
               </div>
            </div>

            <h2 class="font-semibold text-secondhand-orange-300 mt-4">Kontak Orang Lain</h2>
            <div class="w-full flex space-x-2">
               <div class="w-1/4">
                  <InputLabel for="emerhubungansatu" value="Hubungan" />
                  <Multiselect v-model="resourceForm.emerhubungansatu" mode="single" placeholder="Status" :object="true"
                     :options="contactrelationshipList" label="display" valueProp="code" track-by="code" class="mt-1"
                     :classes="combo_classes" />
               </div>
               <div class="w-1/4">
                  <InputLabel for="emernamasatu" value="Nama" />
                  <TextInput id="emernamasatu" type="text" class="mt-1 block w-full" v-model="resourceForm.emernamasatu"
                     placeholder="Nama" />
               </div>
               <div class="w-1/4">
                  <InputLabel for="emertelphpsatu" value="No HP" />
                  <TextInput id="emertelphpsatu" type="text" class="mt-1 block w-full"
                     v-model="resourceForm.emertelphpsatu" placeholder="No HP" />
               </div>
            </div>
            <div class="w-full flex space-x-2">
               <div class="w-1/4">
                  <InputLabel for="emerhubungandua" value="Hubungan" />
                  <Multiselect v-model="resourceForm.emerhubungandua" mode="single" placeholder="Status" :object="true"
                     :options="contactrelationshipList" label="display" valueProp="code" track-by="code" class="mt-1"
                     :classes="combo_classes" />
               </div>
               <div class="w-1/4">
                  <InputLabel for="emernamadua" value="Nama" />
                  <TextInput id="emernamadua" type="text" class="mt-1 block w-full" v-model="resourceForm.emernamadua"
                     placeholder="Nama" />
               </div>
               <div class="w-1/4">
                  <InputLabel for="emertelphpdua" value="No HP" />
                  <TextInput id="emertelphpdua" type="text" class="mt-1 block w-full" v-model="resourceForm.emertelphpdua"
                     placeholder="No HP" />
               </div>
            </div>
            <div class="flex flex-col items-center justify-end mt-10">
               <MainButton class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0">
                  Daftar
               </MainButton>
            </div>
         </form>
      </div>
      {{ hasil }}
   </AuthenticatedLayout>
</template>
<script setup>
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import MainButton from '@/Components/MainButton.vue';
import InputError from '@/Components/InputError.vue';
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const resourceForm = ref({
   nik: '',
   paspor: '',
   kk: '',
   nama: '',
   nohp: '',
   notelprumah: '',
   email: '',
   gender: null,
   negaralahir: '',
   kotalahir: '',
   tanggallahir: '',
   alamat: '',
   provinsi: null,
   kota: null,
   kecamatan: null,
   keluarahan: null,
   rt: '',
   rw: '',
   kodepos: '',
   kewarganegaraan: '',
   statusnikah: '',
   statusnikahtext: '',
   kelahirankembar: '',
   bahasasatu: null,
   bahasasatupreferred: true,
   bahasadua: null,
   bahasaduapreferred: false,
   emerhubungansatu: null,
   emernamasatu: '',
   emertelphpsatu: '',
   emerhubungandua: null,
   emernamadua: '',
   emertelphpdua: ''
});

const creationSuccessModal = ref(false);
const showPatientDetails = ref(false);
const resultsFound = ref(false);
const patient = ref('');
const nikPatient = ref('');
const cariPatientID = async () => {
   const response = await axios.get(route('satusehat.search.patient',
      { 'identifier': "https://fhir.kemkes.go.id/id/nik|" + nikPatient.value }));
   if (response.data.total >= 1) {
      patient.value = response.data.entry[0];
      showPatientDetails.value = true;
      resultsFound.value = true;
   } else if (response.data.total === 0) {
      patient.value = null;
      showPatientDetails.value = false;
      resultsFound.value = false;
   };
}; 

const submitSatusehat = async () => {
   if (patient.value !== null) {
      await axios.get(route('integration.show', {
         resourceType: 'Patient',
         id: patient.value.resource.id
      })).then(response => {
         creationSuccessModal.value = true;
      })
         .catch(error => {
            console.error('Error creating user:', error);
         });
   } else {
      axios.post(route('users.store'), form)
         .then(response => {
            creationSuccessModal.value = true;
         })
         .catch(error => {
            console.error('Error creating user:', error);
         });
   };
};

const barulahir = ref("search");

const searchNegara = async (query) => {
   const { data } = await axios.get(route('terminologi.iso3166', { 'search': query }));
   const originalData = data;
   return originalData;
};

const searchProvinsi = async (query) => {
   const { data } = await axios.get(route('terminologi.wilayah.provinsi', { 'search': query }));
   const originalData = data;
   return originalData;
};

const searchKota = async (query) => {
   const { data } = await axios.get(route('terminologi.wilayah.kabko', { 'search': query, 'kode_provinsi': resourceForm.value.provinsi.kode_provinsi }));
   const originalData = data;
   return originalData;
};

const searchKecamatan = async (query) => {
   const { data } = await axios.get(route('terminologi.wilayah.kecamatan', { 'search': query, 'kode_kabko': resourceForm.value.kota.kode_kabko }));
   const originalData = data;
   return originalData;
};

const searchKelurahan = async (query) => {
   const { data } = await axios.get(route('terminologi.wilayah.kelurahan', { 'search': query, 'kode_kecamatan': resourceForm.value.kecamatan.kode_kecamatan }));
   const originalData = data;
   return originalData;
};

const searchKotaLahir = async (query) => {
   const { data } = await axios.get(route('terminologi.wilayah.kotalahir', { 'search': query }));
   const originalData = data;
   for (const key in originalData) {
      const currentObject = originalData[key];
      const label = `${currentObject.nama_kabko}, ${currentObject.nama_provinsi}`;
      currentObject.label = label;
   }
   return originalData;
};

const maritalList = ref(null);
const getmaritalList = async () => {
   const { data } = await axios.get(route('terminologi.get'), {
      params: {
         'resourceType': 'Patient',
         'attribute': 'maritalStatus'
      }
   });
   maritalList.value = data;
};

const contactrelationshipList = ref(null);
const getcontactrelationshipList = async () => {
   const { data } = await axios.get(route('terminologi.get'), {
      params: {
         'resourceType': 'PatientContact',
         'attribute': 'relationship'
      }
   });
   contactrelationshipList.value = data;
};

const searchBahasa = async (query) => {
   const { data } = await axios.get(route('terminologi.bcp47', { 'search': query }));
   const originalData = data;
   return originalData;
};

const hasil = ref('');

const submit = async () => {
   const currentTime = new Date().toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '');
   const submitResource = ref({
      "resourceType": "Patient",
      "identifier": [],
      "active": true,
      "name": [
         {
            "use": "official",
            "text": resourceForm.value.nama
         }
      ],
      "telecom": [],
      "gender": resourceForm.value.gender,
      "birthDate": resourceForm.value.tanggallahir.toISOString().split('T')[0],
      "deceasedBoolean": false,
      "address": [
         {
            "use": "home",
            "line": [resourceForm.value.alamat],
            "city": resourceForm.value.kota.nama_kabko,
            "postalCode": resourceForm.value.kodepos,
            "country": "ID",
            "extension": [
               {
                  "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode",
                  "extension": [
                     {
                        "url": "province",
                        "valueCode": resourceForm.value.provinsi.kode_provinsi
                     },
                     {
                        "url": "city",
                        "valueCode": resourceForm.value.kota.kode_kabko
                     },
                     {
                        "url": "district",
                        "valueCode": resourceForm.value.kecamatan.kode_kecamatan
                     },
                     {
                        "url": "village",
                        "valueCode": resourceForm.value.keluarahan.kode_kelurahan
                     },
                     {
                        "url": "rt",
                        "valueCode": resourceForm.value.rt
                     },
                     {
                        "url": "rw",
                        "valueCode": resourceForm.value.rw
                     }
                  ]
               }
            ]
         }
      ],
      "maritalStatus": {
         "coding": [
            {
               "system": resourceForm.value.statusnikah.system,
               "code": resourceForm.value.statusnikah.code,
               "display": resourceForm.value.statusnikah.display
            }
         ],
         "text": resourceForm.value.statusnikahtext
      },
      "multipleBirthInteger": resourceForm.value.kelahirankembar,
      "communication": [
         {
            "language": {
               "coding": [
                  {
                     "system": resourceForm.value.bahasasatu.system,
                     "code": resourceForm.value.bahasasatu.code,
                     "display": resourceForm.value.bahasasatu.display
                  }
               ],
               "text": resourceForm.value.bahasasatu.definition
            },
            "preferred": resourceForm.value.bahasasatupreferred
         }
      ],
      "contact": [],
      "extension": [
         {
            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace",
            "valueAddress": {
               "city": "",
               "country": resourceForm.value.negaralahir.code
            }
         },
         {
            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/citizenshipStatus",
            "valueCode": resourceForm.value.kewarganegaraan
         }
      ]
   });

   if (resourceForm.value.nik !== '' && barulahir.value === false) {
      submitResource.value.identifier.push({
         "use": "official",
         "system": "https://fhir.kemkes.go.id/id/nik",
         "value": resourceForm.value.nik
      });
   } else if (resourceForm.value.nik !== '' && barulahir.value === true) {
      submitResource.value.identifier.push({
         "use": "official",
         "system": "https://fhir.kemkes.go.id/id/nik-ibu",
         "value": resourceForm.value.nik
      });
   };

   if (resourceForm.value.paspor !== '') {
      submitResource.value.identifier.push({
         "use": "official",
         "system": "https://fhir.kemkes.go.id/id/paspor",
         "value": resourceForm.value.paspor
      });
   };

   if (resourceForm.value.kk !== '') {
      submitResource.value.identifier.push({
         "use": "official",
         "system": "https://fhir.kemkes.go.id/id/kk",
         "value": resourceForm.value.kk
      });
   };

   if (resourceForm.value.nohp !== '') {
      submitResource.value.telecom.push({
         "system": "phone",
         "value": resourceForm.value.nohp,
         "use": "mobile"
      });
   };

   if (resourceForm.value.notelprumah !== '') {
      submitResource.value.telecom.push({
         "system": "phone",
         "value": resourceForm.value.notelprumah,
         "use": "home"
      });
   };

   if (resourceForm.value.email !== '') {
      submitResource.value.telecom.push({
         "system": "email",
         "value": resourceForm.value.email,
         "use": "home"
      });
   };

   if (resourceForm.value.emerhubungansatu !== null && resourceForm.value.emernamasatu !== '' && resourceForm.value.emertelphpsatu !== '') {
      submitResource.value.contact.push({
         "relationship": [
            {
               "coding": [
                  {
                     "system": resourceForm.value.emerhubungansatu.system,
                     "code": resourceForm.value.emerhubungansatu.code,
                     "display": resourceForm.value.emerhubungansatu.display
                  }
               ]
            }
         ],
         "name": {
            "use": "official",
            "text": resourceForm.value.emernamasatu
         },
         "telecom": [
            {
               "system": "phone",
               "value": resourceForm.value.emertelphpsatu,
               "use": "mobile"
            }
         ]
      })
   };

   if (resourceForm.value.emerhubungandua !== null && resourceForm.value.emernamadua !== '' && resourceForm.value.emertelphpdua !== '') {
      submitResource.value.contact.push({
         "relationship": [
            {
               "coding": [
                  {
                     "system": resourceForm.value.emerhubungandua.system,
                     "code": resourceForm.value.emerhubungandua.code,
                     "display": resourceForm.value.emerhubungandua.display
                  }
               ]
            }
         ],
         "name": {
            "use": "official",
            "text": resourceForm.value.emernamadua
         },
         "telecom": [
            {
               "system": "phone",
               "value": resourceForm.value.emertelphpdua,
               "use": "mobile"
            }
         ]
      })
   };

   if (resourceForm.value.bahasadua !== null) {
      submitResource.value.language.push(
         {
            "language": {
               "coding": [
                  {
                     "system": resourceForm.value.bahasadua.system,
                     "code": resourceForm.value.bahasadua.code,
                     "display": resourceForm.value.bahasadua.display
                  }
               ],
               "text": resourceForm.value.bahasadua.definition
            },
            "preferred": resourceForm.value.bahasaduapreferred
         }
      )
   };

   if (resourceForm.value.negaralahir.code === "ID") {
      submitResource.value.extension[0].valueAddress.city = resourceForm.value.kotalahir.nama_kabko
   } else {
      submitResource.value.extension[0].valueAddress.city = resourceForm.value.kotalahir
   };

   if (submitResource.value.contact == [] || resourceForm.value.emerhubungandua === null ||
      resourceForm.value.emernamadua === '' || resourceForm.value.emertelphpdua === '' ||
      resourceForm.value.emerhubungansatu === null ||
      resourceForm.value.emernamasatu === '' || resourceForm.value.emertelphpsatu === '') {
      delete submitResource.value.contact;
   };

   hasil.value = submitResource;

   // try {
   //    const response = axios.post(route('integration.store', { res_type: "Encounter" }), submitResource);
   // } catch (error) {
   //    console.error(error.response.data);
   // }
};

const combo_classes = {
   container: 'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer border-2 border-neutral-grey-0 ring-0 shadow-sm rounded-xl bg-white text-base leading-snug outline-none',
   search: 'w-full absolute inset-0 outline-none border-0 ring-0 focus:ring-original-teal-300 focus:ring-2 appearance-none box-border text-base font-sans bg-white rounded-xl pl-3.5 rtl:pl-0 rtl:pr-3.5',
   placeholder: 'flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-500 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
   optionPointed: 'text-white bg-original-teal-300',
   optionSelected: 'text-white bg-original-teal-300',
   optionDisabled: 'text-gray-300 cursor-not-allowed',
   optionSelectedPointed: 'text-white bg-original-teal-300 opacity-90',
   optionSelectedDisabled: 'text-green-100 bg-original-teal-300 bg-opacity-50 cursor-not-allowed',
};

onMounted(() => {
   getmaritalList();
   getcontactrelationshipList();
})

</script>

<style>
.dp__theme_light {
   --dp-background-color: #fff;
   --dp-text-color: #323232;
   --dp-hover-color: #f3f3f3;
   --dp-hover-text-color: #323232;
   --dp-hover-icon-color: #499d8c;
   --dp-primary-color: #499d8c;
   --dp-primary-disabled-color: #6db1a3;
   --dp-primary-text-color: #f8f5f5;
   --dp-secondary-color: #499d8c;
   --dp-border-color: #b5b3bc;
   --dp-border-color-hover: #499d8c;
   --dp-menu-border-color: #ddd;
   --dp-highlight-color: #499d8c;
}

:root {
   /*General*/
   --dp-font-family: "Poppins", "Open Sans", "Helvetica Neue", sans-serif;
   --dp-border-radius: 10px;
   --dp-cell-border-radius: 12px;
   --dp-input-padding: 10px 12px;
   --dp-font-size: 0.875rem;
}
</style>