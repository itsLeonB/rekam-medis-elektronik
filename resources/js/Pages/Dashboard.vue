<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    resourceContents: Object
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
                                        <span class="text-xs leading-4 font-medium text-gray-700 uppercase">id</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-center">
                                        <span class="text-xs leading-4 font-medium text-gray-700 uppercase">res_id</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-center">
                                        <span class="text-xs leading-4 font-medium text-gray-700 uppercase">res_ver</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span class="text-xs leading-4 font-medium text-gray-700 uppercase">res_text[text][div]</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                <tr v-for="re in resourceContents" :key="re.id">
                                    <td class="px-4 py-2 whitespace-nowrap text-sm leading-5 text-gray-700">{{ re.id }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm leading-5 text-gray-700">{{ re.res_id }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm leading-5 text-gray-700">{{ re.res_ver }}
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
