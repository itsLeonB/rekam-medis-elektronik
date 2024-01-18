<?php

namespace Database\Factories\Fhir\Datatypes;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Models\Fhir\BackboneElements\AllergyIntoleranceReaction;
use App\Models\Fhir\BackboneElements\ClinicalImpressionInvestigation;
use App\Models\Fhir\BackboneElements\CompositionSection;
use App\Models\Fhir\BackboneElements\ConditionStage;
use App\Models\Fhir\BackboneElements\EncounterDiagnosis;
use App\Models\Fhir\BackboneElements\EncounterHospitalization;
use App\Models\Fhir\BackboneElements\EncounterParticipant;
use App\Models\Fhir\BackboneElements\OrganizationContact;
use App\Models\Fhir\BackboneElements\PatientCommunication;
use App\Models\Fhir\BackboneElements\PatientContact;
use App\Models\Fhir\BackboneElements\ProcedureFocalDevice;
use App\Models\Fhir\BackboneElements\ProcedurePerformer;
use App\Models\Fhir\Datatypes\Dosage;
use App\Models\Fhir\Datatypes\DoseAndRate;
use App\Models\Fhir\Datatypes\Timing;
use App\Models\Fhir\Resources\AllergyIntolerance;
use App\Models\Fhir\Resources\ClinicalImpression;
use App\Models\Fhir\Resources\Composition;
use App\Models\Fhir\Resources\Condition;
use App\Models\Fhir\Resources\Encounter;
use App\Models\Fhir\Resources\Location;
use App\Models\Fhir\Resources\Medication;
use App\Models\Fhir\Resources\MedicationRequest;
use App\Models\Fhir\Resources\MedicationStatement;
use App\Models\Fhir\Resources\Observation;
use App\Models\Fhir\Resources\Organization;
use App\Models\Fhir\Resources\Patient;
use App\Models\Fhir\Resources\Procedure;
use App\Models\Fhir\Resources\ServiceRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CodingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_selected' => fake()->boolean(),
        ];
    }

    public function medicationType(): self
    {
        $code = fake()->randomElement(Medication::MEDICATION_TYPE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Medication::MEDICATION_TYPE['binding']['valueset']['system'],
            'code' => $code,
            'display' => Medication::MEDICATION_TYPE['binding']['valueset']['display'][$code],
        ]);
    }

    public function medicationForm(): self
    {
        $code = fake()->randomElement(Medication::FORM['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Medication::FORM['binding']['valueset']['system'],
            'code' => $code,
            'display' => Medication::FORM['binding']['valueset']['display'][$code],
        ]);
    }

    public function locationServiceClass(): self
    {
        $code = fake()->randomElement(Location::SERVICE_CLASS['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Location::SERVICE_CLASS['binding']['valueset']['system'][$code],
            'code' => $code,
            'display' => Location::SERVICE_CLASS['binding']['valueset']['display'][$code],
        ]);
    }

    public function locationPhysicalType(): self
    {
        $code = fake()->randomElement(Location::PHYSICAL_TYPE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Location::PHYSICAL_TYPE['binding']['valueset']['system'][$code],
            'code' => $code,
            'display' => Location::PHYSICAL_TYPE['binding']['valueset']['display'][$code],
        ]);
    }

    public function locationType(): self
    {
        $code = fake()->randomElement(Location::TYPE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Location::TYPE['binding']['valueset']['system'],
            'code' => $code,
            'display' => Location::TYPE['binding']['valueset']['display'][$code],
        ]);
    }

    public function locationOperationalStatus(): self
    {
        $code = fake()->randomElement(Location::OPERATIONAL_STATUS['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Location::OPERATIONAL_STATUS['binding']['valueset']['system'],
            'code' => $code,
            'display' => Location::OPERATIONAL_STATUS['binding']['valueset']['display'][$code],
        ]);
    }

    public function organizationContactPurpose(): self
    {
        $code = fake()->randomElement(OrganizationContact::PURPOSE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => OrganizationContact::PURPOSE['binding']['valueset']['system'],
            'code' => $code,
            'display' => OrganizationContact::PURPOSE['binding']['valueset']['display'][$code],
        ]);
    }

    public function organizationType(): self
    {
        $code = fake()->randomElement(Organization::TYPE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Organization::TYPE['binding']['valueset']['system'],
            'code' => $code,
            'display' => Organization::TYPE['binding']['valueset']['display'][$code],
        ]);
    }

    public function medicationStatementCategory(): self
    {
        $code = fake()->randomElement(MedicationStatement::CATEGORY['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => MedicationStatement::CATEGORY['binding']['valueset']['system'],
            'code' => $code,
            'display' => MedicationStatement::CATEGORY['binding']['valueset']['display'][$code],
        ]);
    }

    public function medicationStatementStatusReason(): self
    {
        $code = fake()->randomElement(MedicationStatement::STATUS_REASON['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => MedicationStatement::STATUS_REASON['binding']['valueset']['system'],
            'code' => $code,
            'display' => MedicationStatement::STATUS_REASON['binding']['valueset']['display'][$code],
        ]);
    }

    public function serviceRequestPerformerType(): self
    {
        $code = DB::table(ServiceRequest::PERFORMER_TYPE['binding']['valueset']['table'])
            ->inRandomOrder()
            ->first();

        return $this->state(fn (array $attributes) => [
            'system' => ServiceRequest::PERFORMER_TYPE['binding']['valueset']['system'],
            'code' => $code->code,
            'display' => $code->display
        ]);
    }

    public function serviceRequestOrderDetail(): self
    {
        $code = fake()->randomElement(ServiceRequest::ORDER_DETAIL['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => ServiceRequest::ORDER_DETAIL['binding']['valueset']['system'],
            'code' => $code,
            'display' => ServiceRequest::ORDER_DETAIL['binding']['valueset']['display'][$code],
        ]);
    }

    public function serviceRequestCategory(): self
    {
        $code = fake()->randomElement(ServiceRequest::CATEGORY['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => ServiceRequest::CATEGORY['binding']['valueset']['system'],
            'code' => $code,
            'display' => ServiceRequest::CATEGORY['binding']['valueset']['display'][$code],
        ]);
    }

    public function prognosis(): self
    {
        $code = fake()->randomElement(ClinicalImpression::PROGNOSIS_CODEABLE_CONCEPT['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => ClinicalImpression::PROGNOSIS_CODEABLE_CONCEPT['binding']['valueset']['system'],
            'code' => $code,
            'display' => ClinicalImpression::PROGNOSIS_CODEABLE_CONCEPT['binding']['valueset']['display'][$code],
        ]);
    }

    public function clinicalImpressionInvestigationCode(): self
    {
        $code = fake()->randomElement(ClinicalImpressionInvestigation::CODE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => ClinicalImpressionInvestigation::CODE['binding']['valueset']['system'],
            'code' => $code,
            'display' => ClinicalImpressionInvestigation::CODE['binding']['valueset']['display'][$code],
        ]);
    }

    public function allergyReactionExposureRoute(): self
    {
        $code = fake()->randomElement(AllergyIntoleranceReaction::EXPOSURE_ROUTE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => AllergyIntoleranceReaction::EXPOSURE_ROUTE['binding']['valueset']['system'],
            'code' => $code,
            'display' => AllergyIntoleranceReaction::EXPOSURE_ROUTE['binding']['valueset']['display'][$code],
        ]);
    }

    public function allergyReactionManifestation(): self
    {
        $code = fake()->randomElement(AllergyIntoleranceReaction::MANIFESTATION['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => AllergyIntoleranceReaction::MANIFESTATION['binding']['valueset']['system'],
            'code' => $code,
            'display' => AllergyIntoleranceReaction::MANIFESTATION['binding']['valueset']['display'][$code],
        ]);
    }

    public function allergyReactionSubstance(): self
    {
        $code = DB::table(AllergyIntoleranceReaction::SUBSTANCE['binding']['valueset']['table'])
            ->inRandomOrder()
            ->first();

        return $this->state(fn (array $attributes) => [
            'system' => $code->system,
            'code' => $code->code,
            'display' => $code->display
        ]);
    }

    public function allergyCode(): self
    {
        $code = DB::table(AllergyIntolerance::CODE['binding']['valueset']['table'])
            ->inRandomOrder()
            ->first();

        return $this->state(fn (array $attributes) => [
            'system' => $code->system,
            'code' => $code->code,
            'display' => $code->display
        ]);
    }

    public function allergyVerificationStatus(): self
    {
        $code = fake()->randomElement(AllergyIntolerance::VERIFICATION_STATUS['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => AllergyIntolerance::VERIFICATION_STATUS['binding']['valueset']['system'],
            'code' => $code,
            'display' => AllergyIntolerance::VERIFICATION_STATUS['binding']['valueset']['display'][$code],
        ]);
    }

    public function allergyClinicalStatus(): self
    {
        $code = fake()->randomElement(AllergyIntolerance::CLINICAL_STATUS['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => AllergyIntolerance::CLINICAL_STATUS['binding']['valueset']['system'],
            'code' => $code,
            'display' => AllergyIntolerance::CLINICAL_STATUS['binding']['valueset']['display'][$code],
        ]);
    }

    public function compositionSectionEmptyReason(): self
    {
        $code = fake()->randomElement(CompositionSection::EMPTY_REASON['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => CompositionSection::EMPTY_REASON['binding']['valueset']['system'],
            'code' => $code,
            'display' => CompositionSection::EMPTY_REASON['binding']['valueset']['display'][$code],
        ]);
    }

    public function compositionSectionOrderedBy(): self
    {
        $code = fake()->randomElement(CompositionSection::ORDERED_BY['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => CompositionSection::ORDERED_BY['binding']['valueset']['system'],
            'code' => $code,
            'display' => CompositionSection::ORDERED_BY['binding']['valueset']['display'][$code],
        ]);
    }

    public function compositionSectionCode(): self
    {
        $code = fake()->randomElement(CompositionSection::CODE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => CompositionSection::CODE['binding']['valueset']['system'],
            'code' => $code,
            'display' => CompositionSection::CODE['binding']['valueset']['display'][$code],
        ]);
    }

    public function compositionCategory(): self
    {
        $code = fake()->randomElement(Composition::CATEGORY['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Composition::CATEGORY['binding']['valueset']['system'],
            'code' => $code,
            'display' => Composition::CATEGORY['binding']['valueset']['display'][$code],
        ]);
    }

    public function compositionType(): self
    {
        $code = DB::table(Codesystems::LOINC['table'])
            ->where('scale_typ', 'Doc')
            ->inRandomOrder()
            ->first();

        return $this->state(fn (array $attributes) => [
            'system' => Codesystems::LOINC['system'],
            'code' => $code->code,
            'display' => $code->display
        ]);
    }

    public function substitutionReason(): self
    {
        $code = fake()->randomElement(MedicationRequest::SUBSTITUTION_REASON['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => MedicationRequest::SUBSTITUTION_REASON['binding']['valueset']['system'],
            'code' => $code,
            'display' => MedicationRequest::SUBSTITUTION_REASON['binding']['valueset']['display'][$code],
        ]);
    }

    public function doseAndRateType(): self
    {
        $code = fake()->randomElement(DoseAndRate::TYPE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => DoseAndRate::TYPE['binding']['valueset']['system'],
            'code' => $code,
            'display' => DoseAndRate::TYPE['binding']['valueset']['display'][$code],
        ]);
    }

    public function dosageMethod(): self
    {
        $code = fake()->randomElement(Dosage::METHOD['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Dosage::METHOD['binding']['valueset']['system'],
            'code' => $code,
            'display' => Dosage::METHOD['binding']['valueset']['display'][$code],
        ]);
    }

    public function dosageRoute(): self
    {
        $code = fake()->randomElement(Dosage::ROUTE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Dosage::ROUTE['binding']['valueset']['system'],
            'code' => $code,
            'display' => Dosage::ROUTE['binding']['valueset']['display'][$code],
        ]);
    }

    public function dosageSite(): self
    {
        $code = DB::table(Dosage::SITE['binding']['valueset']['table'])
            ->inRandomOrder()
            ->first();

        return $this->state(fn (array $attributes) => [
            'system' => Dosage::SITE['binding']['valueset']['system'],
            'code' => $code->code,
            'display' => $code->display
        ]);
    }

    public function timingCode(): self
    {
        $code = fake()->randomElement(Timing::CODE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Timing::CODE['binding']['valueset']['system'],
            'code' => $code,
            'display' => Timing::CODE['binding']['valueset']['display'][$code],
        ]);
    }

    public function dosageAdditionalInstruction(): self
    {
        $code = fake()->randomElement(Dosage::ADDITIONAL_INSTRUCTION['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Dosage::ADDITIONAL_INSTRUCTION['binding']['valueset']['system'],
            'code' => $code,
            'display' => Dosage::ADDITIONAL_INSTRUCTION['binding']['valueset']['display'][$code],
        ]);
    }

    public function medicationRequestCourseOfTherapyType(): self
    {
        $code = fake()->randomElement(MedicationRequest::COURSE_OF_THERAPY_TYPE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => MedicationRequest::COURSE_OF_THERAPY_TYPE['binding']['valueset']['system'],
            'code' => $code,
            'display' => MedicationRequest::COURSE_OF_THERAPY_TYPE['binding']['valueset']['display'][$code],
        ]);
    }

    public function medicationRequestPerformerType(): self
    {
        $code = DB::table(MedicationRequest::PERFORMER_TYPE['binding']['valueset']['table'])
            ->inRandomOrder()
            ->first();

        return $this->state(fn (array $attributes) => [
            'system' => MedicationRequest::PERFORMER_TYPE['binding']['valueset']['system'],
            'code' => $code->code,
            'display' => $code->display
        ]);
    }

    public function medicationRequestCategory(): self
    {
        $code = fake()->randomElement(MedicationRequest::CATEGORY['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => MedicationRequest::CATEGORY['binding']['valueset']['system'],
            'code' => $code,
            'display' => MedicationRequest::CATEGORY['binding']['valueset']['display'][$code],
        ]);
    }

    public function medicationRequestStatusReason(): self
    {
        $code = fake()->randomElement(MedicationRequest::STATUS_REASON['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => MedicationRequest::STATUS_REASON['binding']['valueset']['system'],
            'code' => $code,
            'display' => MedicationRequest::STATUS_REASON['binding']['valueset']['display'][$code],
        ]);
    }

    public function focalDeviceAction(): self
    {
        $code = DB::table(ProcedureFocalDevice::ACTION['binding']['valueset']['table'])
            ->inRandomOrder()
            ->first();

        return $this->state(fn (array $attributes) => [
            'system' => ProcedureFocalDevice::ACTION['binding']['valueset']['system'],
            'code' => $code->code,
            'display' => $code->display
        ]);
    }

    public function procedureFollowUp(): self
    {
        $code = fake()->randomElement(Procedure::FOLLOW_UP['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Procedure::FOLLOW_UP['binding']['valueset']['system'],
            'code' => $code,
            'display' => Procedure::FOLLOW_UP['binding']['valueset']['display'][$code],
        ]);
    }

    public function procedureOutcome(): self
    {
        $code = fake()->randomElement(Procedure::OUTCOME['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Procedure::OUTCOME['binding']['valueset']['system'],
            'code' => $code,
            'display' => Procedure::OUTCOME['binding']['valueset']['display'][$code],
        ]);
    }

    public function procedurePerformerFunction(): self
    {
        $code = DB::table(ProcedurePerformer::FUNCTION['binding']['valueset']['table'])
            ->inRandomOrder()
            ->first();

        return $this->state(fn (array $attributes) => [
            'system' => ProcedurePerformer::FUNCTION['binding']['valueset']['system'],
            'code' => $code->code,
            'display' => $code->display
        ]);
    }

    public function icd9CmProcedure(): self
    {
        $code = DB::table(Codesystems::ICD9CMProcedure['table'])
            ->inRandomOrder()
            ->first();

        return $this->state(fn (array $attributes) => [
            'system' => Codesystems::ICD9CMProcedure['system'],
            'code' => $code->code,
            'display' => $code->display
        ]);
    }

    public function procedureCategory(): self
    {
        $code = fake()->randomElement(Procedure::CATEGORY['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Procedure::CATEGORY['binding']['valueset']['system'],
            'code' => $code,
            'display' => Procedure::CATEGORY['binding']['valueset']['display'][$code],
        ]);
    }

    public function procedureStatusReason(): self
    {
        $code = DB::table(Procedure::STATUS_REASON['binding']['valueset']['table'])
            ->inRandomOrder()
            ->first();

        return $this->state(fn (array $attributes) => [
            'system' => Procedure::STATUS_REASON['binding']['valueset']['system'],
            'code' => $code->code,
            'display' => $code->display
        ]);
    }

    public function observationInterpretation(): self
    {
        $code = fake()->randomElement(Observation::INTERPRETATION['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Observation::INTERPRETATION['binding']['valueset']['system'][$code],
            'code' => $code,
            'display' => Observation::INTERPRETATION['binding']['valueset']['display'][$code],
        ]);
    }

    public function observationDataAbsentReason(): self
    {
        $code = fake()->randomElement(Observation::DATA_ABSENT_REASON['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Observation::DATA_ABSENT_REASON['binding']['valueset']['system'],
            'code' => $code,
            'display' => Observation::DATA_ABSENT_REASON['binding']['valueset']['display'][$code],
        ]);
    }

    public function loinc(): self
    {
        $code = DB::table(Codesystems::LOINC['table'])
            ->inRandomOrder()
            ->first();

        return $this->state(fn (array $attributes) => [
            'system' => Codesystems::LOINC['system'],
            'code' => $code->code,
            'display' => $code->display
        ]);
    }

    public function observationCategory(): self
    {
        $code = fake()->randomElement(Observation::CATEGORY['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Observation::CATEGORY['binding']['valueset']['system'],
            'code' => $code,
            'display' => Observation::CATEGORY['binding']['valueset']['display'][$code],
        ]);
    }

    public function encounterDiagnosisUse(): self
    {
        $code = fake()->randomElement(EncounterDiagnosis::USE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => EncounterDiagnosis::USE['binding']['valueset']['system'],
            'code' => $code,
            'display' => EncounterDiagnosis::USE['binding']['valueset']['display'][$code],
        ]);
    }

    public function conditionStageType(): self
    {
        $code = DB::table(ConditionStage::TYPE['binding']['valueset']['table'])
            ->inRandomOrder()
            ->first();

        return $this->state(fn (array $attributes) => [
            'system' => ConditionStage::TYPE['binding']['valueset']['system'],
            'code' => $code->code,
            'display' => $code->display
        ]);
    }

    public function conditionStageSummary(): self
    {
        $code = fake()->randomElement(ConditionStage::SUMMARY['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => ConditionStage::SUMMARY['binding']['valueset']['system'],
            'code' => $code,
            'display' => ConditionStage::SUMMARY['binding']['valueset']['display'][$code],
        ]);
    }

    public function snomedBodySite(): self
    {
        $code = DB::table(Valuesets::SNOMEDCTBodySite['table'])
            ->inRandomOrder()
            ->first();

        return $this->state(fn (array $attributes) => [
            'system' => Valuesets::SNOMEDCTBodySite['system'],
            'code' => $code->code,
            'display' => $code->display
        ]);
    }

    public function icd10(): self
    {
        $code = DB::table(Codesystems::ICD10['table'])
            ->inRandomOrder()
            ->first();

        return $this->state(fn (array $attributes) => [
            'system' => Codesystems::ICD10['system'],
            'code' => $code->code,
            'display' => $code->display_en
        ]);
    }

    public function conditionSeverity(): self
    {
        $code = fake()->randomElement(Condition::SEVERITY['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Condition::SEVERITY['binding']['valueset']['system'],
            'code' => $code,
            'display' => Condition::SEVERITY['binding']['valueset']['display'][$code],
        ]);
    }

    public function conditionCategory(): self
    {
        $code = fake()->randomElement(Condition::CATEGORY['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Condition::CATEGORY['binding']['valueset']['system'],
            'code' => $code,
            'display' => Condition::CATEGORY['binding']['valueset']['display'][$code],
        ]);
    }

    public function conditionVerificationStatus(): self
    {
        $code = fake()->randomElement(Condition::VERIFICATION_STATUS['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Condition::VERIFICATION_STATUS['binding']['valueset']['system'],
            'code' => $code,
            'display' => Condition::VERIFICATION_STATUS['binding']['valueset']['display'][$code],
        ]);
    }

    public function conditionClinicalStatus(): self
    {
        $code = fake()->randomElement(Condition::CLINICAL_STATUS['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Condition::CLINICAL_STATUS['binding']['valueset']['system'],
            'code' => $code,
            'display' => Condition::CLINICAL_STATUS['binding']['valueset']['display'][$code],
        ]);
    }

    public function dischargeDisposition(): self
    {
        $code = fake()->randomElement(EncounterHospitalization::DISCHARGE_DISPOSITION['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => EncounterHospitalization::DISCHARGE_DISPOSITION['binding']['valueset']['system'][$code],
            'code' => $code,
            'display' => EncounterHospitalization::DISCHARGE_DISPOSITION['binding']['valueset']['display'][$code],
        ]);
    }

    public function specialArrangement(): self
    {
        $code = fake()->randomElement(EncounterHospitalization::SPECIAL_ARRANGEMENT['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => EncounterHospitalization::SPECIAL_ARRANGEMENT['binding']['valueset']['system'],
            'code' => $code,
            'display' => EncounterHospitalization::SPECIAL_ARRANGEMENT['binding']['valueset']['display'][$code],
        ]);
    }

    public function dietPreference(): self
    {
        $code = fake()->randomElement(EncounterHospitalization::DIET_PREFERENCE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => EncounterHospitalization::DIET_PREFERENCE['binding']['valueset']['system'],
            'code' => $code,
            'display' => EncounterHospitalization::DIET_PREFERENCE['binding']['valueset']['display'][$code],
        ]);
    }

    public function admitSource(): self
    {
        $code = fake()->randomElement(EncounterHospitalization::ADMIT_SOURCE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => EncounterHospitalization::ADMIT_SOURCE['binding']['valueset']['system'],
            'code' => $code,
            'display' => EncounterHospitalization::ADMIT_SOURCE['binding']['valueset']['display'][$code],
        ]);
    }

    public function encounterParticipantType(): self
    {
        $code = fake()->randomElement(EncounterParticipant::TYPE['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => EncounterParticipant::TYPE['binding']['valueset']['system'][$code],
            'code' => $code,
            'display' => EncounterParticipant::TYPE['binding']['valueset']['display'][$code],
        ]);
    }

    public function encounterPriority(): self
    {
        $code = fake()->randomElement(Encounter::PRIORITY['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Encounter::PRIORITY['binding']['valueset']['system'],
            'code' => $code,
            'display' => Encounter::PRIORITY['binding']['valueset']['display'][$code],
        ]);
    }

    public function encounterClass(int $pos): self
    {
        $codes = ['AMB', 'IMP', 'EMER'];
        $code = $codes[$pos];

        return $this->state(fn (array $attributes) => [
            'system' => Encounter::ENC_CLASS['binding']['valueset']['system'],
            'code' => $code,
            'display' => Encounter::ENC_CLASS['binding']['valueset']['display'][$code],
        ]);
    }

    public function encounterServiceType(): self
    {
        $code = fake()->randomElement([124, 177, 186, 88, 168, 218, 557, 221, 286, 263, 189, 221, 124, 286]);

        return $this->state(fn (array $attributes) => [
            'system' => Encounter::SERVICE_TYPE['binding']['valueset']['system'],
            'code' => $code,
            'display' => DB::table(Encounter::SERVICE_TYPE['binding']['valueset']['table'])
                ->where('code', $code)
                ->first()
                ->display,
        ]);
    }

    public function patientMaritalStatus(): self
    {
        $code = fake()->randomElement(Patient::MARITAL_STATUS['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => Patient::MARITAL_STATUS['binding']['valueset']['system'][$code],
            'code' => $code,
            'display' => Patient::MARITAL_STATUS['binding']['valueset']['display'][$code],
        ]);
    }

    public function patientContactRelationship(): self
    {
        $code = fake()->randomElement(PatientContact::RELATIONSHIP['binding']['valueset']['code']);

        return $this->state(fn (array $attributes) => [
            'system' => PatientContact::RELATIONSHIP['binding']['valueset']['system'],
            'code' => $code,
            'display' => PatientContact::RELATIONSHIP['binding']['valueset']['display'][$code],
        ]);
    }

    public function patientCommunicationLanguage(): self
    {
        $code = DB::table(PatientCommunication::LANGUAGE['binding']['valueset']['table'])
            ->inRandomOrder()
            ->first();

        return $this->state(fn (array $attributes) => [
            'system' => PatientCommunication::LANGUAGE['binding']['valueset']['system'],
            'code' => $code->code,
            'display' => $code->display
        ]);
    }
}
