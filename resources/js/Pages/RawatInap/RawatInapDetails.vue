<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Encounter - </title>
        </template>
        <div class="xl:hidden ml-10 mt-10 w-28 mb-2">
            <BackButton />
        </div>
        <div class="h-screen flex flex-row pt-4 xl:pt-7">
            <aside class="hidden overflow-auto xl:h-[90%] xl:pb-10 xl:fixed xl:flex xl:flex-col xl:w-72">
                <div class="px-8 w-28 mb-2">
                    <BackButton />
                </div>
                <!-- Navigation Links -->
                <ul class="space-y-2">
                    <li>
                        <NavButton @click="formSection = 1" :active="formSection === 1">
                            <span class="pt-1 mr-1">1.</span>
                            <span class="pt-1">Identitas Pasien</span>
                        </NavButton>
                    </li>
                    <li>
                        <NavButton @click="formSection = 2" :active="formSection === 2">
                            <span class="pt-1 mr-1">2.</span>
                            <span class="pt-1">Formulir Rawat Inap</span>
                        </NavButton>
                    </li>
                    <li>
                        <NavButton @click="formSection = 3" :active="formSection === 3">
                            <span class="pt-1 mr-1">3.</span>
                            <span class="pt-1">Diagnosis</span>
                        </NavButton>
                    </li>
                    <li>
                        <NavButton @click="formSection = 4" :active="formSection === 4">
                            <span class="pt-1 mr-1">4.</span>
                            <span class="pt-1">Tindakan</span>
                        </NavButton>
                    </li>
                    <li>
                        <NavButton @click="formSection = 5" :active="formSection === 5">
                            <span class="pt-1 mr-1">5.</span>
                            <span class="pt-1">Asesmen Harian</span>
                        </NavButton>
                    </li>
                    <li>
                        <NavButton @click="formSection = 6" :active="formSection === 6">
                            <span class="pt-1 mr-1">6.</span>
                            <span class="pt-1">Tatalaksana</span>
                        </NavButton>
                    </li>
                    <li>
                        <NavButton @click="formSection = 7" :active="formSection === 7">
                            <span class="pt-1 mr-1">7.</span>
                            <span class="pt-1">Prognosis</span>
                        </NavButton>
                    </li>
                    <li>
                        <NavButton @click="formSection = 8" :active="formSection === 8">
                            <span class="pt-1 mr-1">8.</span>
                            <span class="pt-1">Rencana Tindak Lanjut</span>
                        </NavButton>
                    </li>
                    <li>
                        <NavButton @click="formSection = 9" :active="formSection === 9">
                            <span class="pt-1 mr-1">9.</span>
                            <span class="pt-1">Kondisi & Cara Keluar</span>
                        </NavButton>
                    </li>
                </ul>
            </aside>
            <div v-show="formSection === 1" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-secondhand-orange-300">Identitas Pasien</h1>
                    <div class="flex flex-col lg:flex-row">
                        <!-- Kiri -->
                        <div class="w-full lg:w-2/3 py-2 lg:pr-7 lg:pb-0">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300">a. Identitas Pasien</h2>
                            <div class="space-y-4">
                                <IdentitasPasien :encounter="encounter" />
                            </div>
                        </div>
                        <!-- Kanan -->
                        <div class="w-full lg:w-1/3 py-2 lg:pl-7">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300">b. Status Kunjungan</h2>
                            <div class="space-y-4">
                                <StatusKunjungan :encounter="encounter" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="formSection === 2" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-secondhand-orange-300">Formulir Rawat Inap</h1>
                    <div class="flex flex-col lg:flex-row">
                        <!-- Kiri -->
                        <div class="w-full lg:w-1/2 py-2 lg:pr-7 lg:pb-0">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300">a. Anamnesis</h2>
                            <div class="space-y-4">
                                <KeluhanUtama :encounter_reference="encounter_reference" :subject_reference="subject_ref"/>
                                <RiwayatPenyakitPribadi :encounter_reference="encounter_reference" :subject_reference="subject_ref" />
                                <RiwayatPenyakitKeluarga :encounter_reference="encounter_reference" :subject_reference="subject_ref"/>
                                <RiwayatAlergi :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"/>
                                <RiwayatPengobatanStatement :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref" />
                            </div>
                            <h2 class="text-xl font-semibold text-secondhand-orange-300 mt-5">b. Pemeriksaan Psikologis</h2>
                            <div class="space-y-4">
                                <PemeriksaanPsikologis :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref" />
                            </div>
                        </div>
                        <!-- Kanan -->
                        <div class="w-full lg:w-1/2 py-2 lg:pl-7">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300">c. Pemeriksaan Fisik</h2>
                            <div class="space-y-4">
                                <TingkatKesadaran :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref" />
                            </div>
                            <div class="space-y-4">
                                <Nadi ref="nadi" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref" />
                                <Pernafasan ref="pernafasan" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref" />
                                <Sistole ref="sistol" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref" />
                                <Diastole ref="diastole" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref" />
                                <Suhu ref="suhu" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref" />
                                <div class="w-full flex justify-end mt-2 mr-3">
                                    <MainButtonSmall @click="vitalSubmit" class="teal-button text-original-white-0">Submit
                                    </MainButtonSmall>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <Kepala ref="kepala" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Mata ref="mata" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Telinga ref="telinga" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Hidung ref="hidung" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Rambut ref="rambut" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Bibir ref="bibir" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Gigigeligi ref="gigigeligi" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Lidah ref="lidah" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Langit ref="lagit" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Leher ref="leher" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Tenggorokan ref="tenggorokan" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Tenggorokan ref="tenggorokan" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Tonsil ref="tonsil" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Dada ref="dada" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Payudara ref="payudara" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Punggung ref="punggung" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Perut ref="perut" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Genital ref="genital" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Anus ref="anus" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Lenganatas ref="lenganatas" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Lenganbawah ref="lenganbawah" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Jaritangan ref="jaritangan" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Kukutangan ref="kukutangan" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Persendiantangan ref="persendiantangan" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Tungkaiatas ref="tungkaiatas" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Tungkaibawah ref="tungkaibawah" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Jarikaki ref="jarikaki" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Kukukaki ref="kukukaki" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <Persendiankaki ref="persendiankaki" :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref"  />
                                <div class="w-full flex justify-end mt-2 mr-3">
                                    <MainButtonSmall @click="fisikSubmit" class="teal-button text-original-white-0">Submit
                                    </MainButtonSmall>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="formSection === 3" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-secondhand-orange-300">Diagnosis</h1>
                    <div class="flex flex-col lg:flex-row">
                        <!-- Kiri -->
                        <div class="w-full lg:w-1/2 py-2 lg:pr-7 lg:pb-0">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300">a. Diagnosis Masuk</h2>
                            <div class="space-y-4">
                                <DiagnosisMasuk :encounter_reference="encounter_reference" :subject_reference="subject_ref" />
                            </div>
                        </div>
                        <!-- Kanan -->
                        <div class="w-full lg:w-1/2 py-2 lg:pl-7">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300">b. Diagnosis Keluar</h2>
                            <div class="space-y-4">
                                <DiagnosisAkhirPrimer :encounter_reference="encounter_reference" :subject_reference="subject_ref" />
                                <DiagnosisAkhirSekunder :encounter_reference="encounter_reference" :subject_reference="subject_ref" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="formSection === 4" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-secondhand-orange-300">Tindakan/Prosedur</h1>
                    <div class="flex flex-col lg:flex-row">
                        <!-- Kiri -->
                        <div class="w-full lg:w-1/2 py-2 lg:pr-7 lg:pb-0">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300">a. Tindakan</h2>
                            <div class="space-y-4">
                                <Tindakan :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref" />
                            </div>
                        </div>
                        <!-- Kanan -->
                        <div class="w-full lg:w-1/2 py-2 lg:pl-7">
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="formSection === 5" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-secondhand-orange-300">Asesmen Harian</h1>
                    <div class="flex flex-col lg:flex-row">
                        <!-- Kiri -->
                        <div class="w-full lg:w-1/2 py-2 lg:pr-7 lg:pb-0">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300">a. Asesmen</h2>
                            <div class="space-y-4">
                                <AsesmenHarian :encounter_reference="encounter_reference" :subject_reference="subject_ref" />
                            </div>
                        </div>
                        <!-- Kanan -->
                        <div class="w-full lg:w-1/2 py-2 lg:pl-7">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300">b. Riwayat Asesmen</h2>
                            <div class="space-y-4">
                                <RiwayatAsesmenHarian :encounter_satusehat_id="encounter_satusehat_id"  />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="formSection === 6" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-secondhand-orange-300">Tatalaksana</h1>
                    <div class="flex flex-col lg:flex-row">
                        <!-- Kiri -->
                        <div class="w-full lg:w-1/2 py-2 lg:pr-7 lg:pb-0">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300">a. Edukasi</h2>
                            <div class="space-y-4">
                                <Edukasi :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref" />
                            </div>
                        </div>
                        <!-- Kanan -->
                        <div class="w-full lg:w-1/2 py-2 lg:pl-7">
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="formSection === 7" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-secondhand-orange-300">Prognosis</h1>
                    <div class="flex flex-col lg:flex-row">
                        <!-- Kiri -->
                        <div class="w-full lg:w-1/2 py-2 lg:pr-7 lg:pb-0">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300">a. Prognosis</h2>
                            <div class="space-y-4">
                                <Prognosis :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref" />
                            </div>
                        </div>
                        <!-- Kanan -->
                        <div class="w-full lg:w-1/2 py-2 lg:pl-7">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300"></h2>
                            <div class="space-y-4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="formSection === 8" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-secondhand-orange-300">Rencana Tindak Lanjut</h1>
                    <div class="flex flex-col lg:flex-row">
                        <!-- Kiri -->
                        <div class="w-full lg:w-1/2 py-2 lg:pr-7 lg:pb-0">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300">a. Rencana Tindak Lanjut</h2>
                            <div class="space-y-4">
                                <RencanaTindakLanjut :encounter_reference="encounter_reference" :subject_reference="subject_ref" :practitioner_reference="practitioner_ref" />
                            </div>
                        </div>
                        <!-- Kanan -->
                        <div class="w-full lg:w-1/2 py-2 lg:pl-7">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300"></h2>
                            <div class="space-y-4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="formSection === 9" class="min-h-full px-5 md:px-10 xl:pl-80 xl:pr-14 pb-10 w-full">
                <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
                    <h1 class="text-2xl font-bold text-secondhand-orange-300">Kondisi saat Meninggalkan RS dan Cara Keluar
                    </h1>
                    <div class="flex flex-col lg:flex-row">
                        <!-- Kiri -->
                        <div class="w-full lg:w-1/2 py-2 lg:pr-7 lg:pb-0">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300">a. Kondisi saat Meninggalkan RS
                            </h2>
                            <div class="space-y-4">
                                <KondisiSaatMeninggalkanRS :encounter_reference="encounter_reference"  :subject_reference="subject_ref"/>
                            </div>
                        </div>
                        <!-- Kanan -->
                        <div class="w-full lg:w-1/2 py-2 lg:pl-7">
                            <h2 class="text-xl font-semibold text-secondhand-orange-300">b. Cara Keluar dari RS</h2>
                            <div class="space-y-4">
                                <CaraKeluar :encounter_satusehat_id="encounter_satusehat_id" />

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <template #responsivecontent>
            <ResponsiveNavButton @click="formSection = 1" :active="formSection === 1"> 1. Identitas Pasien
            </ResponsiveNavButton>
            <ResponsiveNavButton @click="formSection = 2" :active="formSection === 2"> 2. Formulir Rawat Inap
            </ResponsiveNavButton>
            <ResponsiveNavButton @click="formSection = 3" :active="formSection === 3"> 3. Diagnosis
            </ResponsiveNavButton>
            <ResponsiveNavButton @click="formSection = 4" :active="formSection === 4"> 4. Tindakan
            </ResponsiveNavButton>
            <ResponsiveNavButton @click="formSection = 5" :active="formSection === 5"> 5. Tatalaksana
            </ResponsiveNavButton>
            <ResponsiveNavButton @click="formSection = 6" :active="formSection === 6"> 6. Prognosis
            </ResponsiveNavButton>
            <ResponsiveNavButton @click="formSection = 7" :active="formSection === 7"> 7. Rencana Tindak Lanjut
            </ResponsiveNavButton>
            <ResponsiveNavButton @click="formSection = 8" :active="formSection === 8"> 8. Kondisi saat Meninggalkan RS
            </ResponsiveNavButton>
            <ResponsiveNavButton @click="formSection = 9" :active="formSection === 9"> 9. Kondisi & Cara Keluar
            </ResponsiveNavButton>
        </template>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import IdentitasPasien from '@/Pages/FormRekamMedis/IdentitasPasien/IdentitasPasien.vue';
