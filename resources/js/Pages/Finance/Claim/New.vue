<template>
    <AuthenticatedLayout>
        <template #apphead>
            <title>Buat Claim - </title>
        </template>
        <div class="bg-original-white-0 shadow rounded-xl md:rounded-2xl mb-8 p-6 md:py-8 md:px-10">
            <h1 class="text-2xl font-bold text-neutral-black-300">Buat Claim Baru</h1>
            <p class="mb-3 text-base font-normal text-neutral-grey-100">Halaman untuk membuat claim baru.</p>

            <!-- Form -->
            <form @submit.prevent="submit">
                <div class="mt-4">
                    <InputLabel value="Pasien" />
                    <Multiselect mode="single" placeholder="Pasien" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true" :options="searchPatient"
                        label="label" valueProp="satusehatId" track-by="satusehatId" :classes="combo_classes" required
                        v-model="resourceForm.subject" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Enterer" />
                    <Multiselect mode="single" placeholder="Enterer" :object="true" :options="practitionerList"
                        label="name" valueProp="satusehat_id" track-by="satusehat_id" class="mt-1"
                        :classes="combo_classes" required v-model="resourceForm.participant" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Insurer" />
                    <Multiselect mode="single" placeholder="Insurer" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true" :options="searchOrg"
                        label="label" valueProp="id" track-by="id" :classes="combo_classes" required
                        v-model="resourceForm.insurer" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Provider" />
                    <Multiselect mode="single" placeholder="Provider" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true" :options="searchOrg"
                        label="label" valueProp="id" track-by="id" :classes="combo_classes" required
                        v-model="resourceForm.provider" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Status" />
                    <select v-model="resourceForm.status"
                        class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                        <option v-for="item in status" :value=item.id>{{ item.label }}</option>
                    </select>
                </div>
                <div class="mt-4">
                    <InputLabel value="Priority" />
                    <Multiselect mode="single" placeholder="Priority" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="true" :delay="300" :searchable="true" :options="priority"
                        label="label" valueProp="id" track-by="id" :classes="combo_classes" required
                        v-model="resourceForm.priority" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Facility" />
                    <Multiselect mode="single" placeholder="Facility" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true"
                        :options="searchLocation" label="label" valueProp="id" track-by="id" :classes="combo_classes"
                        required v-model="resourceForm.facility" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Type" />
                    <Multiselect mode="single" placeholder="Type" :filter-results="false" :object="true" :min-chars="1"
                        :resolve-on-load="true" :delay="300" :searchable="true" :options="type" label="label"
                        valueProp="id" track-by="id" :classes="combo_classes" required v-model="resourceForm.type" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Sub-Type" />
                    <Multiselect mode="single" placeholder="Sub Type" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="true" :delay="300" :searchable="true" :options="subType"
                        label="label" valueProp="id" track-by="id" :classes="combo_classes" required
                        v-model="resourceForm.subType" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Use" />
                    <select v-model="resourceForm.use"
                        class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                        <option v-for="item in use" :value=item.id>{{ item.label }}</option>
                    </select>
                </div>
                <div class="mt-4">
                    <InputLabel value="Funds Reserve" />
                    <Multiselect mode="single" placeholder="Funds Reserve" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="true" :delay="300" :searchable="true" :options="fundsreserve"
                        label="label" valueProp="id" track-by="id" :classes="combo_classes" required
                        v-model="resourceForm.fundsreserve" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4 grid grid-cols-4 gap-1">
                    <div>
                        <InputLabel value="Payee Type" />
                        <Multiselect mode="single" placeholder="Payee Type" :filter-results="false" :object="true"
                            :min-chars="1" :resolve-on-load="true" :delay="300" :searchable="true" :options="payeeType"
                            label="display" valueProp="code" track-by="code" :classes="combo_classes" required
                            v-model="resourceForm.payee.type" />
                        <InputError class="mt-1" />
                    </div>
                    <div class="grid grid-cols-4 col-span-3 ">
                        <InputLabel value="Payee Party" class="col-span-4" />
                        <select v-model="resourceForm.payee.party.type"
                            class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                            <option v-for="item in payeePartyType" :value=item>{{ item }}</option>
                        </select>
                        <Multiselect mode="single" placeholder="Payee Type" :filter-results="false" :object="true"
                            :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true"
                            :options="searchPayeeParty" label="label" valueProp="id" track-by="id"
                            :classes="combo_classes" required v-model="resourceForm.payee.party.reference"
                            class="col-span-3" />
                        <InputError class="mt-1" />
                    </div>
                </div>
                <div class="mt-4">
                    <InputLabel value="Related Claims" />
                    <div class="claim-field grid grid-cols-4 gap-2" v-for="(claim, index) in resourceForm.related"
                        :key="index">
                        <Multiselect mode="single" placeholder="Claim" :filter-results="false" :object="true"
                            :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true"
                            :options="searchClaim" label="label" valueProp="id" track-by="id" :classes="combo_classes"
                            required v-model="resourceForm.related[index].claimReference" class=" col-span-2" />
                        <Multiselect mode="single" placeholder="Claim Relationship" :filter-results="false"
                            :object="true" :min-chars="1" :resolve-on-load="true" :delay="300" :searchable="true"
                            :options="claimRelationship" label="display" valueProp="code" track-by="code"
                            :classes="combo_classes" required v-model="resourceForm.related[index].claimRelationship" />
                        <SecondaryButtonSmall type="button" @click="removeField(index)"
                            class="inline-block text-center mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg me-1">
                            Remove
                        </SecondaryButtonSmall>
                    </div>
                    <SecondaryButtonSmall type="button" @click="addField"
                        class="mt-2 inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm orange-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg me-1">
                        + Tambah Claim Terkait
                    </SecondaryButtonSmall>
                </div>
                <div class="mt-4">
                    <InputLabel value="Prescription" />
                    <Multiselect mode="single" placeholder="Prescription" :filter-results="false" :object="true"
                        :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true"
                        :options="searchMedicationRequest" label="label" valueProp="id" track-by="id"
                        :classes="combo_classes" v-model="resourceForm.prescription" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4">
                    <InputLabel value="Original Prescription (jika ada perbedaan dari resep dokter dan apoteker)" />
                    <Multiselect mode="single" placeholder="Original Prescription" :filter-results="false"
                        :object="true" :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true"
                        :options="searchMedicationRequest" label="label" valueProp="id" track-by="id"
                        :classes="combo_classes" v-model="resourceForm.originalPrescription" />
                    <InputError class="mt-1" />
                </div>
                <div class="mt-4 grid grid-cols-2 gap-1">
                    <div>
                        <InputLabel value="Billable Period (Start)" />
                        <VueDatePicker class=" border-[1.5px] rounded-lg border-neutral-grey-0 " required
                            v-model="resourceForm.billablePeriod.start">
                        </VueDatePicker>
                        <InputError class="mt-1" />
                    </div>
                    <div>
                        <InputLabel value="Billable Period (End)" />
                        <VueDatePicker class=" border-[1.5px] rounded-lg border-neutral-grey-0 " required
                            v-model="resourceForm.billablePeriod.end">
                        </VueDatePicker>
                        <InputError class="mt-1" />
                    </div>
                </div>

                <div class="mt-4">
                    <InputLabel value="Care Team" />
                    <div class="care-team" v-for="(team, index) in resourceForm.careTeam" :key="index">
                        <p class="text-sm font-bold">Care Team #{{ index + 1 }}</p>
                        <InputLabel class="mt-2" value="Provider (Practitioner/Organization)" />
                        <div class="grid grid-cols-2 gap-1">
                            <Multiselect mode="single" placeholder="Provider (Practitioner)" :filter-results="false"
                                :object="true" :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true"
                                :options="searchProvider" label="label" valueProp="id" track-by="id"
                                :classes="combo_classes" required v-model="resourceForm.careTeam[index].provider" />
                            <Multiselect mode="single" placeholder="Provider (Organization)" :filter-results="false"
                                :object="true" :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true"
                                :options="searchOrg" label="label" valueProp="id" track-by="id" :classes="combo_classes"
                                required v-model="resourceForm.careTeam[index].provider" />
                        </div>

                        <InputLabel class="mt-2" value="Responsible" />

                        <select v-model="resourceForm.careTeam.responsible"
                            class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                            <option value="0">False</option>
                            <option value="1">True</option>
                        </select>
                        <InputLabel class="mt-2" value="Role" />

                        <Multiselect mode="single" placeholder="Role" :filter-results="false" :object="true"
                            :min-chars="1" :resolve-on-load="true" :delay="300" :searchable="true"
                            :options="careTeamRole" label="display" valueProp="code" track-by="code"
                            :classes="combo_classes" required v-model="resourceForm.careTeam[index].role" />
                        <InputLabel class="mt-2" value="Qualification" />

                        <Multiselect mode="single" placeholder="Qualification" :filter-results="false" :object="true"
                            :min-chars="1" :resolve-on-load="true" :delay="300" :searchable="true"
                            :options="careTeamQualification" label="display" valueProp="code" track-by="code"
                            :classes="combo_classes" required v-model="resourceForm.careTeam[index].qualification" />

                        <SecondaryButtonSmall type="button" @click="removeCareTeam(index)"
                            class="inline-block text-center mt-1 mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg me-1">
                            Remove Care Team
                        </SecondaryButtonSmall>
                    </div>
                    <SecondaryButtonSmall type="button" @click="addCareTeam"
                        class="mt-2 inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm orange-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg me-1">
                        + Tambah Care Team
                    </SecondaryButtonSmall>
                </div>

                <!-- <div class="mt-4">
                    <InputLabel value="Supporting Information" />
                    <div class="care-team" v-for="(info, index) in resourceForm.supportingInfo" :key="index">
                        <p class="text-sm font-bold">Supporting Information #{{ index + 1 }}</p>
                        <InputLabel class="mt-2" value="Category" />
                        <Multiselect mode="single" placeholder="Category" :filter-results="false" :object="true"
                            :min-chars="1" :resolve-on-load="true" :delay="300" :searchable="true"
                            :options="supportingInfoCategories" label="label" valueProp="id" track-by="id"
                            :classes="combo_classes" required v-model="resourceForm.supportingInfo[index].category" />
                        <Multiselect mode="single" placeholder="Provider (Organization)" :filter-results="false"
                            :object="true" :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true"
                            :options="searchOrg" label="label" valueProp="id" track-by="id" :classes="combo_classes"
                            required v-model="resourceForm.careTeam[index].provider" />

                        <InputLabel class="mt-2" value="Responsible" />

                        <select v-model="resourceForm.careTeam.responsible"
                            class="block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit">
                            <option value="0">False</option>
                            <option value="1">True</option>
                        </select>
                        <InputLabel class="mt-2" value="Role" />

                        <Multiselect mode="single" placeholder="Role" :filter-results="false" :object="true"
                            :min-chars="1" :resolve-on-load="true" :delay="300" :searchable="true"
                            :options="careTeamRole" label="display" valueProp="code" track-by="code"
                            :classes="combo_classes" required v-model="resourceForm.careTeam[index].role" />
                        <InputLabel class="mt-2" value="Qualification" />

                        <Multiselect mode="single" placeholder="Qualification" :filter-results="false" :object="true"
                            :min-chars="1" :resolve-on-load="true" :delay="300" :searchable="true"
                            :options="careTeamQualification" label="display" valueProp="code" track-by="code"
                            :classes="combo_classes" required v-model="resourceForm.careTeam[index].qualification" />

                        <SecondaryButtonSmall type="button" @click="removeCareTeam(index)"
                            class="inline-block text-center mt-1 mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg me-1">
                            Remove Care Team
                        </SecondaryButtonSmall>
                    </div>
                    <SecondaryButtonSmall type="button" @click="addCareTeam"
                        class="mt-2 inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm orange-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg me-1">
                        + Tambah Care Team
                    </SecondaryButtonSmall>
                    <div class="mt-4">
                        <InputLabel value="Procedures" />
                        <div class="claim-field grid grid-cols-4 gap-2" v-for="(claim, index) in resourceForm.related"
                            :key="index">
                            <Multiselect mode="single" placeholder="Procedure" :filter-results="false" :object="true"
                                :min-chars="1" :resolve-on-load="false" :delay="300" :searchable="true"
                                :options="getProcedure" label="label" valueProp="id" track-by="id"
                                :classes="combo_classes" required v-model="resourceForm.procedure[index]"
                                class=" col-span-2" />
                            <SecondaryButtonSmall type="button" @click="removeProcedure(index)"
                                class="inline-block text-center mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm teal-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg me-1">
                                Remove
                            </SecondaryButtonSmall>
                        </div>
                        <SecondaryButtonSmall type="button" @click="addProcedure"
                            class="mt-2 inline-flex mb-3 justify-center px-4 py-2 border border-transparent rounded-xl font-semibold text-sm orange-button text-original-white-0 transition ease-in-out duration-150 hover:shadow-lg me-1">
                            + Tambah Procedure
                        </SecondaryButtonSmall>
                    </div>

                </div> -->

                <div class="mt-4 text-center">
                    <MainButton :isLoading="isLoading"
                        class="w-full mb-3 mx-auto max-w-[284px] block teal-button text-original-white-0" type="submit">
                        Tambah Claim
                    </MainButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>

