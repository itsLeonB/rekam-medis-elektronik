<template>
    <AuthenticatedLayout>
        <div class="bg-original-white-0 flex justify-between shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-0 md:px-10">
            <div class="md:py-8">
                <span class="inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-7 mr-2" viewBox="0 0 33 35" fill="none">
                        <path
                            d="M17.5762 7.58874C19.568 7.58874 21.1826 6.22573 21.1826 4.54437C21.1826 2.86301 19.568 1.5 17.5762 1.5C15.5844 1.5 13.9697 2.86301 13.9697 4.54437C13.9697 6.22573 15.5844 7.58874 17.5762 7.58874Z"
                            stroke="currentColor" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M10.7138 19.9147L17.179 25.131C17.8167 25.6508 18.2785 26.3005 18.4984 27.0244L19.9278 31.6095C20.2797 32.7419 21.6651 33.4102 23.0065 33.0946C24.3039 32.779 25.0736 31.6652 24.7437 30.57L23.1604 25.1495C23.0505 24.7411 22.8086 24.3513 22.4787 24.0357L18.0366 19.5806L19.8398 14.0487L22.8745 16.5362C23.0945 16.7218 23.3583 16.8518 23.6222 16.9632L27.5365 18.3554C28.5261 18.7081 29.6696 18.2997 30.0874 17.4644C30.4613 16.6476 30.0215 15.738 29.0979 15.3667L25.0736 13.8445C24.6558 13.696 24.3039 13.4547 24.04 13.1391L19.8619 8.46119C19.554 8.12705 19.0702 7.92285 18.5644 7.92285H15.0019C14.6721 7.92285 14.3202 7.99711 14.0343 8.12705L8.40476 10.6888C7.78902 10.9672 7.30524 11.3942 6.97539 11.8954L4.0946 16.5176C3.6108 17.2973 3.98464 18.244 4.88626 18.6338C5.78787 19.0237 6.86543 18.7267 7.34922 17.9841L10.186 13.6032L12.9568 12.7122L10.5598 20.4716C10.4939 20.6944 10.4499 20.8986 10.4059 21.1213L9.79018 25.2609C9.74619 25.5208 9.61425 25.7807 9.41634 25.9849L5.32609 29.976C4.46846 30.8113 4.62237 32.0922 5.67792 32.7605C6.6455 33.373 8.00893 33.2431 8.77859 32.4635L13.6385 27.6184C14.0124 27.2286 14.2543 26.7645 14.3202 26.2633L14.6941 23.0148"
                            stroke="currentColor" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <h1 class="text-2xl font-bold text-neutral-black-300">Rawat Inap</h1>
                </span>
                <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman Pasien Rawat Inap.
                </p>
                <div class="flex flex-col sm:flex-row">
                    <Link :href="route('rawatinap.daftar')" as="button"
                        class="inline-flex mb-3 mr-5 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Pendaftaran Rawat Inap
                    </Link>
                    <select id="ruangan_id" v-model="ruangan_id" @change="fetchPatient"
                        class="bg-original-white-0 border border-neutral-grey-0 text-neutral-black-300 text-sm rounded-lg focus:ring-original-teal-300 focus:border-original-teal-300 block w-52 px-2.5 h-fit">
                        <option v-for="item in ruangan" :value=item.id>{{ item.label }}</option>
                    </select>
                </div>
            </div>
            <div class="overflow-hidden hidden lg:block lg:h-48">
                <img :src="'storage/images/r' + ruangan_id + '.png'" class="h-[150%]" alt="">
            </div>
        </div>
        <div class="bg-original-white-0 overflow-hidden shadow sm:rounded-2xl mb-8 py-8 pl-10 pr-14">
            <h1 class="mb-4 text-2xl font-bold text-secondhand-orange-300">Daftar Pasien</h1>
            <div class="relative overflow-x-auto mb-5">
                <table class="w-full text-base text-left rtl:text-right text-neutral-grey-200 ">
                    <thead class="text-base text-center text-neutral-black-300 bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-2/6">
                                Nama
                            </th>
                            <th scope="col" class="px-6 py-3 w-1/6">
                                No RM
                            </th>
                            <th scope="col" class="px-6 py-3 w-1/6">
                                Waktu dan Status
                            </th>
                            <th scope="col" class="px-6 py-3 w-2/6">
                                Dokter
                            </th>
                        </tr>
                    </thead>
                    <tbody v-for="(patient, index) in patients" :key="index">
                        <tr class="bg-original-white-0 hover:bg-thirdinner-lightteal-300"
                            :class="{ 'border-b': index !== (patients.length - 1) }">
                            <Link :href="route('rawatinap.details', { 'encounter_satusehat_id': patient.encounter_satusehat_id })">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap hover:underline w-2/5">
                                {{ patient.patient_name }}
                            </th>
                            </Link>
                            <td class="px-6 py-4 w-2/5">
                                {{ patient.patient_identifier }}
                            </td>
                            <td class="px-6 py-4 w-2/5">
                                {{ formatTimestamp(patient.period_start) }}
                            </td>
                            <td class="px-6 py-4 w-2/5">
                                {{ patient.patient_name }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutNav.vue';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const ruangan_id = ref(263);

const ruangan = [
    {
        "id": 263,
        "label": 'Ruang Bersalin'
    },
    {
        "id": 189,
        "label": 'Ruang Neonatus'
    },
    {
        "id": 221,
        "label": 'Ruang Interna & Bedah'
    },
    {
        "id": 124,
        "label": 'Ruang Paviliun'
    },
    {
        "id": 286,
        "label": 'Ruang Anak'
    }
];

const patients = ref([]);

const fetchPatient = async () => {
    const { data } = await axios.get(route('daftar-pasien.rawat-inap', { serviceType: ruangan_id.value }));
    patients.value = data;
    console.log(patients.value)
};

const formatTimestamp = (timestamp) => {
    const date = new Date(timestamp);
    date.setHours(date.getHours() + 7);
    const options = { day: '2-digit', month: 'long', year: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', timeZone: 'UTC' };
    return date.toLocaleDateString('id-ID', options);
};

</script>