import StatusKunjungan from '@/Pages/FormRekamMedis/IdentitasPasien/StatusKunjungan.vue';
import KeluhanUtama from '@/Pages/FormRekamMedis/Anamnesis/KeluhanUtama.vue';
import RiwayatAlergi from '@/Pages/FormRekamMedis/Anamnesis/RiwayatAlergi.vue';
import PemeriksaanPsikologis from '@/Pages/FormRekamMedis/PemeriksaanPsikologis/PemeriksaanPsikologis.vue';
import TingkatKesadaran from '@/Pages/FormRekamMedis/PemeriksaanFisik/TingkatKesadaran.vue';
import Nadi from '@/Pages/FormRekamMedis/PemeriksaanFisik/Nadi.vue';
import Pernafasan from '@/Pages/FormRekamMedis/PemeriksaanFisik/Pernafasan.vue';
import Sistole from '@/Pages/FormRekamMedis/PemeriksaanFisik/Sistole.vue';
import Diastole from '@/Pages/FormRekamMedis/PemeriksaanFisik/Diastole.vue';
import Suhu from '@/Pages/FormRekamMedis/PemeriksaanFisik/Suhu.vue';
import Anus from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Anus.vue';
import Bibir from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Bibir.vue';
import Dada from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Dada.vue';
import Genital from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Genital.vue';
import Gigigeligi from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Gigigeligi.vue';
import Hidung from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Hidung.vue';
import Jarikaki from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Jarikaki.vue';
import Jaritangan from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Jaritangan.vue';
import Kepala from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Kepala.vue';
import Kukukaki from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Kukukaki.vue';
import Kukutangan from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Kukutangan.vue';
import Lidah from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Lidah.vue';
import Langit from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Langit.vue';
import Leher from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Leher.vue';
import Lenganatas from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Lenganatas.vue';
import Lenganbawah from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Lenganbawah.vue';
import Mata from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Mata.vue';
import Payudara from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Payudara.vue';
import Persendiankaki from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Persendiankaki.vue';
import Persendiantangan from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Persendiantangan.vue';
import Perut from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Perut.vue';
import Punggung from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Punggung.vue';
import Rambut from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Rambut.vue';
import Telinga from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Telinga.vue';
import Tenggorokan from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Tenggorokan.vue';
import Tonsil from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Tonsil.vue';
import Tungkaiatas from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Tungkaiatas.vue';
import Tungkaibawah from '@/Pages/FormRekamMedis/PemeriksaanFisik/Fisik/Tungkaibawah.vue';
import RiwayatPengobatanStatement from '@/Pages/FormRekamMedis/Anamnesis/RiwayatPengobatanStatement.vue';
import RiwayatPenyakitPribadi from '@/Pages/FormRekamMedis/Anamnesis/RiwayatPenyakitPribadi.vue';
import RiwayatPenyakitKeluarga from '@/Pages/FormRekamMedis/Anamnesis/RiwayatPenyakitKeluarga.vue';
import DiagnosisMasuk from '@/Pages/FormRekamMedis/Diagnosis/DiagnosisMasuk.vue';
import DiagnosisAkhirPrimer from '@/Pages/FormRekamMedis/Diagnosis/DiagnosisAkhirPrimer.vue';
import DiagnosisAkhirSekunder from '@/Pages/FormRekamMedis/Diagnosis/DiagnosisAkhirSekunder.vue';
import Tindakan from '@/Pages/FormRekamMedis/Tindakan/Tindakan.vue';
import Edukasi from '@/Pages/FormRekamMedis/Tatalaksana/Edukasi.vue';
import Prognosis from '@/Pages/FormRekamMedis/Prognosis/Prognosis.vue';
import KondisiSaatMeninggalkanRS from '@/Pages/FormRekamMedis/KondisiSaatMeninggalkanRS/KondisiSaatMeninggalkanRS.vue';
import CaraKeluar from '@/Pages/FormRekamMedis/CaraKeluarDariRS/CaraKeluar.vue';
import RencanaTindakLanjut from '@/Pages/FormRekamMedis/RencanaTindakLanjut/RencanaTindakLanjut.vue';
import AsesmenHarian from '@/Pages/FormRekamMedis/AsesmenHarian/AsesmenHarian.vue';
import RiwayatAsesmenHarian from '@/Pages/FormRekamMedis/AsesmenHarian/RiwayatAsesmenHarian.vue';