</template>
<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Multiselect from '@vueform/multiselect';
import '@vueform/multiselect/themes/default.css';
import TextInput from '@/Components/TextInput.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import MainButton from '@/Components/MainButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayoutBack.vue';
import axios from 'axios';
import { ref, onMounted, computed, provide, watch } from 'vue';

const props = defineProps({
    id: {
        type: String,
    },
});

// Variables
const practitionerList = ref([])

const status = [
    { id: "active", label: "Active" },
    { id: "cancelled", label: "Cancelled" },
    { id: "draft", label: "Draft" },
    { id: "entered-in-error", label: "Entered in Error" }
];

const type = [
    {
        system: "http://terminology.hl7.org/CodeSystem/claim-type",
        id: "institutional",
        label: "Institutional"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claim-type",
        id: "oral",
        label: "Oral"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claim-type",
        id: "pharmacy",
        label: "Pharmacy"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claim-type",
        id: "professional",
        label: "Professional"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claim-type",
        id: "vision",
        label: "Vision"
    },
]

const subType = [
    {
        system: "http://terminology.hl7.org/CodeSystem/ex-claimsubtype",
        id: "ortho",
        label: "Orthodontic Claim"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/ex-claimsubtype",
        id: "emergency",
        label: "Emergency Claim"
    },
];

