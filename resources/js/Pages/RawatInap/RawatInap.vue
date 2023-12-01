<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutNav.vue';

defineProps({
    rekamMedis: Object
});
</script>

<template>
    <AuthenticatedLayout>
        <div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900">Halaman Rawat Inap</h2>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
