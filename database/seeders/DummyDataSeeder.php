<?php

namespace Database\Seeders;

use App\Fhir\Processor;
use App\Models\Fhir\BackboneElements\AllergyIntoleranceReaction;
use App\Models\Fhir\BackboneElements\ClinicalImpressionFinding;
use App\Models\Fhir\BackboneElements\ClinicalImpressionInvestigation;
use App\Models\Fhir\BackboneElements\CompositionAttester;
use App\Models\Fhir\BackboneElements\CompositionSection;
use App\Models\Fhir\BackboneElements\ConditionStage;
use App\Models\Fhir\BackboneElements\EncounterClassHistory;
use App\Models\Fhir\BackboneElements\EncounterDiagnosis;
use App\Models\Fhir\BackboneElements\EncounterHospitalization;
use App\Models\Fhir\BackboneElements\EncounterLocation;
use App\Models\Fhir\BackboneElements\EncounterParticipant;
use App\Models\Fhir\BackboneElements\EncounterStatusHistory;
use App\Models\Fhir\BackboneElements\MedicationRequestDispenseRequest;
use App\Models\Fhir\BackboneElements\MedicationRequestDispenseRequestInitialFill;
use App\Models\Fhir\BackboneElements\MedicationRequestSubstitution;
use App\Models\Fhir\BackboneElements\PatientCommunication;
use App\Models\Fhir\BackboneElements\PatientContact;
use App\Models\Fhir\BackboneElements\ProcedurePerformer;
use App\Models\Fhir\BackboneElements\QuestionnaireResponseItem;
use App\Models\Fhir\BackboneElements\QuestionnaireResponseItemAnswer;
use App\Models\Fhir\Datatypes\Address;
use App\Models\Fhir\Datatypes\Annotation;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Coding;
use App\Models\Fhir\Datatypes\ComplexExtension;
use App\Models\Fhir\Datatypes\ContactPoint;
use App\Models\Fhir\Datatypes\Dosage;
use App\Models\Fhir\Datatypes\DoseAndRate;
use App\Models\Fhir\Datatypes\Duration;
use App\Models\Fhir\Datatypes\Extension;
use App\Models\Fhir\Datatypes\HumanName;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Narrative;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Ratio;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Datatypes\SimpleQuantity;
use App\Models\Fhir\Datatypes\Timing;
use App\Models\Fhir\Datatypes\TimingRepeat;
use App\Models\Fhir\Resource;
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
use App\Models\Fhir\Resources\Patient;
use App\Models\Fhir\Resources\Practitioner;
use App\Models\Fhir\Resources\Procedure;
use App\Models\Fhir\Resources\QuestionnaireResponse;
use App\Models\Fhir\Resources\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedOnboarding();

            for ($i = 0; $i < 20; $i++) {
                $this->makeDummies();
            }

            User::factory()->count(50)->create();
        });
    }

    public function seedOnboarding()
    {
        $processor = new Processor();

        $files = Storage::disk('onboarding-resource')->files();

        foreach ($files as $f) {
            $resText = Storage::disk('onboarding-resource')->get($f);
            list($resType, $satusehatId) = explode('-', $f, 2);
            list($satusehatId, $ext) = explode('.', $satusehatId, 2);

            switch ($resType) {
                case 'Organization':
                    $org = Resource::create([
                        'satusehat_id' => config('app.organization_id'),
                        'res_type' => $resType
                    ]);

                    $org->content()->create([
                        'res_text' => $resText,
                        'res_ver' => 1
                    ]);

                    $resText = json_decode($resText, true);
                    $orgData = $processor->generateOrganization($resText);
                    $orgData = $this->removeEmptyValues($orgData);
                    $processor->saveOrganization($org, $orgData);
                    $org->save();

                    break;
                case 'Location':
                    $loc = Resource::create([
                        'satusehat_id' => 'mock-location',
                        'res_type' => $resType
                    ]);

                    $loc->content()->create([
                        'res_text' => $resText,
                        'res_ver' => 1
                    ]);

                    $resText = json_decode($resText, true);
                    $locData = $processor->generateLocation($resText);
                    $locData = $this->removeEmptyValues($locData);
                    $processor->saveLocation($loc, $locData);
                    $loc->save();

                    break;
                case 'Practitioner':
                    $prac = Resource::create([
                        'satusehat_id' => 'rsum',
                        'res_type' => $resType
                    ]);

                    $prac->content()->create([
                        'res_text' => $resText,
                        'res_ver' => 1
                    ]);

                    $resText = json_decode($resText, true);
                    $pracData = $processor->generatePractitioner($resText);
                    $pracData = $this->removeEmptyValues($pracData);
                    $processor->savePractitioner($prac, $pracData);
                    $prac->save();

                    break;
                case 'Medication':
                    $med = Resource::create([
                        'satusehat_id' => 'mock-medication',
                        'res_type' => $resType
                    ]);

                    $med->content()->create([
                        'res_text' => $resText,
                        'res_ver' => 1
                    ]);

                    $resText = json_decode($resText, true);
                    $medData = $processor->generateMedication($resText);
                    $medData = $this->removeEmptyValues($medData);
                    $processor->saveMedication($med, $medData);
                    $med->save();

                    break;
            }
        }
    }

    public function makeDummies(bool $forTest = false, bool $patientEncounterOnly = false)
    {
        $patient = $this->dummyPatient();
        $patientId = $patient->resource->satusehat_id;
        $encounter = $this->dummyEncounter($patientId);

        if ($patientEncounterOnly) {
            return [$patient, $encounter];
        }

        $encounterId = $encounter->resource->satusehat_id;

        if ($forTest) {
            $conditionId = $this->dummyCondition($patientId, $encounterId);
            $diagnosis = EncounterDiagnosis::factory()->for($encounter, 'encounter')->create(['rank' => 1]);
            Reference::factory()->for($diagnosis, 'referenceable')->create([
                'reference' => 'Condition/' . $conditionId,
                'attr_type' => 'condition'
            ]);
            $this->fakeCodeableConcept($diagnosis, 'encounterDiagnosisUse', 'use');
        } else {
            for ($j = 1; $j <= 5; $j++) {
                $conditionId = $this->dummyCondition($patientId, $encounterId);
                $diagnosis = EncounterDiagnosis::factory()->for($encounter, 'encounter')->create(['rank' => $j]);
                Reference::factory()->for($diagnosis, 'referenceable')->create([
                    'reference' => 'Condition/' . $conditionId,
                    'attr_type' => 'condition'
                ]);
                $this->fakeCodeableConcept($diagnosis, 'encounterDiagnosisUse', 'use');
            }
        }

        $observationId = $this->dummyObservation($patientId, $encounterId);
        $procedureId = $this->dummyProcedure($patientId, $encounterId);
        $encMedReqId = $this->dummyMedicationRequest($patientId, $encounterId);
        $patMedReqId = $this->dummyMedicationRequest($patientId);
        $encCompId = $this->dummyComposition($patientId, $encounterId);
        $patCompId = $this->dummyComposition($patientId);
        $encAllergyId = $this->dummyAllergyIntolerance($patientId, $encounterId);
        $patAllergyId = $this->dummyAllergyIntolerance($patientId);
        $clinicId = $this->dummyClinicalImpression($patientId, $encounterId);
        $serviceRequest = $this->dummyServiceRequest($patientId, $encounterId);
        Reference::factory()->for($encounter, 'referenceable')->create([
            'reference' => 'ServiceRequest/' . $serviceRequest->resource->satusehat_id,
            'attr_type' => 'basedOn'
        ]);
        $encMedStateId = $this->dummyMedicationStatement($patientId, $encounterId);
        $patMedStateId = $this->dummyMedicationStatement($patientId);
        $encQuestionId = $this->dummyQuestionnaireResponse($patientId, $encounterId);
        $patQuestionId = $this->dummyQuestionnaireResponse($patientId);

        return [$patient, $encounter, $conditionId, $observationId, $procedureId, $encMedReqId, $patMedReqId, $encCompId, $patCompId, $encAllergyId, $patAllergyId, $clinicId, $serviceRequest, $encMedStateId, $patMedStateId, $encQuestionId, $patQuestionId];
    }

    private function dummyQuestionnaireResponse($patientId, $encounterId = null)
    {
        $questionResp = QuestionnaireResponse::factory()->create();
        Reference::factory()->for($questionResp, 'referenceable')->create([
            'reference' => 'Patient/' . $patientId,
            'attr_type' => 'subject'
        ]);
        if ($encounterId) {
            Reference::factory()->for($questionResp, 'referenceable')->create([
                'reference' => 'Encounter/' . $encounterId,
                'attr_type' => 'encounter'
            ]);
        }
        Reference::factory()->for($questionResp, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'author'
        ]);
        Reference::factory()->for($questionResp, 'referenceable')->create([
            'reference' => 'Patient/' . $patientId,
            'attr_type' => 'source'
        ]);

        $item = QuestionnaireResponseItem::factory()->for($questionResp, 'questionnaireResponse')->create();
        $answer = QuestionnaireResponseItemAnswer::factory()->for($item, 'parentItem')->create();
        $item = QuestionnaireResponseItem::factory()->for($questionResp, 'parent')->create();
        $item = QuestionnaireResponseItem::factory()->for($answer, 'parent')->create();
        $answer = QuestionnaireResponseItemAnswer::factory()->for($item, 'parentItem')->create();

        return $questionResp->resource->satusehat_id;
    }

    private function dummyMedicationStatement($patientId, $encounterId = null)
    {
        $medState = MedicationStatement::factory()->create();
        $this->fakeCodeableConcept($medState, 'medicationStatementStatusReason', 'statusReason');
        $this->fakeCodeableConcept($medState, 'medicationStatementCategory', 'category');
        Reference::factory()->for($medState, 'referenceable')->create([
            'reference' => 'Medication/' . Medication::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'medicationReference'
        ]);
        Reference::factory()->for($medState, 'referenceable')->create([
            'reference' => 'Patient/' . $patientId,
            'attr_type' => 'subject'
        ]);
        if ($encounterId) {
            Reference::factory()->for($medState, 'referenceable')->create([
                'reference' => 'Encounter/' . $encounterId,
                'attr_type' => 'context'
            ]);
        }
        Reference::factory()->for($medState, 'referenceable')->create([
            'reference' => 'Patient/' . $patientId,
            'attr_type' => 'informationSource'
        ]);
        $this->fakeCodeableConcept($medState, 'icd10', 'reasonCode');
        $this->fakeNote($medState);
        $this->fakeDosage($medState, 'dosage');

        return $medState->resource->satusehat_id;
    }

    private function dummyEncounter($patientId)
    {
        $practitioner = Resource::where('res_type', 'Practitioner')->inRandomOrder()->first();

        $encounter = Encounter::factory()->create();

        for ($i = 0, $iMax = rand(1, 3); $i < $iMax; $i++) {
            EncounterStatusHistory::factory()
                ->for($encounter, 'encounter')
                ->has(Period::factory(), 'period')
                ->create();
        }

        Coding::factory()->encounterClass()->for($encounter, 'codeable')->create(['attr_type' => 'class']);

        for ($i = 0, $iMax = rand(1, 3); $i < $iMax; $i++) {
            $encClassHistory = EncounterClassHistory::factory()
                ->for($encounter, 'encounter')
                ->has(Period::factory(), 'period')
                ->create();
            Coding::factory()->encounterClass()->for($encClassHistory, 'codeable')->create(['attr_type' => 'class']);
        }

        $this->fakeCodeableConcept($encounter, 'encounterServiceType', 'serviceType');
        $this->fakeCodeableConcept($encounter, 'encounterPriority', 'priority');

        Reference::factory()->for($encounter, 'referenceable')->create([
            'reference' => 'Patient/' . $patientId,
            'attr_type' => 'subject'
        ]);

        for ($i = 0, $iMax = rand(1, 3); $i < $iMax; $i++) {
            $encParticipant = EncounterParticipant::factory()
                ->for($encounter, 'encounter')
                ->has(Period::factory(), 'period')
                ->create();
            $this->fakeCodeableConcept($encParticipant, 'encounterParticipantType', 'type');
            Reference::factory()->for($encParticipant, 'referenceable')->create([
                'reference' => 'Practitioner/' . $practitioner->satusehat_id,
                'attr_type' => 'individual'
            ]);
        }

        Period::factory()->for($encounter, 'periodable')->create();

        $encHosp = EncounterHospitalization::factory()->for($encounter, 'encounter')->create();
        $this->fakeCodeableConcept($encHosp, 'admitSource', 'admitSource');
        $this->fakeCodeableConcept($encHosp, 'dietPreference', 'dietPreference');
        $this->fakeCodeableConcept($encHosp, 'specialArrangement', 'specialArrangement');
        $this->fakeCodeableConcept($encHosp, 'dischargeDisposition', 'dischargeDisposition');

        $locId = Resource::where('res_type', 'Location')->inRandomOrder()->first()->satusehat_id;
        $loc = EncounterLocation::factory()->for($encounter, 'encounter')->create();
        Reference::factory()->for($loc, 'referenceable')->create([
            'reference' => 'Location/' . $locId,
            'attr_type' => 'location'
        ]);

        Reference::factory()->for($encounter, 'referenceable')->create([
            'reference' => 'Organization/' . config('app.organization_id'),
            'attr_type' => 'serviceProvider'
        ]);

        return $encounter;
    }

    private function dummyServiceRequest($patientId, $encounterId)
    {
        $serviceRequest = ServiceRequest::factory()->create();
        $this->fakeCodeableConcept($serviceRequest, 'serviceRequestCategory', 'category');
        $this->fakeCodeableConcept($serviceRequest, 'loinc', 'code');
        $this->fakeCodeableConcept($serviceRequest, 'serviceRequestOrderDetail', 'orderDetail');
        Ratio::factory()->for($serviceRequest, 'rateable')->create(['attr_type' => 'quantityRatio']);
        Reference::factory()->for($serviceRequest, 'referenceable')->create([
            'reference' => 'Patient/' . $patientId,
            'attr_type' => 'subject'
        ]);
        Reference::factory()->for($serviceRequest, 'referenceable')->create([
            'reference' => 'Encounter/' . $encounterId,
            'attr_type' => 'encounter'
        ]);
        Reference::factory()->for($serviceRequest, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'requester'
        ]);
        $this->fakeCodeableConcept($serviceRequest, 'serviceRequestPerformerType', 'performerType');
        Reference::factory()->for($serviceRequest, 'referenceable')->create([
            'reference' => 'Organization/' . config('app.organization_id'),
            'attr_type' => 'performer'
        ]);
        Reference::factory()->for($serviceRequest, 'referenceable')->create([
            'reference' => 'Location/' . Location::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'locationReference'
        ]);
        $this->fakeCodeableConcept($serviceRequest, 'icd10', 'reasonCode');
        $this->fakeCodeableConcept($serviceRequest, 'snomedBodySite', 'bodySite');
        $this->fakeNote($serviceRequest);

        return $serviceRequest;
    }

    private function dummyClinicalImpression($patientId, $encounterId)
    {
        $clinicalImpression = ClinicalImpression::factory()->create();
        $this->fakeCodeableConcept($clinicalImpression, 'icd10', 'statusReason');
        Reference::factory()->for($clinicalImpression, 'referenceable')->create([
            'reference' => 'Patient/' . $patientId,
            'attr_type' => 'subject'
        ]);
        Reference::factory()->for($clinicalImpression, 'referenceable')->create([
            'reference' => 'Encounter/' . $encounterId,
            'attr_type' => 'encounter'
        ]);
        Reference::factory()->for($clinicalImpression, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'assessor'
        ]);

        $investigation = ClinicalImpressionInvestigation::factory()->for($clinicalImpression, 'clinicalImpression')->create();
        $this->fakeCodeableConcept($investigation, 'clinicalImpressionInvestigationCode', 'code');

        $finding = ClinicalImpressionFinding::factory()->for($clinicalImpression, 'clinicalImpression')->create();
        $this->fakeCodeableConcept($finding, 'icd10', 'itemCodeableConcept');

        $this->fakeCodeableConcept($clinicalImpression, 'prognosis', 'prognosisCodeableConcept');
        $this->fakeNote($clinicalImpression);

        return $clinicalImpression->resource->satusehat_id;
    }

    private function dummyAllergyIntolerance($patientId, $encounterId = null)
    {
        $allergy = AllergyIntolerance::factory()->create();
        $this->fakeCodeableConcept($allergy, 'allergyClinicalStatus', 'clinicalStatus');
        $this->fakeCodeableConcept($allergy, 'allergyVerificationStatus', 'verificationStatus');
        $this->fakeCodeableConcept($allergy, 'allergyCode', 'code');
        Reference::factory()->for($allergy, 'referenceable')->create([
            'reference' => 'Patient/' . $patientId,
            'attr_type' => 'patient'
        ]);
        if ($encounterId) {
            Reference::factory()->for($allergy, 'referenceable')->create([
                'reference' => 'Encounter/' . $encounterId,
                'attr_type' => 'encounter'
            ]);
        }
        Reference::factory()->for($allergy, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'recorder'
        ]);
        Reference::factory()->for($allergy, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'asserter'
        ]);
        $this->fakeNote($allergy);

        $reaction = AllergyIntoleranceReaction::factory()->for($allergy, 'allergyIntolerance')->create();
        $this->fakeCodeableConcept($reaction, 'allergyReactionSubstance', 'substance');
        $this->fakeCodeableConcept($reaction, 'allergyReactionManifestation', 'manifestation');
        $this->fakeCodeableConcept($reaction, 'allergyReactionExposureRoute', 'exposureRoute');
        $this->fakeNote($reaction);

        return $allergy->resource->satusehat_id;
    }

    private function dummyComposition($patientId, $encounterId = null)
    {
        $composition = Composition::factory()->create();
        $this->fakeCodeableConcept($composition, 'compositionType', 'type');
        $this->fakeCodeableConcept($composition, 'compositionCategory', 'category');
        Reference::factory()->for($composition, 'referenceable')->create([
            'reference' => 'Patient/' . $patientId,
            'attr_type' => 'subject'
        ]);
        if ($encounterId) {
            Reference::factory()->for($composition, 'referenceable')->create([
                'reference' => 'Encounter/' . $encounterId,
                'attr_type' => 'encounter'
            ]);
        }
        Reference::factory()->for($composition, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'author'
        ]);

        $attester = CompositionAttester::factory()->for($composition, 'composition')->create();
        Reference::factory()->for($attester, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'party'
        ]);

        Reference::factory()->for($composition, 'referenceable')->create([
            'reference' => 'Organization/' . config('app.organization_id'),
            'attr_type' => 'custodian'
        ]);

        $section = CompositionSection::factory()->for($composition, 'composition')->create();
        $this->fakeCodeableConcept($section, 'compositionSectionCode', 'code');
        Reference::factory()->for($section, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'author'
        ]);
        Narrative::factory()->for($section, 'narrateable')->create(['attr_type' => 'text']);
        $this->fakeCodeableConcept($section, 'compositionSectionOrderedBy', 'orderedBy');
        $this->fakeCodeableConcept($section, 'compositionSectionEmptyReason', 'emptyReason');

        $section = CompositionSection::factory()->for($section, 'parent')->create();
        $this->fakeCodeableConcept($section, 'compositionSectionCode', 'code');
        Reference::factory()->for($section, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'author'
        ]);
        Narrative::factory()->for($section, 'narrateable')->create(['attr_type' => 'text']);
        $this->fakeCodeableConcept($section, 'compositionSectionOrderedBy', 'orderedBy');
        $this->fakeCodeableConcept($section, 'compositionSectionEmptyReason', 'emptyReason');

        return $composition->resource->satusehat_id;
    }

    private function dummyMedicationRequest($patientId, $encounterId = null)
    {
        $medReq = MedicationRequest::factory()->create();
        $this->fakeCodeableConcept($medReq, 'medicationRequestStatusReason', 'statusReason');
        $this->fakeCodeableConcept($medReq, 'medicationRequestCategory', 'category');
        Reference::factory()->for($medReq, 'referenceable')->create([
            'reference' => 'Medication/' . Medication::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'medicationReference'
        ]);
        Reference::factory()->for($medReq, 'referenceable')->create([
            'reference' => 'Patient/' . $patientId,
            'attr_type' => 'subject'
        ]);
        if ($encounterId) {
            Reference::factory()->for($medReq, 'referenceable')->create([
                'reference' => 'Encounter/' . $encounterId,
                'attr_type' => 'encounter'
            ]);
        }
        Reference::factory()->for($medReq, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'requester'
        ]);
        Reference::factory()->for($medReq, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'performer'
        ]);
        $this->fakeCodeableConcept($medReq, 'medicationRequestPerformerType', 'performerType');
        Reference::factory()->for($medReq, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'recorder'
        ]);
        $this->fakeCodeableConcept($medReq, 'icd10', 'reasonCode');
        $this->fakeCodeableConcept($medReq, 'medicationRequestCourseOfTherapyType', 'courseOfTherapyType');
        $this->fakeNote($medReq);
        $this->fakeDosage($medReq, 'dosageInstruction');

        $dispReq = MedicationRequestDispenseRequest::factory()->for($medReq, 'medicationRequest')->create();
        $initialFill = MedicationRequestDispenseRequestInitialFill::factory()->for($dispReq, 'dispenseRequest')->create();
        Duration::factory()->for($initialFill, 'durationable')->create(['attr_type' => 'duration']);
        SimpleQuantity::factory()->for($initialFill, 'simple_quantifiable')->create(['attr_type' => 'quantity']);
        Duration::factory()->for($dispReq, 'durationable')->create(['attr_type' => 'dispenseInterval']);
        Period::factory()->for($dispReq, 'periodable')->create(['attr_type' => 'validityPeriod']);
        SimpleQuantity::factory()->for($dispReq, 'simple_quantifiable')->create(['attr_type' => 'quantity']);
        Duration::factory()->for($dispReq, 'durationable')->create(['attr_type' => 'expectedSupplyDuration']);
        Reference::factory()->for($dispReq, 'referenceable')->create([
            'reference' => 'Organization/' . config('app.organization_id'),
            'attr_type' => 'performer'
        ]);

        $subs = MedicationRequestSubstitution::factory()->for($medReq, 'medicationRequest')->create();
        $this->fakeCodeableConcept($subs, 'substitutionReason', 'reason');

        return $medReq->resource->satusehat_id;
    }

    private function dummyProcedure($patientId, $encounterId)
    {
        $procedure = Procedure::factory()->create();
        $this->fakeCodeableConcept($procedure, 'procedureStatusReason', 'statusReason');
        $this->fakeCodeableConcept($procedure, 'procedureCategory', 'category');
        $this->fakeCodeableConcept($procedure, 'icd9CmProcedure', 'code');
        Reference::factory()->for($procedure, 'referenceable')->create([
            'reference' => 'Patient/' . $patientId,
            'attr_type' => 'subject'
        ]);
        Reference::factory()->for($procedure, 'referenceable')->create([
            'reference' => 'Encounter/' . $encounterId,
            'attr_type' => 'encounter'
        ]);
        Reference::factory()->for($procedure, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'recorder'
        ]);
        Reference::factory()->for($procedure, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'asserter'
        ]);

        $performer = ProcedurePerformer::factory()->for($procedure, 'procedure')->create();
        $this->fakeCodeableConcept($performer, 'procedurePerformerFunction', 'function');
        Reference::factory()->for($performer, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'actor'
        ]);
        Reference::factory()->for($performer, 'referenceable')->create([
            'reference' => 'Organization/' . config('app.organization_id'),
            'attr_type' => 'onBehalfOf'
        ]);

        Reference::factory()->for($procedure, 'referenceable')->create([
            'reference' => 'Location/' . Location::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'location'
        ]);
        $this->fakeCodeableConcept($procedure, 'icd10', 'reasonCode');
        $this->fakeCodeableConcept($procedure, 'snomedBodySite', 'bodySite');
        $this->fakeCodeableConcept($procedure, 'procedureOutcome', 'outcome');
        $this->fakeCodeableConcept($procedure, 'icd10', 'complication');
        $this->fakeCodeableConcept($procedure, 'procedureFollowUp', 'followUp');
        $this->fakeNote($procedure);

        return $procedure->resource->satusehat_id;
    }

    private function fakeDosage($parent, $attribute)
    {
        $dosage = Dosage::factory()->for($parent, 'dosageable')->create(['attr_type' => $attribute]);
        $this->fakeCodeableConcept($dosage, 'dosageAdditionalInstruction', 'additionalInstruction');
        $this->fakeTiming($dosage, 'timing');
        $this->fakeCodeableConcept($dosage, 'dosageSite', 'site');
        $this->fakeCodeableConcept($dosage, 'dosageRoute', 'route');
        $this->fakeCodeableConcept($dosage, 'dosageMethod', 'method');

        $doseAndRate = DoseAndRate::factory()->for($dosage, 'dosage')->create();
        $this->fakeCodeableConcept($doseAndRate, 'doseAndRateType', 'type');
        SimpleQuantity::factory()->for($doseAndRate, 'simple_quantifiable')->create(['attr_type' => 'doseQuantity']);
        SimpleQuantity::factory()->for($doseAndRate, 'simple_quantifiable')->create(['attr_type' => 'rateQuantity']);

        Ratio::factory()->for($dosage, 'rateable')->create(['attr_type' => 'maxDosePerPeriod']);
        SimpleQuantity::factory()->for($dosage, 'simple_quantifiable')->create(['attr_type' => 'maxDosePerAdministration']);
        SimpleQuantity::factory()->for($dosage, 'simple_quantifiable')->create(['attr_type' => 'maxDosePerLifetime']);
    }

    private function fakeTiming($parent, $attribute)
    {
        $timing = Timing::factory()->for($parent, 'timeable')->create(['attr_type' => $attribute]);
        TimingRepeat::factory()
            ->for($timing, 'timing')
            ->has(Period::factory(), 'boundsPeriod')
            ->create();
        $this->fakeCodeableConcept($timing, 'timingCode', 'code');
    }

    private function dummyObservation($patientId, $encounterId)
    {
        $observation = Observation::factory()->create();
        $this->fakeCodeableConcept($observation, 'observationCategory', 'category');
        $this->fakeCodeableConcept($observation, 'loinc', 'code');
        Reference::factory()->for($observation, 'referenceable')->create([
            'reference' => 'Patient/' . $patientId,
            'attr_type' => 'subject'
        ]);
        Reference::factory()->for($observation, 'referenceable')->create([
            'reference' => 'Encounter/' . $encounterId,
            'attr_type' => 'encounter'
        ]);
        Reference::factory()->for($observation, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'performer'
        ]);
        $this->fakeCodeableConcept($observation, 'observationDataAbsentReason', 'dataAbsentReason');
        $this->fakeCodeableConcept($observation, 'observationInterpretation', 'interpretation');
        $this->fakeNote($observation);
        $this->fakeCodeableConcept($observation, 'snomedBodySite', 'bodySite');

        return $observation->resource->satusehat_id;
    }

    private function dummyCondition($patientId, $encounterId)
    {
        $condition = Condition::factory()->create();
        $this->fakeCodeableConcept($condition, 'conditionClinicalStatus', 'clinicalStatus');
        $this->fakeCodeableConcept($condition, 'conditionVerificationStatus', 'verificationStatus');
        $this->fakeCodeableConcept($condition, 'conditionCategory', 'category');
        $this->fakeCodeableConcept($condition, 'conditionSeverity', 'severity');
        $this->fakeCodeableConcept($condition, 'icd10', 'code');
        $this->fakeCodeableConcept($condition, 'snomedBodySite', 'bodySite');

        Reference::factory()->for($condition, 'referenceable')->create([
            'reference' => 'Patient/' . $patientId,
            'attr_type' => 'subject'
        ]);

        Reference::factory()->for($condition, 'referenceable')->create([
            'reference' => 'Encounter/' . $encounterId,
            'attr_type' => 'encounter'
        ]);

        Reference::factory()->for($condition, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'recorder'
        ]);

        Reference::factory()->for($condition, 'referenceable')->create([
            'reference' => 'Practitioner/' . Practitioner::inRandomOrder()->first()->resource->satusehat_id,
            'attr_type' => 'asserter'
        ]);

        $stage = ConditionStage::factory()->for($condition, 'condition')->create();
        $this->fakeCodeableConcept($stage, 'conditionStageSummary', 'summary');
        $this->fakeCodeableConcept($stage, 'conditionStageType', 'type');

        $this->fakeNote($condition);

        return $condition->resource->satusehat_id;
    }

    private function dummyPatient()
    {
        $patient = Patient::factory()->create();

        $isChild = [true, false];
        $isChild = fake()->randomElement($isChild);

        if ($isChild) {
            Identifier::factory()->nikIbu()->for($patient, 'identifiable')->create(['attr_type' => 'identifier']);
        } else {
            Identifier::factory()->nik()->for($patient, 'identifiable')->create(['attr_type' => 'identifier']);
            Identifier::factory()->bpjs()->for($patient, 'identifiable')->create(['attr_type' => 'identifier']);
            Identifier::factory()->paspor()->for($patient, 'identifiable')->create(['attr_type' => 'identifier']);
            Identifier::factory()->kk()->for($patient, 'identifiable')->create(['attr_type' => 'identifier']);
        }

        HumanName::factory()->for($patient, 'humanNameable')->create(['attr_type' => 'name']);
        $this->fakeTelecom($patient);
        $this->fakeAddress($patient);
        $this->fakeCodeableConcept($patient, 'patientMaritalStatus', 'maritalStatus');

        for ($i = 0; $i < 2; $i++) {
            $contact = PatientContact::factory()->for($patient, 'patient')->create();
            $this->fakeCodeableConcept($contact, 'patientContactRelationship', 'relationship');
            HumanName::factory()->for($contact, 'humanNameable')->create(['attr_type' => 'name']);
            $this->fakeTelecom($contact);
            $this->fakeAddress($contact);
        }

        for ($i = 0; $i < 2; $i++) {
            $communication = PatientCommunication::factory()->for($patient, 'patient')->create();
            $this->fakeCodeableConcept($communication, 'patientCommunicationLanguage', 'language');
        }

        return $patient;
    }

    private function fakeNote($parent)
    {
        $note = Annotation::factory()->for($parent, 'annotable')->create(['attr_type' => 'note']);
        Reference::factory()->for($note, 'referenceable')->create([
            'attr_type' => 'authorReference',
            'reference' => 'Practitioner/' . Practitioner::first()->resource->satusehat_id
        ]);
    }

    private function fakeCodeableConcept($parent, $attribute, $type)
    {
        CodeableConcept::factory()
            ->for($parent, 'codeable')
            ->has(Coding::factory()->$attribute(), 'coding')
            ->create(['attr_type' => $type]);
    }

    private function fakeComplexExtension($parent, $attribute, $type)
    {
        $administrativeCode = ComplexExtension::factory()->$attribute()->for($parent, 'complexExtendable')->create(['attr_type' => $type]);
        foreach ($administrativeCode->exts as $ext) {
            Extension::factory()->$ext()->for($administrativeCode, 'extendable')->create(['attr_type' => 'extension']);
        }
    }

    private function fakeTelecom($parent)
    {
        ContactPoint::factory()->phone()->for($parent, 'contactPointable')->create([
            'attr_type' => 'telecom',
            'rank' => 1
        ]);
        ContactPoint::factory()->email()->for($parent, 'contactPointable')->create([
            'attr_type' => 'telecom',
            'rank' => 2
        ]);
        ContactPoint::factory()->url()->for($parent, 'contactPointable')->create([
            'attr_type' => 'telecom',
            'rank' => 3
        ]);
    }

    private function fakeAddress($parent)
    {
        for ($i = 0; $i < 2; $i++) {
            $address = Address::factory()->for($parent, 'addressable')->create(['attr_type' => 'address']);
            $this->fakeComplexExtension($address, 'administrativeCode', 'administrativeCode');
            $this->fakeComplexExtension($address, 'geolocation', 'geolocation');
        }
    }

    private function removeEmptyValues($array)
    {
        return array_filter($array, function ($value) {
            if (is_array($value)) {
                return !empty($this->removeEmptyValues($value));
            }
            return $value !== null && $value !== "";
        });
    }
}