const use = [
    {
        system: "http://hl7.org/fhir/claim-use",
        id: "claim",
        label: "Claim"
    },
    {
        system: "http://hl7.org/fhir/claim-use",
        id: "preauthorization",
        label: "Preauthorization"
    },
    {
        system: "http://hl7.org/fhir/claim-use",
        id: "predetermination",
        label: "Predetermination"
    }
];

const priority = [
    {
        system: "http://terminology.hl7.org/CodeSystem/processpriority",
        id: "stat",
        label: "Immediate"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/processpriority",
        id: "normal",
        label: "Normal"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/processpriority",
        id: "deferred",
        label: "Deferred"
    },
]

const fundsreserve = [
    {
        system: "http://terminology.hl7.org/CodeSystem/fundsreserve",
        id: "patient",
        label: "Patient"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/fundsreserve",
        id: "provider",
        label: "Provider"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/fundsreserve",
        id: "none",
        label: "None"
    },
]

const claimRelationship = [
    {
        system: "http://terminology.hl7.org/CodeSystem/ex-relatedclaimrelationship",
        code: "prior",
        display: "Prior Claim"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/ex-relatedclaimrelationship",
        code: "associated",
        display: "Associated Claim"
    }
];

const payeeType = [
    {
        system: "http://terminology.hl7.org/CodeSystem/payeetype",
        code: "subscriber",
        display: "Subscriber"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/payeetype",
        code: "provider",
        display: "Provider"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/payeetype",
        code: "other",
        display: "Other"
    },
]

