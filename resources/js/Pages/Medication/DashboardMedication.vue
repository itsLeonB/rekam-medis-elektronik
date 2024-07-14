<template>
    <AuthenticatedLayout>
        <div class="flex flex-row justify-between bg-white overflow-hidden rounded-xl shadow h-20 md:h-36 md:rounded-2xl mb-8">
            <div class="pl-8 my-auto">
                <h2 class="text-3xl font-bold text-secondhand-orange-300 mb-1">Dashboard stok obat</h2>
            </div>
            <div class="hidden md:block md:mr-8">
                <img src="storage/images/pharmacy.jpg" class="h-full mx-auto" alt="">
            </div>
        </div>
        <div class="flex flex-col items-center space-y-8 md:flex-row md:space-x-8 md:space-y-0 mb-8">
            <div v-for="(card, index) in cardsData" :key="index" class="px-10 py-6 w-full flex flex-1 items-center h-full bg-white overflow-hidden shadow rounded-lg md:rounded-xl">
            <div class="mr-5 h-fit" v-html="card.svg"></div>
            <div class="h-fit">
                <h2 class="text-base font-normal text-neutral-grey-100">{{ card.title }}</h2>
                <span class="text-xl font-semibold text-neutral-black-500">{{ card.value }} {{ card.unit }}</span>
            </div>
            </div>
        </div>
        <div class="flex flex-col space-y-8 md:flex-row md:space-x-8 md:space-y-0">
            <LineChart :title="'Perbandingan Transaksi Per Bulan (Total Kuantitas)'" :options="bulan" :series="jumlahTransaksiperBulan" :forecastSeries="foreCastTransaksiPerBulan" class="basis-full" />
        </div>
        <div class="flex justify-center mt-8">
            <div class="flex justify-center">
            <a  v-if="['admin', 'apoteker'].includes($page.props.auth.user.roles[0].name)" :href="route('medication.table')" :active="route().current('medication.table')" class="bg-secondhand-orange-300 text-white font-bold py-2 px-4 rounded hover:bg-secondhand-orange-400 transition">
                View Detailed Table
            </a>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutNav.vue';
import { ref, onMounted, computed, onActivated  } from 'vue';
import axios from 'axios';
import LineChart from '@/Components//LineChart.vue';
const bulan = ref({
  chart: {
    type: 'line',
    height: 350
  },
  xaxis: {
    categories: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
  },
  title: {
    text: 'Monthly Data Comparison by Year'
  }
});

const mendekatiKadaluarsa = ref(0);
const fetchMendekatiKadaluarsa = async () => {
    try {
        const response = await axios.get(route('analytics.obat-mendekati-kadaluarsa'));
        mendekatiKadaluarsa.value = response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

const stokSedikit = ref(0);
const fetchStokSedikit = async () => {
    try {
        const response = await axios.get(route('analytics.obat-stok-sedikit'));
        stokSedikit.value = response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};
const fastMoving = ref(0);
const fetchFastMoving = async () => {
    try {
        const response = await axios.get(route('analytics.obat-fast-moving'));
        fastMoving.value = response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};
const penggunaanPalingBanyak = ref(0);
const fetchPenggunaanPalingBanyak = async () => {
    try {
        const response = await axios.get(route('analytics.obat-penggunaan-paling-banyak'));
        penggunaanPalingBanyak.value = response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

// Series data including actual and forecast data
const jumlahTransaksiperBulan = ref([]);
const fetchPerbandinganTransaksiPerBulan = async () => {
    try {
        const response = await axios.get(route('analytics.obat-transaksi-perbandingan-per-bulan'));
        jumlahTransaksiperBulan.value = response.data;
        saveMonthlyData();
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

const saveMonthlyData = async () => {
    try {
        const response = await axios.post(route('analytics.save-monthly-data', {data: jumlahTransaksiperBulan.value}));
        runScripts();
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

const runScripts = async () => {
    try {
        const response = await axios.get(route('analytics.forecast'));
        fetchForecast();
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

const foreCastTransaksiPerBulan = ref([]);
const fetchForecast = async () => {
    try {
        const response = await axios.get(route('analytics.fetch-forecast'));
        foreCastTransaksiPerBulan.value = response.data
        console.log(foreCastTransaksiPerBulan.value, response.data, 'daffaforecast')
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

const cardsData = ref([
    {
        // < 1 bulan
        title: 'Obat Mendekati Kadaluarsa',
        value: mendekatiKadaluarsa,
        unit: 'Unit',
    },
    {
        title: 'Obat Stok Sedikit',
        value: stokSedikit,
        unit: 'Unit',
    },
    {
        title: 'Obat Fast Moving',
        value: fastMoving,
        unit: 'Unit',
    },
    {
        //< 1 Bulan, Transaksi > 50 Pcs
        title: 'Obat Penggunaan Paling Banyak',
        value: penggunaanPalingBanyak,
        unit: 'Unit',
    },
]);

onMounted(() => {
    fetchMendekatiKadaluarsa();
    fetchStokSedikit();
    fetchFastMoving();
    fetchPenggunaanPalingBanyak();
    fetchPerbandinganTransaksiPerBulan();
    fetchForecast();
});

onActivated(() => {
    fetchMendekatiKadaluarsa();
    fetchStokSedikit();
    fetchFastMoving();
    fetchPenggunaanPalingBanyak();
    fetchPerbandinganTransaksiPerBulan();
});

</script>