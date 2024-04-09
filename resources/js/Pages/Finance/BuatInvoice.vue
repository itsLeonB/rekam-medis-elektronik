<template>
    <AuthenticatedLayout>
        <Modal :show="creationSuccessModal">
            <div class="p-6">
                <h2 class="text-lg text-center font-medium text-gray-900">
                    Data kunjungan telah berhasil dibuat. <br> Kembali ke halaman Gawat Darurat.
                </h2>
                <div class="mt-6 flex justify-end">
                    <Link :href="route('gawatdarurat')"
                        class="mx-auto mb-3 w-fit block justify-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Kembali </Link>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>