const careTeamRole = [
    {
        system: "http://terminology.hl7.org/CodeSystem/claimcareteamrole",
        code: "primary",
        display: "Primary Provider"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claimcareteamrole",
        code: "assist",
        display: "Assisting Provider"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claimcareteamrole",
        code: "supervisor",
        display: "Supervising Provider"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claimcareteamrole",
        code: "other",
        display: "Primary Provider"
    },
]

const careTeamQualification = [
    {
        system: "http://terminology.hl7.org/CodeSystem/ex-providerqualification",
        code: "311405",
        display: "Dentist"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/ex-providerqualification",
        code: "604215",
        display: "Ophthalmologist"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/ex-providerqualification",
        code: "604210",
        display: "Optometrist"
    },
]

const supportingInfoCategories = [
    {
        system: "http://terminology.kemkes.go.id/CodeSystem/claiminformationcategory",
        code: "upgrade-class-indicator",
        display: "Indikator Naik Kelas"
    },
    {
        system: "http://terminology.kemkes.go.id/CodeSystem/claiminformationcategory",
        code: "upgrade-class-class",
        display: "Kenaikan Kelas"
    },
    {
        system: "http://terminology.kemkes.go.id/CodeSystem/claiminformationcategory",
        code: "claim-text-encoded",
        display: "Claim Text Encoded"
    },
    {
        system: "http://terminology.kemkes.go.id/CodeSystem/claiminformationcategory",
        code: "e-klaim-version",
        display: "Versi Aplikasi E-Klaim"
    },
    {
        system: "http://terminology.kemkes.go.id/CodeSystem/claiminformationcategory",
        code: "unu-grouper-version",
        display: "Versi Grouper INACBG"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "info",
        display: "Information"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "discharge",
        display: "Discharge"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "onset",
        display: "Onset"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "related",
        display: "Related Services"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "exception",
        display: "Exception"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "material",
        display: "Materials Forwarded"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "attachment",
        display: "Attachment"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "missingtooth",
        display: "Missing Tooth"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "prosthesis",
        display: "Prosthesis"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "other",
        display: "Other"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "hospitalized",
        display: "Hospitalized"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "employmentimpacted",
        display: "Employment Impacted"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "externalcause",
        display: "External Cause"
    },
    {
        system: "http://terminology.hl7.org/CodeSystem/claiminformationcategory",
        code: "patientreasonforvisit",
        display: "Patient Reason for Visit"
    }
];

