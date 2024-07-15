<template>
    <AuthenticatedLayout>
        <div
            class="bg-original-white-0 flex justify-between shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-0 md:px-10">
            <div class="md:py-8">
                <span class="inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" viewBox="0 0 24 24" fill="none">
                        <path
                            d="M4 8L6.1 10.8C6.37944 11.1726 6.74179 11.475 7.15836 11.6833C7.57493 11.8916 8.03426 12 8.5 12H20M10 6H14M12 4V8M14 18C14 18.5304 14.2107 19.0391 14.5858 19.4142C14.9609 19.7893 15.4696 20 16 20C16.5304 20 17.0391 19.7893 17.4142 19.4142C17.7893 19.0391 18 18.5304 18 18C18 17.4696 17.7893 16.9609 17.4142 16.5858C17.0391 16.2107 16.5304 16 16 16C15.4696 16 14.9609 16.2107 14.5858 16.5858C14.2107 16.9609 14 17.4696 14 18ZM6 18C6 18.5304 6.21071 19.0391 6.58579 19.4142C6.96086 19.7893 7.46957 20 8 20C8.53043 20 9.03914 19.7893 9.41421 19.4142C9.78929 19.0391 10 18.5304 10 18C10 17.4696 9.78929 16.9609 9.41421 16.5858C9.03914 16.2107 8.53043 16 8 16C7.46957 16 6.96086 16.2107 6.58579 16.5858C6.21071 16.9609 6 17.4696 6 18Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 12V14M12 14L9.5 16.5M12 14L14.5 16.5" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <h1 class="text-2xl font-bold text-neutral-black-300">Dashboard Keuangan</h1>
                </span>
                <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman untuk melihat kondisi keuangan,
                    seperti jumlah invoice, klaim, dan akun pasien
                </p>
                <div class="flex flex-col gap-4 sm:flex-row">
                    <Link v-if="['admin', 'keuangan'].includes($page.props.auth.user.roles[0].name)"
                        :href="route('finance.claim.index')" as="button"
                        class="inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Daftar Klaim
                    </Link>
                    <Link v-if="['admin', 'keuangan'].includes($page.props.auth.user.roles[0].name)"
                        :href="route('finance.invoice.index')" as="button"
                        class="inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Daftar Invoice
                    </Link>
                    <Link v-if="['admin', 'keuangan'].includes($page.props.auth.user.roles[0].name)"
                        :href="route('finance.catalogue')" as="button"
                        class="inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Daftar Harga
                    </Link>
                    <Link v-if="['admin', 'keuangan'].includes($page.props.auth.user.roles[0].name)"
                        :href="route('finance.account.index')" as="button"
                        class="inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Daftar Account Pasien
                    </Link>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-2">
            <div
                class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-2 md:py-8 md:pl-10 md:pr-14">
                <h2 class="font-bold text-xl">Akun Pasien Aktif</h2>
                <span class="text-lg font-semibold text-neutral-black-500">{{ activeAccounts }} Akun Pasien</span>
            </div>
            <div
                class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-2 md:py-8 md:pl-10 md:pr-14">
                <h2 class="font-bold text-xl">Jumlah Invoice Terbit</h2>
                <p>{{ issuedInvoice }} Invoice</p>
            </div>
            <div
                class="bg-original-white-0 overflow-hidden shadow rounded-xl md:rounded-2xl mb-8 p-2 md:py-8 md:pl-10 md:pr-14">
                <h2 class="font-bold text-xl">Jumlah Klaim Aktif</h2>
                <p>{{ activeClaims }} Klaim</p>
            </div>

            <div class="flex flex-col space-y-8 md:flex-row md:space-x-8 md:space-y-0 col-span-3">
                <div class="p-5 flex flex-col basis-3/5 h-fit bg-white overflow-hidden shadow sm:rounded-xl">
                    <h2 class="pl-4 pt-3 mb-2 text-base font-semibold text-neutral-black-500">Jumlah Invoice Per Bulan
                    </h2>
                    <VueApexCharts width="100%" height="320px" type="bar" :options="jumlahInvoicePerBulanOptions"
                        :series="jumlahInvoicePerBulan">
                    </VueApexCharts>
                </div>
                <div class="p-5 flex flex-col basis-2/5 h-fit bg-white overflow-hidden shadow sm:rounded-xl">
                    <h2 class="pl-4 pt-3 mb-2 text-base font-semibold text-neutral-black-500">Persebaran Invoice</h2>
                    <VueApexCharts width="100%" height="367px" type="donut" :options="persebaranCoverageOptions"
                        :series="persebaranCoverage">
                    </VueApexCharts>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutNav.vue';
import MainButton from '@/Components/MainButton.vue';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import VueApexCharts from 'vue3-apexcharts';
import axios from 'axios';

const users = ref([]);
const invoice = ref([]);
const account = ref([]);

