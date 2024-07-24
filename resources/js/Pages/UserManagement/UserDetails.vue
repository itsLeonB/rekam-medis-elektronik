<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>User Details - </title>
        </template>

        <div class="p-8 bg-original-white-0 overflow-hidden shadow sm:rounded-2xl mb-8">
            <div class="flex justify-between">
                <h1 class="mb-8 px-5 pt-3 text-2xl font-bold text-neutral-black-300">Detail User</h1>
                <div>
                    <Link :href="route('usermanagement.edit', {'user_id': props.user_id})" as="button"
                        class="mr-3 inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                    Edit User
                    </Link>
                    <MainButton @click="confirmUserDeletion" class="orange-button text-original-white-0">
                        Hapus User
                    </MainButton>
                </div>
            </div>
            <Modal :show="confirmingUserDeletion" @close="closeModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Apakah yakin akan menghapus user ini?
                    </h2>
                    <div class="mt-6 flex justify-end">
                        <MainButton @click="closeModal" class="teal-button text-original-white-0"> Cancel </MainButton>

                        <MainButton class="ml-3 orange-button text-original-white-0" @click="deleteUser(user_id)">
                            Hapus User
                        </MainButton>
                    </div>
                </div>
            </Modal>
            <Modal :show="deletionSuccessModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        User telah berhasil dihapus. Kembali ke halaman User Management.
                    </h2>
                    <div class="mt-6 flex justify-end">
                        <Link :href="route('usermanagement')" class="mx-auto mb-3 w-fit block justify-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg"> Kembali </Link>
                    </div>
                </div>
            </Modal>
            <div class="relative overflow-x-auto mb-5">
                <table class="w-full text-base text-left text-neutral-grey-200 ">
                    <tbody>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                Nama
                            </th>
                            <td class="px-6 py-4 w-2/3">
                                {{ user.name }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                Email
                            </th>
                            <td class="px-6 py-4 w-2/3">
                                {{ user.email }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                Verifikasi Email
                            </th>
                            <td class="px-6 py-4 w-2/3">
                                {{ formatTimestamp(user.email_verified_at) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="py-8 bg-original-white-0 overflow-hidden shadow sm:rounded-2xl mb-8 pl-10 pr-14">
            <h1 class="mb-8 px-5 pt-3 text-2xl font-bold text-neutral-black-300">Detail Practitioner</h1>
            <div class="relative overflow-x-auto mb-5">
                <table class="w-full text-base text-left text-neutral-grey-200 ">
                    <tbody>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                ID Satusehat
                            </th>
                            <td class="px-6 py-4 w-2/3">
                                {{ practitioner.id }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/3">
                                Gender
                            </th>
                            <td class="px-6 py-4 w-2/3">
                                {{ practitioner.gender }}
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import MainButton from '@/Components/MainButton.vue';
import Modal from '@/Components/Modal.vue';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const confirmingUserDeletion = ref(false);
const deletionSuccessModal = ref(false);

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
};

const deleteUser = () => {
    axios.delete(route('users.destroy', {'user_id': props.user_id}))
        .then(response => {
            closeModal();
            deletionSuccessModal.value = true;
        })
        .catch(error => {
            console.error('Error deleting user:', error);
        });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
};

const props = defineProps({
    user_id: {
        type: String,
    },
});

const user = ref({});
const practitioner = ref({});

const fetchUser = async () => {
    const response = await axios.get(route('users.show', {'user_id': props.user_id}));
    user.value = response.data.user;
    if (response.data.practitioner !== null) {
        practitioner.value = response.data.practitioner;
    }
};

const formatTimestamp = (timestamp) => {
    const date = new Date(timestamp);
    date.setHours(date.getHours() + 7);
    const options = { day: '2-digit', month: 'long', year: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', timeZone: 'UTC' };
    return date.toLocaleDateString('id-ID', options);
};

onMounted(() => {
    fetchUser();
}
);

</script>