<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Tambah User - </title>
        </template>
        <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <h1 class="text-2xl font-bold text-neutral-black-300">Tambah User</h1>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman untuk menambahkan user.</p>
            <Modal :show="creationSuccessModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        User telah berhasil dibuat. Kembali ke halaman User Management.
                    </h2>
                    <div class="mt-6 flex justify-end">
                        <Link :href="route('usermanagement')"
                            class="mx-auto mb-3 w-fit block justify-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                        Kembali </Link>
                    </div>
                </div>
            </Modal>
            <form>
                <div>
                    <InputLabel for="name" value="Nama" />
                    <TextInput id="name" type="text" class="mt-1 block w-full" required v-model="form.name"
                        placeholder="Masukkan Nama" />
                    <InputError class="mt-1" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="email" value="Email" />
                    <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                        autocomplete="username" placeholder="Masukkan Email" />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Password" />
                    <div class="relative p-0 rounded-xl border-none text-neutral-black-300">
                        <TextInput id="password" :type="togglePassword" class="mt-1 block w-full" v-model="form.password"
                            required placeholder="Masukkan Password">
                        </TextInput>
                        <div class="absolute inset-y-0 right-0 mx-3 w-5 h-5 my-auto">
                            <div class="text-neutral-grey-100 hover:text-original-teal-300 active:text-original-teal-400 cursor-pointer"
                                @click="togglePasswordVisibility">
                                <svg v-show="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg v-show="showPassword" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <InputError class="mt-1" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password_confirmation" value="Konfirmasi Password" />
                    <div class="relative p-0 rounded-xl border-none text-neutral-black-300">
                        <TextInput id="password_confirmation" :type="togglePassword" class="mt-1 block w-full"
                            v-model="form.password_confirmation" required placeholder="Masukkan Konfirmasi Password">
                        </TextInput>
                        <div class="absolute inset-y-0 right-0 mx-3 w-5 h-5 my-auto bg-original-white-0">
                            <div class="text-neutral-grey-100 hover:text-original-teal-300 active:text-original-teal-400 cursor-pointer"
                                @click="togglePasswordVisibility">
                                <svg v-show="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg v-show="showPassword" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <InputError class="mt-1" :message="form.errors.password_confirmation" />
                </div>
                <div class="mt-4">
                    <InputLabel for="role" value="Peran" />
                    <select id="role" v-model="form.role"
                        class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                        <option v-for="peran in peranList" :value="peran">{{peran}}</option>
                    </select>
                    <InputError class="mt-1" />
                </div>
            </form>
            <form>
                <div class="block mt-4">
                    <label class="flex items-center">
                        <input id="practitionercheck" type="checkbox" @click="togglePractitioner"
                            class="w-4 h-4 text-original-teal-300 bg-original-white-0 border-neutral-grey-100 rounded focus:ring-original-teal-300 ">
                        <span class="ms-2 text-sm text-neutral-black-300">Practitioner</span>
                    </label>
                </div>

                <div class="mt-4" v-show="isPractitioner">
                    <InputLabel for="nik" value="Cari Practitioner ID" />
                    <div class="w-full flex">
                        <TextInput id="nik" type="text" class="mt-1 block w-full mr-3" v-model="nikPractitioner" autofocus
                            placeholder="Masukkan NIK" />
                        <MainButtonSmall @click="cariPractitionerID" class="teal-button text-original-white-0"
                            type="button">
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
            <p v-show="!resultsFound && isPractitioner" class="text-center mt-5"> Data tidak ditemukan.</p>
            <div class="flex mt-5 " v-if="showPractitionerDetails">
                <div class="relative w-full pl-14 overflow-x-auto mb-5">
                    <table class="w-full mx-auto text-base text-left text-neutral-grey-200 ">
                        <tbody class="w-full">
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/3">
                                    Nama
                                </th>
                                <td v-if="practitioner.resource.name" class="px-6 py-4 w-2/3">
                                    {{ practitioner.resource.name[0].text }}
                                </td>
                            </tr>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/3">
                                    Tanggal Lahir
                                </th>
                                <td class="px-6 py-4 w-full">
                                    {{ practitioner.resource.birthDate }}
                                </td>
                            </tr>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/3">
                                    Gender
                                </th>
                                <td class="px-6 py-4 w-full">
                                    {{ practitioner.resource.gender }}
                                </td>
                            </tr>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/3">
                                    Identifier
                                </th>
                                <td class="px-6 py-4 w-2/3">
                                    <p v-for="item in practitioner.resource.identifier">{{ item.system }}: {{ item.value }}
                                    </p>
                                </td>
                            </tr>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/3">
                                    Alamat
                                </th>
                                <td v-if="practitioner.resource.address" class="px-6 py-4 w-2/3">
                                    {{ practitioner.resource.address[0].line[0] }}
                                </td>
                            </tr>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/3">
                                    Kota
                                </th>
                                <td v-if="practitioner.resource.address" class="px-6 py-4 w-2/3">
                                    {{ practitioner.resource.address[0].city }}
                                </td>
                            </tr>
                            <tr class="bg-original-white-0">
                                <th scope="row" class="px-6 py-4 font-semibold whitespace-nowrap w-1/3">
                                    Kontak
                                </th>
                                <td v-if="practitioner.resource.telecom" class="px-6 py-4 w-2/3">
                                    <p v-for="telecom in practitioner.resource.telecom">{{ telecom.system }}: {{
                                        telecom.value }}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="flex flex-col items-center justify-end mt-10">
                <MainButton class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0"
                    @click="submit">
                    Tambah
                </MainButton>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import MainButton from '@/Components/MainButton.vue';
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    practitioner_id: '',
    role: ''
});


const creationSuccessModal = ref(false);
const showPractitionerDetails = ref(false);
const resultsFound = ref(false);
const practitioner = ref(null);

const nikPractitioner = ref('');
const peranList = ref(null);
const getperanList = async () => {
    const { data } = await axios.get(route('users.roles'));
    peranList.value = data;
};


const cariPractitionerID = async () => {
    const response = await axios.get(route('satusehat.search.practitioner',
        { 'identifier': "https://fhir.kemkes.go.id/id/nik|" + nikPractitioner.value }));
    if (response.data.total >= 1) {
        practitioner.value = response.data.entry[0];
        showPractitionerDetails.value = true;
        resultsFound.value = true;
    } else if (response.data.total === 0) {
        practitioner.value = null;
        showPractitionerDetails.value = false;
        resultsFound.value = false;
    };
};

const submit = async () => {
    if (isPractitioner.value === false || practitioner.value === null) {
        delete form.practitioner_id;
    } else if (isPractitioner.value === true && practitioner.value !== null) {
        form.practitioner_id = practitioner.value.resource.id;
        await axios.get(route('integration.show', {
            res_type: 'Practitioner',
            satusehat_id: form.practitioner_id
        }));
    };

    axios.post(route('users.store'), form)
        .then(response => {
            creationSuccessModal.value = true;
        })
        .catch(error => {
            console.error('Error creating user:', error);
        });
};

const showPassword = ref(false);
const togglePassword = ref('password');

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
    togglePassword.value = togglePassword.value === 'password' ? 'text' : 'password';
};

const isPractitioner = ref(false);

const togglePractitioner = () => {
    isPractitioner.value = !isPractitioner.value;
};

onMounted(() => {
    getperanList();
})
</script>
