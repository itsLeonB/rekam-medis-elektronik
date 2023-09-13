<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    rekamMedis: Object
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">Monggo dicoba</h2>

                        <p class="mt-1 text-sm text-gray-600">
                            Yang didisplay table <strong>resource_content</strong> ya bund
                        </p>

                        <table class="mt-4 min-w-full divide-y divide-gray-200 border rounded-sm">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-center">
                                        <span class="text-xs leading-4 font-medium text-gray-700 uppercase">Resource ID</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-center">
                                        <span class="text-xs leading-4 font-medium text-gray-700 uppercase">Nama Pasien</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-center">
                                        <span class="text-xs leading-4 font-medium text-gray-700 uppercase">Nomor Rekam Medis</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span class="text-xs leading-4 font-medium text-gray-700 uppercase">Konten</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                <tr v-for="re in rekamMedis" :key="re.res_id">
                                    <td class="px-4 py-2 whitespace-nowrap text-sm leading-5 text-gray-700">{{ re.res_id }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm leading-5 text-gray-700">{{ re.nama_pasien }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm leading-5 text-gray-700">{{ re.nomor_rekam_medis }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm leading-5 text-gray-700" v-html="re.res_text">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
