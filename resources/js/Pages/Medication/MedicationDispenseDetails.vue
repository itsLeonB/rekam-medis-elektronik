<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Medication Request - </title>
        </template>

        <div class="p-8 bg-original-white-0 overflow-hidden shadow sm:rounded-2xl mb-8">
            <div class="flex justify-between">
                <h1 class="mb-8 px-5 pt-3 text-2xl font-bold text-neutral-black-300">Detail Peresepan Obat</h1> 
            </div>
            <!-- <Modal :show="confirmingUserDeletion" @close="closeModal">
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
            </Modal> -->
            <div class="relative overflow-x-auto mb-5">
                <table class="w-full text-base text-left text-neutral-grey-200 ">
                    <tbody>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/4">
                                Peminta resep
                            </th>
                            <td class="px-6 py-4 w-3/4">
                                {{ medication.requester }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/4">
                                Nama Obat
                            </th>
                            <td class="px-6 py-4 w-3/4">
                                {{ medication.medication }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/4">
                                Pasien
                            </th>
                            <td class="px-6 py-4 w-3/4">
                                {{ medication.patient }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/4">
                                Valid date
                            </th>
                            <td class="px-6 py-4 w-3/4">
                                {{ medication.validStart }} - {{ medication.validEnd }}
                            </td>
                        </tr>
                        <tr class="bg-original-white-0">
                            <th scope="row" class="px-6 py-4 font-normal whitespace-nowrap w-1/4">
                                Jumlah
                            </th>
                            <td class="px-6 py-4 w-3/4">
                                {{ medication.quantity }}&nbsp;{{ medication.uom }}
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
            <div class="buttons-submit">
                <Link :href="route('usermanagement.tambah')" as="button"
                    class="mr-3 inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                Setujui
                </Link>
                <Link :href="route('usermanagement.tambah')" as="button"
                    class="mr-3 inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm orange-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg">
                Tolak
                </Link>
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

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
};

const deleteUser = (user_id) => {
    axios.delete(route('users.destroy' + `/${user_id}`), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal(),
                route('users.index');
        }
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
};

const props = defineProps({
    medication_dispense_id: {
        type: String,
    },
});

const medication_id = props.medication_dispense_id;

const medication = ref([]);

const fetchMedication = async () => {
    const { data } = await axios.get(route('medicationDispense.show', { 'medicationReq_id': medication_id }));

    medication.value = data;
};

const submit = async () => {
   const currentTime = new Date().toISOString().replace('Z', '+00:00').replace(/\.\d{3}/, '');
   const submitResource = ref({
      "resourceType": "MedicationDispense",
      "identifier": [],
      "active": true,
      "name": [
         {
            "use": "official",
            "text": resourceForm.value.nama
         }
      ],
      "telecom": [],
      "gender": resourceForm.value.gender,
      "birthDate": resourceForm.value.tanggallahir.toISOString().split('T')[0],
      "deceasedBoolean": false,
      "address": [
         {
            "use": "home",
            "line": [resourceForm.value.alamat],
            "city": resourceForm.value.kota.nama_kabko,
            "postalCode": resourceForm.value.kodepos,
            "country": "ID",
            "extension": [
               {
                  "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode",
                  "extension": [
                     {
                        "url": "province",
                        "valueCode": resourceForm.value.provinsi.kode_provinsi
                     },
                     {
                        "url": "city",
                        "valueCode": resourceForm.value.kota.kode_kabko
                     },
                     {
                        "url": "district",
                        "valueCode": resourceForm.value.kecamatan.kode_kecamatan
                     },
                     {
                        "url": "village",
                        "valueCode": resourceForm.value.keluarahan.kode_kelurahan
                     },
                     {
                        "url": "rt",
                        "valueCode": resourceForm.value.rt
                     },
                     {
                        "url": "rw",
                        "valueCode": resourceForm.value.rw
                     }
                  ]
               }
            ]
         }
      ],
      "maritalStatus": {
         "coding": [
            {
               "system": resourceForm.value.statusnikah.system,
               "code": resourceForm.value.statusnikah.code,
               "display": resourceForm.value.statusnikah.display
            }
         ],
         "text": resourceForm.value.statusnikahtext
      },
      "multipleBirthInteger": resourceForm.value.kelahirankembar,
      "communication": [
         {
            "language": {
               "coding": [
                  {
                     "system": resourceForm.value.bahasasatu.system,
                     "code": resourceForm.value.bahasasatu.code,
                     "display": resourceForm.value.bahasasatu.display
                  }
               ],
               "text": resourceForm.value.bahasasatu.definition
            },
            "preferred": resourceForm.value.bahasasatupreferred
         }
      ],
      "contact": [],
      "extension": [
         {
            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace",
            "valueAddress": {
               "city": "",
               "country": resourceForm.value.negaralahir.code
            }
         },
         {
            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/citizenshipStatus",
            "valueCode": resourceForm.value.kewarganegaraan
         }
      ]
   });

   if (resourceForm.value.nik !== '' && barulahir.value === false) {
      submitResource.value.identifier.push({
         "use": "official",
         "system": "https://fhir.kemkes.go.id/id/nik",
         "value": resourceForm.value.nik
      });
   } else if (resourceForm.value.nik !== '' && barulahir.value === true) {
      submitResource.value.identifier.push({
         "use": "official",
         "system": "https://fhir.kemkes.go.id/id/nik-ibu",
         "value": resourceForm.value.nik
      });
   };

   if (resourceForm.value.paspor !== '') {
      submitResource.value.identifier.push({
         "use": "official",
         "system": "https://fhir.kemkes.go.id/id/paspor",
         "value": resourceForm.value.paspor
      });
   };

   if (resourceForm.value.kk !== '') {
      submitResource.value.identifier.push({
         "use": "official",
         "system": "https://fhir.kemkes.go.id/id/kk",
         "value": resourceForm.value.kk
      });
   };

   if (resourceForm.value.nohp !== '') {
      submitResource.value.telecom.push({
         "system": "phone",
         "value": resourceForm.value.nohp,
         "use": "mobile"
      });
   };

   if (resourceForm.value.notelprumah !== '') {
      submitResource.value.telecom.push({
         "system": "phone",
         "value": resourceForm.value.notelprumah,
         "use": "home"
      });
   };

   if (resourceForm.value.email !== '') {
      submitResource.value.telecom.push({
         "system": "email",
         "value": resourceForm.value.email,
         "use": "home"
      });
   };

   if (resourceForm.value.emerhubungansatu !== null && resourceForm.value.emernamasatu !== '' && resourceForm.value.emertelphpsatu !== '') {
      submitResource.value.contact.push({
         "relationship": [
            {
               "coding": [
                  {
                     "system": resourceForm.value.emerhubungansatu.system,
                     "code": resourceForm.value.emerhubungansatu.code,
                     "display": resourceForm.value.emerhubungansatu.display
                  }
               ]
            }
         ],
         "name": {
            "use": "official",
            "text": resourceForm.value.emernamasatu
         },
         "telecom": [
            {
               "system": "phone",
               "value": resourceForm.value.emertelphpsatu,
               "use": "mobile"
            }
         ]
      })
   };

   if (resourceForm.value.emerhubungandua !== null && resourceForm.value.emernamadua !== '' && resourceForm.value.emertelphpdua !== '') {
      submitResource.value.contact.push({
         "relationship": [
            {
               "coding": [
                  {
                     "system": resourceForm.value.emerhubungandua.system,
                     "code": resourceForm.value.emerhubungandua.code,
                     "display": resourceForm.value.emerhubungandua.display
                  }
               ]
            }
         ],
         "name": {
            "use": "official",
            "text": resourceForm.value.emernamadua
         },
         "telecom": [
            {
               "system": "phone",
               "value": resourceForm.value.emertelphpdua,
               "use": "mobile"
            }
         ]
      })
   };

   if (resourceForm.value.bahasadua !== null) {
      submitResource.value.language.push(
         {
            "language": {
               "coding": [
                  {
                     "system": resourceForm.value.bahasadua.system,
                     "code": resourceForm.value.bahasadua.code,
                     "display": resourceForm.value.bahasadua.display
                  }
               ],
               "text": resourceForm.value.bahasadua.definition
            },
            "preferred": resourceForm.value.bahasaduapreferred
         }
      )
   };

   if (resourceForm.value.negaralahir.code === "ID") {
      submitResource.value.extension[0].valueAddress.city = resourceForm.value.kotalahir.nama_kabko
   } else {
      submitResource.value.extension[0].valueAddress.city = resourceForm.value.kotalahir
   };

   if (submitResource.value.contact == [] || resourceForm.value.emerhubungandua === null ||
      resourceForm.value.emernamadua === '' || resourceForm.value.emertelphpdua === '' ||
      resourceForm.value.emerhubungansatu === null ||
      resourceForm.value.emernamasatu === '' || resourceForm.value.emertelphpsatu === '') {
      delete submitResource.value.contact;
   };

   hasil.value = submitResource;

   // try {
   //    const response = axios.post(route('integration.store', { res_type: "Encounter" }), submitResource);
   // } catch (error) {
   //    console.error(error.response.data);
   // }
};

onMounted(() => {
    fetchMedication();
    console.log(props);
}
);

</script>

<style>
.buttons-submit {
    display: flex;
    justify-content: flex-end;
}
</style>