import BackButton from '@/Components/BackButton.vue';
import NavButton from '@/Components/NavButton.vue';
import MainButtonSmall from '@/Components/MainButtonSmall.vue';
import ResponsiveNavButton from '@/Components/ResponsiveNavButton.vue';
import axios from 'axios';
import { ref, onMounted } from 'vue';

const props = defineProps({
    encounter_satusehat_id: {
        type: String,
    },
});

// Vital Signs
const nadi = ref(null);
const pernafasan = ref(null);
const sistol = ref(null);
const diastole = ref(null);
const suhu = ref(null);
const vitalSubmit = () => {
    nadi.value.submit();
    pernafasan.value.submit();
    sistol.value.submit();
    diastole.value.submit();
    suhu.value.submit();
};

// Pemeriksaan Fisik
const kepala = ref(null);
const mata = ref(null);
const telinga = ref(null);
const hidung = ref(null);
const rambut = ref(null);
const bibir = ref(null);
const gigigeligi = ref(null);
const lidah = ref(null);
const langit = ref(null);
const leher = ref(null);
const tenggorokan = ref(null);
const tonsil = ref(null);
const dada = ref(null);
const payudara = ref(null);
const punggung = ref(null);
const perut = ref(null);
const genital = ref(null);
const anus = ref(null);
const lenganatas = ref(null);
const lenganbawah = ref(null);
const jaritangan = ref(null);
const kukutangan = ref(null);
const persendiantangan = ref(null);
const tungkaiatas = ref(null);
const tungkaibawah = ref(null);
const jarikaki = ref(null);
const kukukaki = ref(null);
const persendiankaki = ref(null);
const fisikSubmit = () => {
    kepala.value.submit();
    mata.value.submit();
    telinga.value.submit();
    hidung.value.submit();
    rambut.value.submit();
    bibir.value.submit();
    gigigeligi.value.submit();
    lidah.value.submit();
    langit.value.submit();
    leher.value.submit();
    tenggorokan.value.submit();
    tonsil.value.submit();
    dada.value.submit();
    payudara.value.submit();
    punggung.value.submit();
    perut.value.submit();
    genital.value.submit();
    anus.value.submit();
    lenganatas.value.submit();
    lenganbawah.value.submit();
    jaritangan.value.submit();
    kukutangan.value.submit();
    persendiantangan.value.submit();
    tungkaiatas.value.submit();
    tungkaibawah.value.submit();
    jarikaki.value.submit();
    kukukaki.value.submit();
    persendiankaki.value.submit();
};

const formSection = ref(1);

const encounter_reference = {
    "reference": `Encounter/${props.encounter_satusehat_id}`
};

const encounter = ref({});
const practitioner_ref = ref({});
const subject_ref = ref({});

const fetchEncounter = async () => {
    const { data } = await axios.get(route('local.encounter.show', { 'satusehat_id': props.encounter_satusehat_id }));
    encounter.value = data;
    practitioner_ref.value = {
        "reference": encounter.value.participant[encounter.value.participant.length - 1].individual.reference
    };
    subject_ref.value = encounter.value.subject
};

onMounted(() => {
    fetchEncounter();
});

</script>
