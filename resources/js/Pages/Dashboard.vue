<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutNav.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import VueApexCharts from 'vue3-apexcharts';

const months = ref([]);
const ambCounts = ref([]);
const impCounts = ref([]);
const emerCounts = ref([]);
const jumlahPasienPerBulanOptions = ref([]);
const jumlahPasienPerBulan = ref([]);

const fontFamily = 'Poppins, Arial, sans-serif';

const fetchPasienPerBulan = async () => {
  try {
    const response = await axios.get(route('analytics.pasien-per-bulan'));
    const data = response.data.data;

    const uniqueMonths = [...new Set(data.map(item => item.month))];
    const uniqueMonthsParsed = [...new Set(data.map(item => {
        const date = new Date(item.month + '-01');
        const formattedMonth = new Intl.DateTimeFormat('id-ID', { month: 'short', year: '2-digit' }).format(date);
        return formattedMonth;
    }))];
    const ambCountArray = Array(uniqueMonths.length).fill(0);
    const impCountArray = Array(uniqueMonths.length).fill(0);
    const emerCountArray = Array(uniqueMonths.length).fill(0);

    data.forEach(item => {
      const monthIndex = uniqueMonths.indexOf(item.month);
      if (item.class === 'AMB') ambCountArray[monthIndex] = item.count;
      else if (item.class === 'IMP') impCountArray[monthIndex] = item.count;
      else if (item.class === 'EMER') emerCountArray[monthIndex] = item.count;
    });

    months.value = uniqueMonthsParsed;
    ambCounts.value = ambCountArray;
    impCounts.value = impCountArray;
    emerCounts.value = emerCountArray;

    jumlahPasienPerBulanOptions.value = {
        chart: {
            type: 'bar',
            stacked: true,
        },
        colors: ['#6f52ed', '#f6896d', '#58c5a5'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
            },
        },
        xaxis: {
            categories: months.value,
            labels: {
                rotate: -90,
                rotateAlways: true,
                minHeight: 8,
                style: {
                    fontSize: '12px',
                    fontFamily: fontFamily,
                    fontWeight: 400,
                },
            },
            title: {
                text: 'Periode',
                style: {
                    fontSize: '12px',
                    fontFamily: fontFamily,
                    fontWeight: 600,
                    cssClass: 'apexcharts-xaxis-title',
                }
            },
        },
        yaxis: {
            title: {
                text: 'Jumlah Pasien',
                style: {
                    fontSize: '12px',
                    fontFamily: fontFamily,
                    fontWeight: 600,
                    cssClass: 'apexcharts-xaxis-title',
                }
            },
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            fontFamily: fontFamily,
            markers: {
                width: 12,
                height: 12,
                radius: 12,
            },
        },
        tooltip: {
            style: {
                fontFamily: fontFamily,
            }
        },
    };

    jumlahPasienPerBulan.value = [
        {
            name: 'Rawat Jalan',
            data: ambCounts.value
        },
        {
            name: 'Rawat Inap',
            data: impCounts.value
        },
        {
            name: 'IGD',
            data: emerCounts.value
        }
    ];
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

const persebaranPasien = ref([]);
const persebaranPasienOptions = ref([]);

const fetchPersebaranPasien = async () => {
  try {
    const response = await axios.get(route('analytics.sebaran-usia-pasien'));
    const data = response.data.data;

    const urutan = ["balita", "kanak", "remaja", "dewasa", "lansia", "manula"];

    persebaranPasien.value = Array.from(urutan.map(ageGroup => {
        const matchingItem = data.find(item => item.age_group === ageGroup);
        return matchingItem ? matchingItem.count : 0;
    }));

    persebaranPasienOptions.value = {
        chart: {
            type: "donut",
        },
        colors: ["#6f52ed", "#f6896d", "#589ec5", "#f2e35b", "#58c5a5", "#f43f5e"],
        plotOptions: {
            pie: {
            donut: {
                size: "60%",
            },
            },
        },
        labels: ["Balita (0 - 5 Tahun)", "Kanak - Kanak (6 - 12 Tahun)", "Remaja (12 - 25 Tahun)", "Dewasa (26 - 45 Tahun)", 
        "Lansia (46 - 65 Tahun)", "Manula (> 65 Tahun)"],
        legend: {
            position: "bottom",
            horizontalAlign: "center",
            fontFamily: fontFamily,
            markers: {
            width: 12,
            height: 12,
            radius: 12,
            },
        },
        tooltip: {
            style: {
            fontFamily: fontFamily,
            },
        },
    };
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

//Pasien Aktif Hari Ini
const pasienAkitfHariIni = ref(0);
const fetchPasienAkitfHariIni = async () => {
    try {
        const response = await axios.get(route('analytics.pasien-hari-ini'));
        pasienAkitfHariIni.value = response.data.count;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

//Pasien Baru Bulan Ini
const pasienBaruBulanIni = ref(0);
const fetchPasienBaruBulanIni = async () => {
    try {
        const response = await axios.get(route('analytics.pasien-baru-bulan-ini'));
        pasienBaruBulanIni.value = response.data.count;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

//Total Pasien Terdaftar
const totalPasienTerdaftar = ref(0);
const fetchTotalPasienTerdaftar = async () => {
    try {
        const response = await axios.get(route('analytics.jumlah-pasien'));
        totalPasienTerdaftar.value = response.data.count;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

onMounted(() => {
    fetchPasienAkitfHariIni();
    fetchPasienBaruBulanIni();
    fetchTotalPasienTerdaftar();
    fetchPasienPerBulan();
    fetchPersebaranPasien();
});

</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-7xl pb-10 mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-row justify-between bg-white overflow-hidden shadow sm:rounded-2xl mb-8">
                <div class="pl-8 my-auto">
                    <h2 class="text-3xl font-bold text-secondhand-orange-300 mb-1">Hai, {{ $page.props.auth.user.name
                    }}!</h2>
                    <!-- <p class="text-neutral-grey-200 font-normal text-2xl">Selamat datang di halaman utama</p> -->
                </div>
                <div class="mr-8">
                    <img src="storage/images/welcome-onboard.png" class="h-36 mx-auto" alt="">
                </div>
            </div>
            <div class="flex space-x-8 h-28 mb-8">
                <div class="pl-10 flex flex-1 items-center h-full bg-white overflow-hidden shadow sm:rounded-xl">
                    <div class="mr-5 h-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 74 74" fill="none">
                            <circle cx="37" cy="37" r="37" fill="#ECFBF4" />
                            <path
                                d="M26.9092 47.091C26.9092 43.3069 31.7942 43.3069 34.2368 40.7842C35.458 39.5228 31.7942 39.5228 31.7942 33.216C31.7942 29.0119 33.4222 26.9092 36.6793 26.9092C39.9364 26.9092 41.5643 29.0119 41.5643 33.216C41.5643 39.5228 37.9005 39.5228 39.1218 40.7842C41.5643 43.3069 46.4494 43.3069 46.4494 47.091"
                                stroke="#7BDAB8" stroke-width="2.24242" stroke-linecap="square" />
                        </svg>
                    </div>
                    <div class="h-fit">
                        <h2 class="text-base font-normal text-neutral-grey-100">Pasien Aktif Hari Ini</h2>
                        <span class="text-xl font-semibold text-neutral-black-500">{{ pasienAkitfHariIni }}
                            Pasien</span>
                    </div>
                </div>
                <div class="pl-10 flex flex-1 items-center h-full bg-white overflow-hidden shadow sm:rounded-xl">
                    <div class="mr-5 h-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 74 74" fill="none">
                            <circle cx="37" cy="37" r="37" fill="#ECFBF4" />
                            <path
                                d="M26.9092 47.091C26.9092 43.3069 31.7942 43.3069 34.2368 40.7842C35.458 39.5228 31.7942 39.5228 31.7942 33.216C31.7942 29.0119 33.4222 26.9092 36.6793 26.9092C39.9364 26.9092 41.5643 29.0119 41.5643 33.216C41.5643 39.5228 37.9005 39.5228 39.1218 40.7842C41.5643 43.3069 46.4494 43.3069 46.4494 47.091"
                                stroke="#7BDAB8" stroke-width="2.24242" stroke-linecap="square" />
                            <path d="M46 19L46 27" stroke="#7BDAB8" stroke-width="1.33333" stroke-linecap="round" />
                            <path d="M50 23L42 23" stroke="#7BDAB8" stroke-width="1.33333" stroke-linecap="round" />
                        </svg>
                    </div>
                    <div class="h-fit">
                        <h2 class="text-base font-normal text-neutral-grey-100">Pasien Baru Bulan Ini</h2>
                        <span class="text-xl font-semibold text-neutral-black-500">{{ pasienBaruBulanIni }}
                            Pasien</span>
                    </div>
                </div>
                <div class="pl-10 flex flex-1 items-center h-full bg-white overflow-hidden shadow sm:rounded-xl">
                    <div class="mr-5 h-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 74 74" fill="none">
                            <circle cx="37" cy="37" r="37" fill="#ECFBF4" />
                            <path
                                d="M26.9092 47.091C26.9092 43.3069 31.7942 43.3069 34.2368 40.7842C35.458 39.5228 31.7942 39.5228 31.7942 33.216C31.7942 29.0119 33.4222 26.9092 36.6793 26.9092C39.9364 26.9092 41.5643 29.0119 41.5643 33.216C41.5643 39.5228 37.9005 39.5228 39.1218 40.7842C41.5643 43.3069 46.4494 43.3069 46.4494 47.091"
                                stroke="#7BDAB8" stroke-width="2.24242" stroke-linecap="square" />
                            <path
                                d="M40.3634 41.4847C39.8028 40.9241 43.0057 41.0642 45.4482 38.5415C46.6694 37.2801 43.0057 37.2801 43.0057 30.9733C43.0057 26.7692 44.6336 24.6665 47.8907 24.6665C51.1478 24.6665 52.7758 26.7692 52.7758 30.9733C52.7758 37.2801 49.112 37.2801 50.3332 38.5415C52.7758 41.0642 57.6608 41.0642 57.6608 44.8483"
                                stroke="#7BDAB8" stroke-opacity="0.5" stroke-width="2.24242" stroke-linecap="square" />
                            <path
                                d="M15.6973 44.8483C15.6973 41.0642 20.5823 41.0642 23.0248 38.5415C24.2461 37.2801 20.5823 37.2801 20.5823 30.9733C20.5823 26.7692 22.2103 24.6665 25.4674 24.6665C28.7245 24.6665 30.3524 26.7692 30.3524 30.9733C30.3524 37.2801 26.6886 37.2801 27.9099 38.5415C30.3524 41.0642 32.5154 39.8029 32.5154 41.4847"
                                stroke="#7BDAB8" stroke-opacity="0.5" stroke-width="2.24242" stroke-linecap="square" />
                        </svg>
                    </div>
                    <div class="h-fit">
                        <h2 class="text-base font-normal text-neutral-grey-100">Total Pasien Terdaftar</h2>
                        <span class="text-xl font-semibold text-neutral-black-500">{{ totalPasienTerdaftar }}
                            Pasien</span>
                    </div>
                </div>
            </div>
            <div class="flex space-x-8">
                <div class="p-5 flex flex-col basis-3/5 h-fit bg-white overflow-hidden shadow sm:rounded-xl">
                    <h2 class="pl-4 pt-3 mb-2 text-base font-semibold text-neutral-black-500">Jumlah Pasien Per Bulan</h2>
                    <VueApexCharts width="100%" height="320px" type="bar" :options="jumlahPasienPerBulanOptions" :series="jumlahPasienPerBulan">
                    </VueApexCharts>
                </div>
                <div class="p-5 flex flex-col basis-2/5 h-fit bg-white overflow-hidden shadow sm:rounded-xl">
                    <h2 class="pl-4 pt-3 mb-2 text-base font-semibold text-neutral-black-500">Persebaran Pasien</h2>
                    <VueApexCharts width="100%" height="367px" type="donut" :options="persebaranPasienOptions" :series="persebaranPasien">
                    </VueApexCharts>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>