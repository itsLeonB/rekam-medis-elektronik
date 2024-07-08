<script setup>
import AuthenticatedLayout from './AuthenticatedLayout.vue';
import BackButton from '@/Components/BackButton.vue';

</script>

<template>
    <AuthenticatedLayout>
        <div class="mx-auto py-10 px-6 max-w-7xl">
            <div class="w-28 mb-7">
                <BackButton />
            </div>
            <slot />
        </div>
    </AuthenticatedLayout>
</template>