const payeePartyType = ["Practitioner", "Patient", "Organization"]
const providerType = ["Practitioner", "Organization"]

// ResourceForm
const resourceForm = ref({
    status: 'active',
    type: 'institutional',
    subType: 'ortho',
    use: 'claim',
    fundsreserve: 'none',
    subject: {},
    billablePeriod: {
        start: "",
        end: ""
    },
    enterer: {},
    insurer: {},
    provider: {},
    priority: "stat",
    related: [],
    prescription: {},
    originalPrescription: {},
    payee: {
        type: {},
        party: {
            type: 'Organization',
            reference: {}
        },
    },
    facility: {},
    careTeam: [

    ],
    supportingInfo: [],
    procedure: []
});

// Functions
const searchPatient = async (query) => {
    try {
        const { data } = await axios.get(route('rekam-medis.index', { 'nik': query }));
        const originalData = data.rekam_medis.data;
        for (const key in originalData) {
            const currentObject = originalData[key];
            const label = `${currentObject.name} | NIK: ${currentObject.nik}`;
            currentObject.label = label;
            currentObject.id = currentObject.satusehatId;
        }
        return originalData;
    } catch (error) {
        console.error('Error fetching data:', error);
        return [];
    }
}

const getpractitionerList = async () => {
    try {
        const { data } = await axios.get(route('form.index.encounter'));
        practitionerList.value = data;
    } catch (error) {
        console.error('Error fetching data:', error);
        practitionerList.value = [];
    }
};

const getProcedure = async () => {
    try {
        const {data}= await axios.get('/resources/Procedure');
        const originalData = data
        originalData.filter(proc => proc.encounter.reference == "Encounter/" + props.id)
        return originalData
    } catch (error) {
        console.error('Error fetching data:', error);
        return [];
    }
}

