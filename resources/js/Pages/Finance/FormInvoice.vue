<template>
    <form @submit.prevent="submit">
        <div>
            <InputLabel>No. RM</InputLabel>
            <TextInput/>
        </div>
        <div>
            <InputLabel>Nama Pasien</InputLabel>
            <TextInput/>
        </div>
        <div>
            <InputLabel>Penanggung Jawab</InputLabel>
            <TextInput/>
        </div>
        <div>
            <InputLabel>Alamat</InputLabel>
            <TextInput/>
        </div>
        <div>
            <InputLabel>No. Telp/HP</InputLabel>
            <TextInput/>
        </div>
        <div>
            <InputLabel>Tanggal Masuk</InputLabel>
            <TextInput/>
        </div>
        <div>
            <InputLabel>Tanggal Keluar</InputLabel>
            <TextInput/>
        </div>
        <div>
            <InputLabel>Umur</InputLabel>
            <TextInput/>
        </div>
        <div>
            <InputLabel>Kelas</InputLabel>
            <TextInput/>
        </div>
        <div>
            <InputLabel>Dokter</InputLabel>
            <TextInput/>
        </div>
    </form>
</template>

<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutNav.vue';
</script>