const months = ref([]);
const ambCounts = ref([]);
const impCounts = ref([]);
const emerCounts = ref([]);
const issuedInvoice = ref(0);
const activeAccounts = ref(0);
const activeClaims = ref(0);
const jumlahInvoicePerBulanOptions = ref([]);
const jumlahInvoicePerBulan = ref([]);

const fontFamily = 'Poppins, Arial, sans-serif';

const hide = ref(false);

// Analytics
const getIssuedInvoice = async () => {
    try {
        const { data } = await axios.get(route('analytics.issued-invoice'));
        const originalData = data
        issuedInvoice.value = originalData;
    } catch (error) {
        console.error('Error fetching data:', error);
        issuedInvoice.value = 0;
    }
}

const getActiveAccounts = async () => {
    try {
        const { data } = await axios.get(route('analytics.active-accounts'));
        const originalData = data
        activeAccounts.value = originalData;
    } catch (error) {
        console.error('Error fetching data:', error);
        activeAccounts.value = 0;
    }
}

const getActiveClaims = async () => {
    try {
        const { data } = await axios.get(route('analytics.active-claims'));
        const originalData = data
        activeClaims.value = originalData;
    } catch (error) {
        console.error('Error fetching data:', error);
        activeClaims.value = 0;
    }
}


const fetchInvoicePerBulan = async () => {
    try {
        const response = await axios.get(route('analytics.invoice-per-month'));
        const data = response.data;

        const uniqueMonths = [...new Set(data.map(item => item.month))];
        const uniqueMonthsParsed = uniqueMonths.map(month => {
            const date = new Date(month + '-01');
            return new Intl.DateTimeFormat('id-ID', { month: 'short', year: '2-digit' }).format(date);
        });

        const ambCountArray = Array(uniqueMonths.length).fill(0);
        const impCountArray = Array(uniqueMonths.length).fill(0);
        const emerCountArray = Array(uniqueMonths.length).fill(0);

        data.forEach(item => {
            const monthIndex = uniqueMonths.indexOf(item.month);
            if (item.class === 'qris') ambCountArray[monthIndex] = item.count;
            else if (item.class === 'cash') impCountArray[monthIndex] = item.count;
            else if (item.class === 'bank') emerCountArray[monthIndex] = item.count;
        });

        months.value = uniqueMonthsParsed;
        ambCounts.value = ambCountArray;
        impCounts.value = impCountArray;
        emerCounts.value = emerCountArray;

        jumlahInvoicePerBulanOptions.value = {
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
                        fontFamily: 'Arial, sans-serif',
                        fontWeight: 400,
                    },
                },
                title: {
                    text: 'Periode',
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Arial, sans-serif',
                        fontWeight: 600,
                        cssClass: 'apexcharts-xaxis-title',
                    }
                },
            },
            yaxis: {
                title: {
                    text: 'Jumlah Invoice',
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Arial, sans-serif',
                        fontWeight: 600,
                        cssClass: 'apexcharts-xaxis-title',
                    }
                },
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                fontFamily: 'Arial, sans-serif',
                markers: {
                    width: 12,
                    height: 12,
                    radius: 12,
                },
            },
            tooltip: {
                style: {
                    fontFamily: 'Arial, sans-serif',
                }
            },
        };

        jumlahInvoicePerBulan.value = [
            {
                name: 'QRIS',
                data: ambCounts.value
            },
            {
                name: 'Cash',
                data: impCounts.value
            },
            {
                name: 'Bank Transfer',
                data: emerCounts.value
            }
        ];
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};
const persebaranCoverage = ref([]);
const persebaranCoverageOptions = ref([]);

const fetchPersebaranCoverage = async () => {
    try {
        const response = await axios.get(route('analytics.sebaran-coverage'));
        const data = response.data;

        console.log(data);
        const urutan = covClass.value;

        persebaranCoverage.value = Array.from(urutan.map(group => {
            const matchingItem = data.find(item => item.id === group);
            return matchingItem ? matchingItem.count : 0;
        }));

        persebaranCoverageOptions.value = {
            chart: {
                type: "donut"
            },
            colors: ["#6f52ed", "#f6896d", "#589ec5", "#f2e35b", "#58c5a5", "#f43f5e"],
            plotOptions: {
                pie: {
                    donut: {
                        size: "60%",
                    },
                },
            },
            labels: urutan,
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

const covClass = ref([])

const getCovClass = async () => {
    const { data } = await axios.get(route('terminologi.cov-type'));
    const originalData = data.data;
    originalData.map(item => {
        covClass.value.push(item.code)
    });
    console.log(covClass)
}

onMounted(() => {
    getIssuedInvoice();
    getActiveAccounts();
    getActiveClaims();
    fetchInvoicePerBulan();
    getCovClass();
    fetchPersebaranCoverage();
}
);

</script>
