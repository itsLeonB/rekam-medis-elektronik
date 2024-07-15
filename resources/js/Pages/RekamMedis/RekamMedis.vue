<template>
    <AuthenticatedLayout>
        <div class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <span class="inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-2" viewBox="0 0 35 33" fill="none">
                    <path
                        d="M22.6981 20.9552H20.9038V19.2679C20.9038 19.1296 20.8456 18.9989 20.7451 18.9038C20.6448 18.8091 20.5106 18.7572 20.3722 18.7572H17.4647C17.3263 18.7572 17.1921 18.8091 17.0918 18.9038C16.9912 18.9989 16.9331 19.1296 16.9331 19.2679V20.9552H15.1385C15.0001 20.9552 14.8658 21.007 14.7656 21.1018C14.665 21.1968 14.6068 21.3275 14.6068 21.4658V24.2134C14.6068 24.3517 14.665 24.4824 14.7656 24.5774C14.8658 24.6722 15.0001 24.724 15.1385 24.724H16.9331V26.4117C16.9331 26.5499 16.9912 26.6807 17.0918 26.7757C17.1921 26.8705 17.3263 26.9223 17.4647 26.9223H20.3722C20.5106 26.9223 20.6448 26.8705 20.7451 26.7757C20.8456 26.6807 20.9038 26.5499 20.9038 26.4117V24.724H22.6981C22.8364 24.724 22.9707 24.6722 23.0709 24.5774C23.1715 24.4824 23.2297 24.3517 23.2297 24.2134V21.4658C23.2297 21.3275 23.1715 21.1968 23.0709 21.1018C22.9707 21.007 22.8364 20.9552 22.6981 20.9552ZM22.1664 23.7028H20.3722C20.2339 23.7028 20.0996 23.7546 19.9993 23.8494C19.8988 23.9444 19.8406 24.0752 19.8406 24.2134V25.9011H17.9963V24.2134C17.9963 24.0752 17.9381 23.9444 17.8375 23.8494C17.7373 23.7546 17.603 23.7028 17.4647 23.7028H15.6701V21.9764H17.4647C17.603 21.9764 17.7373 21.9246 17.8375 21.8298C17.9381 21.7348 17.9963 21.604 17.9963 21.4658V19.7785H19.8406V21.4658C19.8406 21.604 19.8988 21.7348 19.9993 21.8298C20.0996 21.9246 20.2339 21.9764 20.3722 21.9764H22.1664V23.7028Z"
                        fill="currentColor" stroke="currentColor" stroke-width="0.3" />
                    <path
                        d="M30.4524 7.13704L30.4442 7.13703L30.436 7.13756C30.2052 7.15252 29.5791 7.14548 28.7971 7.13668C28.3129 7.13124 27.7688 7.12512 27.2217 7.12314C26.5138 7.12057 25.8065 7.12513 25.2356 7.14799C24.9504 7.15941 24.6956 7.17553 24.49 7.19815C24.4407 7.20357 24.3922 7.20955 24.3452 7.21632V2.69708V2.69683C24.3447 2.15139 24.115 1.63149 23.7117 1.25034L23.541 1.43095L23.7117 1.25034C23.3089 0.869637 22.7656 0.657793 22.2016 0.657227H22.2014H6.07132V0.657007L6.06086 0.657446C5.87412 0.665266 5.6942 0.735088 5.55321 0.857543L5.55307 0.857377L5.54544 0.864583L0.975444 5.18323L0.975293 5.18307L0.96798 5.19059C0.8374 5.32478 0.759009 5.5007 0.750272 5.68797L0.75 5.68796V5.69962L0.75 27.4222L0.75 27.4224C0.75055 27.9679 0.980224 28.4878 1.38357 28.869C1.78643 29.2497 2.32981 29.4615 2.8938 29.462H2.89403H3.87913C4.0473 30.2397 4.48158 30.9433 5.11464 31.4631C5.80461 32.0296 6.68515 32.3407 7.59478 32.3422H7.59521H30.4522H30.4524C31.4548 32.3411 32.4185 31.9644 33.1313 31.2908C33.8446 30.6167 34.2488 29.6996 34.25 28.7398V28.7395V10.7398V10.7394C34.2488 9.77967 33.8446 8.86261 33.1313 8.18851C32.4185 7.51486 31.4548 7.13819 30.4524 7.13704ZM21.3215 10.7397V12.1771H7.59521H7.59492C6.59258 12.1783 5.62901 12.555 4.91623 13.2286C4.20298 13.9028 3.79896 14.8198 3.79785 15.7795V15.7798V28.0169H2.89425C2.71902 28.0168 2.55367 27.9508 2.43382 27.8376C2.31451 27.7248 2.25022 27.5751 2.25 27.4221V6.42212H6.07132C6.26581 6.42212 6.45498 6.34924 6.59658 6.21543L6.42487 6.03373L6.59658 6.21543C6.73866 6.08117 6.82132 5.89595 6.82132 5.69962V2.10223L22.2012 2.10223C22.2012 2.10223 22.2013 2.10223 22.2013 2.10223C22.3765 2.10241 22.5418 2.16839 22.6616 2.28161C22.781 2.39441 22.8453 2.54425 22.8454 2.69733V7.85792C22.3936 8.17805 22.0213 8.59065 21.7575 9.0668C21.4719 9.58215 21.322 10.1561 21.3215 10.7395V10.7397ZM26.6431 13.6222L26.6449 13.6222C26.8713 13.6205 27.061 13.536 27.1932 13.3942C27.3221 13.2559 27.3839 13.0766 27.3851 12.9027C27.3862 12.7286 27.3266 12.548 27.1975 12.4081C27.0651 12.2648 26.8743 12.1801 26.6462 12.1772L26.6463 12.1772H26.6431H22.8213V10.7401C22.822 10.1726 23.0607 9.62556 23.4901 9.21977C23.92 8.81353 24.5057 8.58272 25.1194 8.58204H30.4522C31.0658 8.5828 31.6515 8.81364 32.0813 9.21987C32.5106 9.62566 32.7493 10.1727 32.75 10.7401V28.7392C32.7493 29.3066 32.5106 29.8537 32.0812 30.2595C31.6513 30.6657 31.0656 30.8965 30.4519 30.8972H7.59549C6.98185 30.8965 6.3962 30.6657 5.9664 30.2595C5.53707 29.8537 5.29845 29.3066 5.29785 28.7392V15.7802C5.29845 15.2128 5.53707 14.6657 5.9664 14.2599C6.3962 13.8537 6.98185 13.6229 7.59549 13.6222L26.6431 13.6222ZM3.33555 4.97712L5.32132 3.10057V4.97712H3.33555Z"
                        fill="currentColor" stroke="currentColor" stroke-width="0.5" />
                    <path
                        d="M9.12007 4.7326H20.5678C21.2181 4.73083 21.2246 3.79009 20.5678 3.7876H9.12007C8.46979 3.78944 8.46319 4.7301 9.12007 4.7326Z"
                        fill="currentColor" />
                    <path
                        d="M9.12007 7.61199H20.5678C21.2181 7.61022 21.2246 6.66949 20.5678 6.66699H9.12007C8.46979 6.6689 8.46319 7.6095 9.12007 7.61199Z"
                        fill="currentColor" />
                    <path
                        d="M4.54865 10.4924H19.0248C19.6739 10.4907 19.6824 9.54986 19.0248 9.54736H4.54865C3.89948 9.549 3.89101 10.4899 4.54865 10.4924Z"
                        fill="currentColor" />
                </svg>
                <h1 class="text-2xl font-bold text-neutral-black-300">Rekam Medis Pasien</h1>
            </span>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman Rekam Medis Pasien.
            </p>
            <Link v-if="['admin', 'perekammedis'].includes($page.props.auth.user.roles[0].name)"
                :href="route('rekammedis.tambah')" as="button"
                class="inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Rekam Medis Baru
            </Link>
        </div>
        <div
            class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:pl-10 md:pr-14">
            <!-- Search bar -->
            <div class="flex flex-col md:flex-row md:justify-end md:items-center mb-5 w-full">
                <form class="mr-3 w-full ">
                    <div class="relative p-0 rounded-xl w-full border-none text-neutral-black-300">
                        <div class="absolute inset-y-0 left-0 mx-3 w-5 h-5 my-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="#8f8f8f" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <input v-model="searchQuery" id="searchQuery" placeholder="Cari"
                            class="pl-9 h-9 block w-full border border-1 outline-none focus:border-original-teal-300 focus:ring-original-teal-300 hover:ring-1 hover:ring-original-teal-300 rounded-xl shadow" />
                        <div class="absolute inset-y-0 right-0 mx-3 w-5 h-5 my-auto cursor-pointer"
                            @click="cancelSearch" v-show="hide">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#8f8f8f"
                                class="w-5 h-5 hover:fill-thirdouter-red-200">
                                <path fill-rule="evenodd"
                                    d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </form>
                <div class="flex mt-4 md:mt-0">
                    <select id="searchWith_id" v-model="searchWith_id"
                        class="bg-original-white-0 mr-3 border-1 border-neutral-grey-0 text-neutral-black-300 text-sm rounded-lg focus:ring-original-teal-300 focus:border-original-teal-300 block w-40 px-2.5 h-fit">
                        <option v-for="item in searchWith" :value=item.id>{{ item.label }}</option>
                    </select>
                    <MainButton @click="searchPatients" class="teal-button text-original-white-0">
                        Cari
                    </MainButton>
                </div>
            </div>
            <div class="relative overflow-x-auto mb-5">
                <table class="w-full text-sm text-center rtl:text-right text-neutral-grey-200 ">
                    <thead class="text-sm font-thin text-neutral-black-300 bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-3/12">
                                Nama
                            </th>
                            <th scope="col" class="px-6 py-3 w-3/12">
                                Nomor Rekam Medis
                            </th>
                            <th scope="col" class="px-6 py-3 w-3/12">
                                Kunjungan Terakhir
                            </th>
                            <th scope="col" class="px-6 py-3 w-3/12">
                                Tanggal Terakhir ke RS
                            </th>
                        </tr>
                    </thead>
                    <tbody v-for="(patient, index) in patients.data" :key="index">
                        <tr class="bg-original-white-0 hover:bg-thirdinner-lightteal-300"
                            :class="{ 'border-b': index !== (patients.data.length - 1) }">
                            <Link :href="route('rekammedis.details', { 'patient_satusehat_id': patient.satusehatId })">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap hover:underline w-3/12">
                                <P>{{ patient.name }}</P>
                                <p v-show="searchWith_id !== 'name' && hide === true">{{ searchWith.find(item => item.id
                                    ===
                                    searchWith_id).label }}: {{ patient[searchWith_id] }}</p>
                            </th>

                            <td class="px-6 py-4 w-3/12">
                                {{ patient['rekam-medis'] == null ? '-' : patient['rekam-medis'] }}
                            </td>
                            </Link>
                            <td class="px-6 py-4 w-3/12">
                                <p>{{ patient.class == 'AMB' ? 'Rawat Jalan' : patient.class == 'IMP' ? 'Rawat Inap' :
                                    patient.class == 'EMER' ? 'IGD' : '-' }}</p>
                                <!-- <p v-if="patient.class === 'AMB'">{{ patient.serviceType }}</p>
                                <p v-else-if="patient.class === 'IMP'">{{ patient.serviceType }}</p> -->

                            </td>
                            <td class="px-6 py-4 w-3/12">
                                <p>{{ formatTimestamp(patient.start).split('/')[0] }}</p>
                                <p>Jam {{ formatTimestamp(patient.start).split('/')[1] }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="text-center mt-4" v-if="searchQuery !== '' && patients.data.length === 0">Data tidak ditemukan
                </p>
            </div>

            <nav class="flex justify-end">
                <ul class="inline-flex -space-x-px text-base h-10">
                    <li>
                        <button
                            @click="fetchPagination((patients.current_page - 1) < 1 ? 1 : (patients.current_page - 1))"
                            class="flex items-center justify-center px-4 h-10 leading-tight text-neutral-grey-200 bg-original-white-0 border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700">&laquo;</button>
                    </li>
                    <template v-for="(item, index) in paging">
                        <li v-if="item !== patients.current_page">
                            <button @click="fetchPagination(item === '...' ? patients.current_page : item)"
                                class="flex items-center justify-center px-4 h-10 text-neutral-grey-200 bg-original-white-0 border border-gray-300 hover:bg-gray-100 hover:text-gray-700 ">{{
                                    item }}</button>
                        </li>
                        <li v-else-if="item === patients.current_page">
                            <button @click="fetchPagination(item)"
                                class="flex items-center justify-center px-4 h-10 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 ">{{
                                    item }}</button>
                        </li>
                    </template>
                    <li>
                        <button
                            @click="fetchPagination((patients.current_page + 1) > patients.last_page ? patients.last_page : (patients.current_page + 1))"
                            class="flex items-center justify-center px-4 h-10 leading-tight text-neutral-grey-200 bg-original-white-0 border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700">&raquo;</button>
                    </li>
                </ul>
            </nav>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutNav.vue';
import MainButton from '@/Components/MainButton.vue';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const patients = ref([]);

const hide = ref(false);

const fetchPatients = async (page = 1) => {
    const { data } = await axios.get(route('rekam-medis.index', { 'page': page }));
    patients.value = data.rekam_medis;
    generateNumbers(1, patients.value.current_page, patients.value.last_page);
};

const cancelSearch = async () => {
    hide.value = false;
    searchQuery.value = '';
    fetchPatients(1);
};

const searchQuery = ref('');

const searchPatients = async () => {
    hide.value = true;
    const query = searchQuery.value;
    const { data } = await axios.get(route('rekam-medis.index', { [searchWith_id.value]: query }));
    patients.value = data.rekam_medis;
    generateNumbers(1, patients.value.current_page, patients.value.last_page);
};

const fetchPagination = async (page = 1) => {
    if (searchQuery.value == '') {
        const { data } = await axios.get(route('rekam-medis.index', { 'page': page }));
        patients.value = data.rekam_medis;
        generateNumbers(1, patients.value.current_page, patients.value.last_page);
    } else {
        const query = searchQuery.value;
        const { data } = await axios.get(route('rekam-medis.index'), { params: { [searchWith_id.value]: query, 'page': page } });
        patients.value = data.rekam_medis;
        generateNumbers(1, patients.value.current_page, patients.value.last_page);
    };
};

const searchWith_id = ref('name');
const searchWith = [
    { "id": 'name', "label": 'Nama' },
    { "id": 'nik', "label": 'NIK' },
    { "id": 'nik-ibu', "label": 'NIK Ibu' },
    { "id": 'paspor', "label": 'Paspor' },
    { "id": 'kk', "label": 'No KK' },
    { "id": 'ihs-number', "label": 'IHS Number' }
];

const poli = [
    { "id": '124', "value": 'umum', "label": 'Poli Umum' },
    { "id": '177', "value": 'neurologi', "label": 'Poli Neurologi' },
    { "id": '186', "value": 'obgyn', "label": 'Poli Obgyn' },
    { "id": '88', "value": 'gigi', "label": 'Poli Gigi' },
    { "id": '168', "value": 'kulit', "label": 'Poli Kulit dan Kelamin' },
    { "id": '218', "value": 'ortopedi', "label": 'Poli Ortopedi' },
    { "id": '557', "value": 'dalam', "label": 'Poli Penyakit Dalam' },
    { "id": '221', "value": 'bedah', "label": 'Poli Bedah' },
    { "id": '286', "value": 'anak', "label": 'Poli Anak' }
];

const findPoli = (serviceType) => {
    return poli.find(pol => pol.id === serviceType).label;
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

const paging = ref([]);

const generateNumbers = (firstNumber, currentNumber, lastNumber) => {
    const result = [];
    if (lastNumber > 5 && (currentNumber < 3 || currentNumber > lastNumber - 2)) {
        result.push(firstNumber, firstNumber + 1);
        result.push('...');
        result.push(lastNumber - 1, lastNumber);
    } else if (lastNumber > 5) {
        result.push(firstNumber, firstNumber + 1);
        result.push('...');
        result.push(currentNumber);
        result.push('...');
        result.push(lastNumber - 1, lastNumber);
    } else {
        for (let i = firstNumber; i <= lastNumber; i++) {
            result.push(i);
        }
    }
    paging.value = result;
};

onMounted(() => {
    fetchPatients();
}
);

</script>