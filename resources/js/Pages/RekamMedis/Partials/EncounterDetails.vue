<template>
    <div class="flex flex-wrap justify-start my-3">
        <MainButtonSmallOutline class="purple-button purple-button-text mr-2 mt-2" @click="formSection = 1">Encounter</MainButtonSmallOutline>
        <MainButtonSmallOutline class="purple-button purple-button-text mr-2 mt-2" @click="formSection = 2">Conditions</MainButtonSmallOutline>
        <MainButtonSmallOutline class="purple-button purple-button-text mr-2 mt-2" @click="formSection = 3">Observations</MainButtonSmallOutline>
        <MainButtonSmallOutline class="purple-button purple-button-text mr-2 mt-2" @click="formSection = 4">Procedures</MainButtonSmallOutline>
        <MainButtonSmallOutline class="purple-button purple-button-text mr-2 mt-2" @click="formSection = 5">Compositions</MainButtonSmallOutline>
        <MainButtonSmallOutline class="purple-button purple-button-text mr-2 mt-2" @click="formSection = 6">Allergy Intolerances</MainButtonSmallOutline>
        <MainButtonSmallOutline class="purple-button purple-button-text mr-2 mt-2" @click="formSection = 7">Clinical Impression</MainButtonSmallOutline>
        <MainButtonSmallOutline class="purple-button purple-button-text mr-2 mt-2" @click="formSection = 8">Service Requests</MainButtonSmallOutline>
        <MainButtonSmallOutline class="purple-button purple-button-text mr-2 mt-2" @click="formSection = 9">Medication Statements</MainButtonSmallOutline>
        <MainButtonSmallOutline class="purple-button purple-button-text mr-2 mt-2" @click="formSection = 10">Questionnaire Responses</MainButtonSmallOutline>
    </div>

    <div v-show="formSection === 1">
        <Encounter :object="encounter.encounter" />
    </div>
    <div v-show="formSection === 2">
        <Conditions :object="encounter.conditions" />
    </div>
    <div v-show="formSection === 3">
        <Observations :object="encounter.observations" />
    </div>
    <div v-show="formSection === 4">
        <Procedures :object="encounter.procedures" />
    </div>
    <div v-show="formSection === 5">
        <Compositions :object="encounter.compositions" />
    </div>
    <div v-show="formSection === 6">
        <AllergyIntolerances :object="encounter.allergyIntolerances" />
    </div>
    <div v-show="formSection === 7">
        <ClinicalImpressions :object="encounter.clinicalImpression" />
    </div>
    <div v-show="formSection === 8">
        <ServiceRequests :object="encounter.serviceRequests" />
    </div>
    <div v-show="formSection === 9">
        <MedicationStatements :object="encounter.medicationStatements" />
    </div>
    <div v-show="formSection === 10">
        <QuestionnaireResponsesGD :object="encounter.questionnaireResponses"  />
    </div>
</template>

<script setup>
import MainButtonSmallOutline from '@/Components/MainButtonSmallOutline.vue';
import Encounter from '@/Pages/RekamMedis/Partials/Resources/Encounter.vue';
import Conditions from '@/Pages/RekamMedis/Partials/Resources/Conditions.vue';
import Observations from '@/Pages/RekamMedis/Partials/Resources/Observations.vue';
import Procedures from '@/Pages/RekamMedis/Partials/Resources/Procedures.vue';
import Compositions from '@/Pages/RekamMedis/Partials/Resources/Compositions.vue';
import ClinicalImpressions from '@/Pages/RekamMedis/Partials/Resources/ClinicalImpressions.vue';
import ServiceRequests from '@/Pages/RekamMedis/Partials/Resources/ServiceRequests.vue';
import MedicationStatements from '@/Pages/RekamMedis/Partials/Resources/MedicationStatements.vue';
import AllergyIntolerances from '@/Pages/RekamMedis/Partials/Resources/AllergyIntolerances.vue';
import QuestionnaireResponsesGD from '@/Pages/RekamMedis/Partials/Resources/QuestionnaireResponsesGD.vue';
import { ref } from 'vue';

const props = defineProps({
    encounter: {
        type: Object,
        required: false
    },
});

const formSection = ref(1);

defineExpose({
    formSection,
})

</script>