const searchOrg = async (query) => {
    try {
        const { data } = await axios.get(route('satusehat.search.organization', { 'name': query }));
        const originalData = data.entry || [];
        // Map the data to the required structure
        return originalData.map(item => ({
            label: item.resource.name,
            id: item.resource.id,
            ...item.resource
        }));
    } catch (error) {
        console.error('Error fetching data:', error);
        return [];
    }
}

const searchClaim = async (query) => {
    try {
        const { data } = await axios.get('/resources/Claim')
        const originalData = data
        originalData.filter(claim => claim.id.includes(query))
        return originalData
    } catch (error) {
        console.error('Error fetching data', error);
        return [];
    }
}

const searchMedicationRequest = async (query) => {
    try {
        const { data } = await axios.get('/resources/MedicationRequest')
        const originalData = data
        originalData.filter(medReq => medReq.id.includes(query))
        return originalData
    } catch (error) {
        console.error('Error fetching data', error);
        return [];
    }
}

const searchProvider = async (query) => {
    try {
        const { data } = await axios.get('/resources/Practitioner')
        const originalData = data
        originalData.filter(party => party.id.includes(query));
        for (const key in originalData) {
            const currentObject = originalData[key];
            const label = currentObject.name[0].text
            currentObject.label = label;
        }
        return originalData;
    } catch (error) {
        console.error('Error fetching data', error);
        return [];
    }

}

const searchPayeeParty = async (query) => {

    switch (resourceForm.value.payee.party.type) {
        case "Organization":
            return searchOrg(query)

        case "Patient":
            return searchPatient(query)

        case "Practitioner":
            try {
                const { data } = await axios.get('/resources/Practitioner')
                const originalData = data
                originalData.filter(party => party.id.includes(query));
                for (const key in originalData) {
                    const currentObject = originalData[key];
                    const label = currentObject.name[0].text
                    currentObject.label = label;
                }
                return originalData;
            } catch (error) {
                console.error('Error fetching data', error);
                return [];
            }
        default:
            return [];
    }
}

const searchLocation = async (query) => {
    try {
        const { data } = await axios.get('/resources/Location');
        const originalData = data
        originalData.filter(party => party.name.includes(query));
        return originalData.map(item => ({
            label: item.name,
            ...item
        }));
    } catch (error) {
        console.error('Error fetching data:', error);
        return [];
    }
}

const addField = () => {
    resourceForm.value.related.push({
        claimReference: null,
        claimRelationship: null
    })
}

const addCareTeam = () => {
    resourceForm.value.careTeam.push({
        provider: {},
        responsible: 1,
        role: {},
        qualification: {}
    })
}

const addSupportingInfo = () => {
    resourceForm.value.supportingInfo.push({

    })
}

const addProcedure = () => {
    resourceForm.value.procedure.push({})
    getProcedure()
}


const removeField = (index) => {
    resourceForm.value.related.splice(index, 1);
};

const removeCareTeam = (index) => {
    resourceForm.value.careTeam.splice(index, 1);
};

const removeSupportingInfo = (index) => {
    resourceForm.value.supportingInfo.splice(index, 1)
}

const removeProcedure = (index) => {
    resourceForm.value.procedure.splice(index, 1)
}

onMounted(() => {
    getpractitionerList()
})

const combo_classes = {
    container: 'relative mx-auto w-full flex items-center justify-end box-border cursor-pointer border-2 border-neutral-grey-0 ring-0 shadow-sm rounded-xl bg-white text-base leading-snug outline-none',
    search: 'w-full absolute inset-0 outline-none border-0 ring-0 focus:ring-original-teal-300 focus:ring-2 appearance-none box-border text-base font-sans bg-white rounded-xl pl-3.5 rtl:pl-0 rtl:pr-3.5',
    placeholder: 'flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-500 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
    optionPointed: 'text-white bg-original-teal-300',
    optionSelected: 'text-white bg-original-teal-300',
    optionDisabled: 'text-gray-300 cursor-not-allowed',
    optionSelectedPointed: 'text-white bg-original-teal-300 opacity-90',
    optionSelectedDisabled: 'text-green-100 bg-original-teal-300 bg-opacity-50 cursor-not-allowed',
};

const submit = async () => {
    
}
</script>