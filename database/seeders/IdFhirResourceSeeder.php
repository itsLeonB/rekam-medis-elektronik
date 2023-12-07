<?php

namespace Database\Seeders;

use App\Constants;
use App\Models\AllergyIntolerance;
use App\Models\ClinicalImpression;
use App\Models\Condition;
use App\Models\MedicationRequest;
use App\Models\Observation;
use App\Models\ObservationComponent;
use App\Models\Procedure;
use App\Models\Resource;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class IdFhirResourceSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $files = Storage::disk('example-id-fhir')->files();

        foreach ($files as $f) {
            $resText = Storage::disk('example-id-fhir')->get($f);
            list($resType, $satusehatId) = explode('-', $f, 2);
            list($satusehatId, $ext) = explode('.', $satusehatId, 2);

            $res = Resource::create(
                [
                    'satusehat_id' => $satusehatId,
                    'res_type' => $resType
                ]
            );

            $res->content()->create(
                [
                    'res_text' => $resText,
                    'res_ver' => 1
                ]
            );

            switch ($resType) {
                    // case 'Organization':
                    //     $this->seedOrganization($res, $resText);
                    //     break;
                    // case 'Location':
                    //     $this->seedLocation($res, $resText);
                    //     break;
                    // case 'Practitioner':
                    //     $this->seedPractitioner($res, $resText);
                    //     break;
                    // case 'Patient':
                    //     $this->seedPatient($res, $resText);
                    //     break;
                    // case 'Encounter':
                    //     $this->seedEncounter($res, $resText);
                    //     break;
                    // case 'Condition':
                    //     $this->seedCondition($res, $resText);
                    //     break;
                    // case 'Observation':
                    //     $this->seedObservation($res, $resText);
                    //     break;
                    // case 'Procedure':
                    //     $this->seedProcedure($res, $resText);
                    //     break;
                    // case 'Medication':
                    //     $this->seedMedication($res, $resText);
                    //     break;
                    // case 'MedicationRequest':
                    //     $this->seedMedicationRequest($res, $resText);
                    //     break;
                    // case 'Composition':
                    //     $this->seedComposition($res, $resText);
                    //     break;
                    // case 'AllergyIntolerance':
                    //     $this->seedAllergyIntolerance($res, $resText);
                    //     break;
                case 'ClinicalImpression':
                    $this->seedClinicalImpression($res, $resText);
                    break;
                case 'QuestionnaireResponse':
                    $this->seedQuestionnaireResponse($res, $resText);
                    break;
                default:
                    break;
            }
        }
    }


    private function seedClinicalImpression($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $clinicalImpressionData = [
            'status' => returnAttribute($resourceContent, ['status']),
            'status_reason_code' => returnAttribute($resourceContent, ['statusReason', 'coding', 0, 'code']),
            'status_reason_text' => returnAttribute($resourceContent, ['statusReason', 'text']),
            'code_system' => returnAttribute($resourceContent, ['code', 'coding', 0, 'system']),
            'code_code' => returnAttribute($resourceContent, ['code', 'coding', 0, 'code']),
            'code_display' => returnAttribute($resourceContent, ['code', 'coding', 0, 'display']),
            'code_text' => returnAttribute($resourceContent, ['code', 'text']),
            'description' => returnAttribute($resourceContent, ['description']),
            'subject' => returnAttribute($resourceContent, ['subject', 'reference']),
            'encounter' => returnAttribute($resourceContent, ['encounter', 'reference']),
            'effective' => returnVariableAttribute($resourceContent, ClinicalImpression::EFFECTIVE['variableTypes']),
            'date' => returnAttribute($resourceContent, ['date']),
            'assessor' => returnAttribute($resourceContent, ['assessor', 'reference']),
            'previous' => returnAttribute($resourceContent, ['previous', 'reference']),
            'problem' => $this->returnMultiReference(returnAttribute($resourceContent, ['problem'])),
            'protocol' => returnAttribute($resourceContent, ['protocol']),
            'summary' => returnAttribute($resourceContent, ['summary']),
            'prognosis_codeable_concept' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['prognosisCodeableConcept'])),
            'prognosis_reference' => $this->returnMultiReference(returnAttribute($resourceContent, ['prognosisReference'])),
            'supporting_info' => $this->returnMultiReference(returnAttribute($resourceContent, ['supportingInfo'])),
        ];

        $clinicalImpressionData = removeEmptyValues($clinicalImpressionData);

        $clinicalImpression = $resource->clinicalImpression()->createQuietly($clinicalImpressionData);
        $clinicalImpression->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));

        $investigations = returnAttribute($resourceContent, ['investigation']);
        if (!empty($investigations)) {
            foreach ($investigations as $i) {
                $investigationData = [
                    'code' => returnAttribute($i, ['code', 'coding', 0, 'code']),
                    'code_text' => returnAttribute($i, ['code', 'text']),
                    'item' => $this->returnMultiReference(returnAttribute($i, ['item'])),
                ];

                $clinicalImpression->investigation()->createQuietly($investigationData);
            }
        }

        $findings = returnAttribute($resourceContent, ['finding']);
        if (!empty($findings)) {
            foreach ($findings as $f) {
                $findingData = [
                    'item_codeable_concept' => returnAttribute($f, ['itemCodeableConcept', 'coding', 0, 'code']),
                    'item_reference' => returnAttribute($f, ['itemReference', 'reference']),
                    'basis' => returnAttribute($f, ['basis']),
                ];

                $clinicalImpression->finding()->createQuietly($findingData);
            }
        }

        $clinicalImpression->note()->createManyQuietly($this->returnAnnotation(returnAttribute($resourceContent, ['note'])));
    }


    private function seedAllergyIntolerance($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $allergyIntoleranceData = [
            'clinical_status' => returnAttribute($resourceContent, ['clinicalStatus', 'coding', 0, 'code']),
            'verification_status' => returnAttribute($resourceContent, ['verificationStatus', 'coding', 0, 'code']),
            'type' => returnAttribute($resourceContent, ['type', 'coding', 0, 'code']),
            'category' => returnAttribute($resourceContent, ['category']),
            'criticality' => returnAttribute($resourceContent, ['criticality']),
            'code_system' => returnAttribute($resourceContent, ['code', 'coding', 0, 'system']),
            'code_code' => returnAttribute($resourceContent, ['code', 'coding', 0, 'code']),
            'code_display' => returnAttribute($resourceContent, ['code', 'coding', 0, 'display']),
            'patient' => returnAttribute($resourceContent, ['patient', 'reference']),
            'encounter' => returnAttribute($resourceContent, ['encounter', 'reference']),
            'onset' => returnVariableAttribute($resourceContent, AllergyIntolerance::ONSET['variableTypes']),
            'recorded_date' => returnAttribute($resourceContent, ['recordedDate']),
            'recorder' => returnAttribute($resourceContent, ['recorder', 'reference']),
            'asserter' => returnAttribute($resourceContent, ['asserter', 'reference']),
            'last_occurence' => returnAttribute($resourceContent, ['lastOccurence']),
        ];

        $allergyIntoleranceData = removeEmptyValues($allergyIntoleranceData);
        $allergyIntolerance = $resource->allergyIntolerance()->createQuietly($allergyIntoleranceData);

        $allergyIntolerance->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));
        $allergyIntolerance->note()->createManyQuietly($this->returnAnnotation(returnAttribute($resourceContent, ['note'])));

        $reactions = returnAttribute($resourceContent, ['reaction']);
        if (!empty($reactions)) {
            foreach ($reactions as $r) {
                $reactionData = [
                    'substance_system' => returnAttribute($r, ['substance', 'coding', 0, 'system']),
                    'substance_code' => returnAttribute($r, ['substance', 'coding', 0, 'code']),
                    'substance_display' => returnAttribute($r, ['substance', 'coding', 0, 'display']),
                    'manifestation' => $this->returnMultiCodeableConcept(returnAttribute($r, ['manifestation'])),
                    'description' => returnAttribute($r, ['description']),
                    'onset' => returnAttribute($r, ['onset']),
                    'severity' => returnAttribute($r, ['severity']),
                    'exposure_route' => returnAttribute($r, ['exposureRoute', 'coding', 0, 'code']),
                ];

                $reaction = $allergyIntolerance->reaction()->createQuietly($reactionData);
                $reaction->note()->createManyQuietly($this->returnAnnotation(returnAttribute($r, ['note'])));
            }
        }
    }


    private function seedComposition($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $compositionData = [
            'identifier_system' => returnAttribute($resourceContent, ['identifier', 'system']),
            'identifier_use' => returnAttribute($resourceContent, ['identifier', 'use']),
            'identifier_value' => returnAttribute($resourceContent, ['identifier', 'value']),
            'status' => returnAttribute($resourceContent, ['status']),
            'type_system' => returnAttribute($resourceContent, ['type', 'coding', 0, 'system']),
            'type_code' => returnAttribute($resourceContent, ['type', 'coding', 0, 'code']),
            'type_display' => returnAttribute($resourceContent, ['type', 'coding', 0, 'display']),
            'category' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['category'])),
            'subject' => returnAttribute($resourceContent, ['subject', 'reference']),
            'encounter' => returnAttribute($resourceContent, ['encounter', 'reference']),
            'date' => returnAttribute($resourceContent, ['date']),
            'author' => $this->returnMultiReference(returnAttribute($resourceContent, ['author'])),
            'title' => returnAttribute($resourceContent, ['title']),
            'confidentiality' => returnAttribute($resourceContent, ['confidentiality']),
            'custodian' => returnAttribute($resourceContent, ['custodian', 'reference']),
        ];

        $composition = $resource->composition()->createQuietly($compositionData);

        $attester = returnAttribute($resourceContent, ['attester']);
        if (!empty($attester)) {
            foreach ($attester as $a) {
                $attesterData = [
                    'mode' => returnAttribute($a, ['mode']),
                    'time' => returnAttribute($a, ['time']),
                    'party' => returnAttribute($a, ['party', 'reference']),
                ];

                $composition->attester()->createQuietly($attesterData);
            }
        }

        $relatesTo = returnAttribute($resourceContent, ['relatesTo']);
        if (!empty($relatesTo)) {
            foreach ($relatesTo as $r) {
                $relatesToData = [
                    'code' => returnAttribute($r, ['code']),
                    'target' => returnVariableAttribute($r, ['targetIdentifier', 'targetReference']),
                ];

                $composition->relatesTo()->createQuietly($relatesToData);
            }
        }

        $event = returnAttribute($resourceContent, ['event']);
        if (!empty($event)) {
            $eventData = [
                'code' => $this->returnMultiCodeableConcept(returnAttribute($event, ['code'])),
                'period_start' => returnAttribute($event, ['period', 'start']),
                'period_end' => returnAttribute($event, ['period', 'end']),
                'detail' => $this->returnMultiReference(returnAttribute($event, ['detail'])),
            ];

            $composition->event()->createQuietly($eventData);
        }

        $section = returnAttribute($resourceContent, ['section']);
        if (!empty($section)) {
            foreach ($section as $s) {
                $sectionData = [
                    'title' => returnAttribute($s, ['title']),
                    'code' => returnAttribute($s, ['code', 'coding', 0, 'code']),
                    'author' => $this->returnMultiReference(returnAttribute($s, ['author'])),
                    'focus' => returnAttribute($s, ['focus', 'reference']),
                    'text_status' => returnAttribute($s, ['text', 'status']),
                    'text_div' => returnAttribute($s, ['text', 'div']),
                    'mode' => returnAttribute($s, ['mode']),
                    'ordered_by' => returnAttribute($s, ['orderedBy', 'coding', 0, 'code']),
                    'entry' => $this->returnMultiReference(returnAttribute($s, ['entry'])),
                    'empty_reason' => returnAttribute($s, ['emptyReason', 'coding', 0, 'code']),
                    'section' => returnAttribute($s, ['section']),
                ];

                $composition->section()->createQuietly($sectionData);
            }
        }
    }


    private function returnDoseAndRate($doseRates): array
    {
        $doseRateData = [];

        if (!empty($doseRates)) {
            foreach ($doseRates as $dr) {
                $doseRateData[] = [
                    'type' => returnAttribute($dr, ['type', 'coding', 0, 'code']),
                    'dose' => returnVariableAttribute($dr, ['doseRange', 'doseQuantity']),
                    'rate' => returnVariableAttribute($dr, ['rateRatio', 'rateRange', 'rateQuantity']),
                ];
            }
        }

        return $doseRateData;
    }


    private function returnDosage($dosageData): array
    {
        $dosage = null;
        if (!empty($dosageData)) {
            $dosage = [
                'sequence' => returnAttribute($dosageData, ['sequence']),
                'text' => returnAttribute($dosageData, ['text']),
                'additional_instruction' => $this->returnMultiCodeableConcept(returnAttribute($dosageData, ['additionalInstruction'])),
                'patient_instruction' => returnAttribute($dosageData, ['patientInstruction']),
                'timing_event' => returnAttribute($dosageData, ['timing', 'event']),
                'timing_repeat' => returnAttribute($dosageData, ['timing', 'repeat']),
                'timing_code' => returnAttribute($dosageData, ['timing', 'code', 'coding', 0, 'code']),
                'site' => returnAttribute($dosageData, ['site', 'coding', 0, 'code']),
                'route' => returnAttribute($dosageData, ['route', 'coding', 0, 'code']),
                'method' => returnAttribute($dosageData, ['method', 'coding', 0, 'code']),
                'max_dose_per_period_numerator_value' => returnAttribute($dosageData, ['maxDosePerPeriod', 'numerator', 'value']),
                'max_dose_per_period_numerator_comparator' => returnAttribute($dosageData, ['maxDosePerPeriod', 'numerator', 'comparator']),
                'max_dose_per_period_numerator_unit' => returnAttribute($dosageData, ['maxDosePerPeriod', 'numerator', 'unit']),
                'max_dose_per_period_numerator_system' => returnAttribute($dosageData, ['maxDosePerPeriod', 'numerator', 'system']),
                'max_dose_per_period_numerator_code' => returnAttribute($dosageData, ['maxDosePerPeriod', 'numerator', 'code']),
                'max_dose_per_period_denominator_value' => returnAttribute($dosageData, ['maxDosePerPeriod', 'denominator', 'value']),
                'max_dose_per_period_denominator_comparator' => returnAttribute($dosageData, ['maxDosePerPeriod', 'denominator', 'comparator']),
                'max_dose_per_period_denominator_unit' => returnAttribute($dosageData, ['maxDosePerPeriod', 'denominator', 'unit']),
                'max_dose_per_period_denominator_system' => returnAttribute($dosageData, ['maxDosePerPeriod', 'denominator', 'system']),
                'max_dose_per_period_denominator_code' => returnAttribute($dosageData, ['maxDosePerPeriod', 'denominator', 'code']),
                'max_dose_per_administration_value' => returnAttribute($dosageData, ['maxDosePerAdministration', 'value']),
                'max_dose_per_administration_unit' => returnAttribute($dosageData, ['maxDosePerAdministration', 'unit']),
                'max_dose_per_administration_system' => returnAttribute($dosageData, ['maxDosePerAdministration', 'system']),
                'max_dose_per_administration_code' => returnAttribute($dosageData, ['maxDosePerAdministration', 'code']),
                'max_dose_per_lifetime_value' => returnAttribute($dosageData, ['maxDosePerLifetime', 'value']),
                'max_dose_per_lifetime_unit' => returnAttribute($dosageData, ['maxDosePerLifetime', 'unit']),
                'max_dose_per_lifetime_system' => returnAttribute($dosageData, ['maxDosePerLifetime', 'system']),
                'max_dose_per_lifetime_code' => returnAttribute($dosageData, ['maxDosePerLifetime', 'code']),
            ];
        }

        return $dosage;
    }


    private function seedMedicationRequest($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $medicationRequestData = [
            'status' => returnAttribute($resourceContent, ['status']),
            'status_reason' => returnAttribute($resourceContent, ['statusReason', 'coding', 0, 'code']),
            'intent' => returnAttribute($resourceContent, ['intent']),
            'category' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['category'])),
            'priority' => returnAttribute($resourceContent, ['priority']),
            'do_not_perform' => returnAttribute($resourceContent, ['doNotPerform']),
            'reported' => returnAttribute($resourceContent, ['reportedBoolean']),
            'medication' => returnAttribute($resourceContent, ['medicationReference', 'reference']),
            'subject' => returnAttribute($resourceContent, ['subject', 'reference']),
            'encounter' => returnAttribute($resourceContent, ['encounter', 'reference']),
            'supporting_information' => $this->returnMultiReference(returnAttribute($resourceContent, ['supportingInformation'])),
            'authored_on' => returnAttribute($resourceContent, ['authoredOn']),
            'requester' => returnAttribute($resourceContent, ['requester', 'reference']),
            'performer' => returnAttribute($resourceContent, ['performer', 'reference']),
            'performer_type' => returnAttribute($resourceContent, ['performerType', 'coding', 0, 'code']),
            'recorder' => returnAttribute($resourceContent, ['recorder', 'reference']),
            'reason_code' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['reasonCode'])),
            'reason_reference' => $this->returnMultiReference(returnAttribute($resourceContent, ['reasonReference'])),
            'based_on' => $this->returnMultiReference(returnAttribute($resourceContent, ['basedOn'])),
            'course_of_therapy' => returnAttribute($resourceContent, ['courseOfTherapyType', 'coding', 0, 'code']),
            'insurance' => $this->returnMultiReference(returnAttribute($resourceContent, ['insurance'])),
            'dispense_interval_value' => returnAttribute($resourceContent, ['dispenseRequest', 'dispenseInterval', 'value']),
            'dispense_interval_comparator' => returnAttribute($resourceContent, ['dispenseRequest', 'dispenseInterval', 'comparator']),
            'dispense_interval_unit' => returnAttribute($resourceContent, ['dispenseRequest', 'dispenseInterval', 'unit']),
            'dispense_interval_system' => returnAttribute($resourceContent, ['dispenseRequest', 'dispenseInterval', 'system']),
            'dispense_interval_code' => returnAttribute($resourceContent, ['dispenseRequest', 'dispenseInterval', 'code']),
            'validity_period_start' => returnAttribute($resourceContent, ['dispenseRequest', 'validityPeriod', 'start']),
            'validity_period_end' => returnAttribute($resourceContent, ['dispenseRequest', 'validityPeriod', 'end']),
            'repeats_allowed' => returnAttribute($resourceContent, ['dispenseRequest', 'numberOfRepeatsAllowed']),
            'quantity_value' => returnAttribute($resourceContent, ['dispenseRequest', 'quantity', 'value']),
            'quantity_unit' => returnAttribute($resourceContent, ['dispenseRequest', 'quantity', 'unit']),
            'quantity_system' => returnAttribute($resourceContent, ['dispenseRequest', 'quantity', 'system']),
            'quantity_code' => returnAttribute($resourceContent, ['dispenseRequest', 'quantity', 'code']),
            'supply_duration_value' => returnAttribute($resourceContent, ['dispenseRequest', 'expectedSupplyDuration', 'value']),
            'supply_duration_comparator' => returnAttribute($resourceContent, ['dispenseRequest', 'expectedSupplyDuration', 'comparator']),
            'supply_duration_unit' => returnAttribute($resourceContent, ['dispenseRequest', 'expectedSupplyDuration', 'unit']),
            'supply_duration_system' => returnAttribute($resourceContent, ['dispenseRequest', 'expectedSupplyDuration', 'system']),
            'supply_duration_code' => returnAttribute($resourceContent, ['dispenseRequest', 'expectedSupplyDuration', 'code']),
            'dispense_performer' => returnAttribute($resourceContent, ['dispenseRequest', 'performer', 'reference']),
            'substitution_allowed' => returnVariableAttribute($resourceContent, MedicationRequest::SUBSTITUTION_ALLOWED['variableTypes']),
            'substitution_reason' => returnAttribute($resourceContent, ['substitution', 'reason', 'coding', 0, 'code']),
        ];

        $medicationRequestData = removeEmptyValues($medicationRequestData);

        $medicationRequest = $resource->medicationRequest()->createQuietly($medicationRequestData);
        $medicationRequest->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));
        $medicationRequest->note()->createManyQuietly($this->returnAnnotation(returnAttribute($resourceContent, ['note'])));

        $dosages = returnAttribute($resourceContent, ['dosageInstruction']);
        if (!empty($dosages)) {
            foreach ($dosages as $d) {
                $dosageData = $this->returnDosage($d);
                $dosage = $medicationRequest->dosage()->createQuietly($dosageData);
                $dosage->doseRate()->createManyQuietly($this->returnDoseAndRate(returnAttribute($d, ['doseAndRate'])));
            }
        }
    }


    private function seedMedication($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);
        $extension = returnAttribute($resourceContent, ['extension']);
        $medicationType = null;
        if (!empty($extension)) {
            foreach ($extension as $e) {
                if ($e['url'] == "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType") {
                    $medicationType = returnAttribute($e, ['valueCodeableConcept', 'coding', 0, 'code']);
                }
            }
        }

        $medicationData = [
            'system' => returnAttribute($resourceContent, ['code', 'coding', 0, 'system']),
            'code' => returnAttribute($resourceContent, ['code', 'coding', 0, 'code']),
            'display' => returnAttribute($resourceContent, ['code', 'coding', 0, 'display']),
            'status' => returnAttribute($resourceContent, ['status']),
            'manufacturer' => returnAttribute($resourceContent, ['manufacturer', 'reference']),
            'form' => returnAttribute($resourceContent, ['form', 'coding', 0, 'code']),
            'amount_numerator_value' => returnAttribute($resourceContent, ['amount', 'numerator', 'value']),
            'amount_numerator_comparator' => returnAttribute($resourceContent, ['amount', 'numerator', 'comparator']),
            'amount_numerator_unit' => returnAttribute($resourceContent, ['amount', 'numerator', 'unit']),
            'amount_numerator_system' => returnAttribute($resourceContent, ['amount', 'numerator', 'system']),
            'amount_numerator_code' => returnAttribute($resourceContent, ['amount', 'numerator', 'code']),
            'amount_denominator_value' => returnAttribute($resourceContent, ['amount', 'denominator', 'value']),
            'amount_denominator_comparator' => returnAttribute($resourceContent, ['amount', 'denominator', 'comparator']),
            'amount_denominator_unit' => returnAttribute($resourceContent, ['amount', 'denominator', 'unit']),
            'amount_denominator_system' => returnAttribute($resourceContent, ['amount', 'denominator', 'system']),
            'amount_denominator_code' => returnAttribute($resourceContent, ['amount', 'denominator', 'code']),
            'batch_lot_number' => returnAttribute($resourceContent, ['batch', 'lotNumber']),
            'batch_expiration_date' => returnAttribute($resourceContent, ['batch', 'expirationDate']),
            'medication_type' => $medicationType
        ];

        $medication = $resource->medication()->createQuietly($medicationData);
        $medication->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));

        $ingredients = returnAttribute($resourceContent, ['ingredient']);

        if (!empty($ingredients)) {
            foreach ($ingredients as $i) {
                $ingredientData = [
                    'system' => returnAttribute($i, ['itemCodeableConcept', 'coding', 0, 'system']),
                    'code' => returnAttribute($i, ['itemCodeableConcept', 'coding', 0, 'code']),
                    'display' => returnAttribute($i, ['itemCodeableConcept', 'coding', 0, 'display']),
                    'is_active' => returnAttribute($i, ['isActive']),
                    'strength_numerator_value' => returnAttribute($i, ['strength', 'numerator', 'value']),
                    'strength_numerator_comparator' => returnAttribute($i, ['strength', 'numerator', 'comparator']),
                    'strength_numerator_unit' => returnAttribute($i, ['strength', 'numerator', 'unit']),
                    'strength_numerator_system' => returnAttribute($i, ['strength', 'numerator', 'system']),
                    'strength_numerator_code' => returnAttribute($i, ['strength', 'numerator', 'code']),
                    'strength_denominator_value' => returnAttribute($i, ['strength', 'denominator', 'value']),
                    'strength_denominator_comparator' => returnAttribute($i, ['strength', 'denominator', 'comparator']),
                    'strength_denominator_unit' => returnAttribute($i, ['strength', 'denominator', 'unit']),
                    'strength_denominator_system' => returnAttribute($i, ['strength', 'denominator', 'system']),
                    'strength_denominator_code' => returnAttribute($i, ['strength', 'denominator', 'code']),
                ];

                $medication->ingredient()->createQuietly($ingredientData);
            }
        }
    }


    private function seedProcedure($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $procedureData = [
            'based_on' => $this->returnMultiReference(returnAttribute($resourceContent, ['basedOn'])),
            'part_of' => $this->returnMultiReference(returnAttribute($resourceContent, ['partOf'])),
            'status' => returnAttribute($resourceContent, ['status']),
            'status_reason' => returnAttribute($resourceContent, ['statusReason', 'coding', 0, 'code']),
            'category' => returnAttribute($resourceContent, ['category', 'coding', 0, 'code']),
            'code_system' => returnAttribute($resourceContent, ['code', 'coding', 0, 'system']),
            'code_code' => returnAttribute($resourceContent, ['code', 'coding', 0, 'code']),
            'code_display' => returnAttribute($resourceContent, ['code', 'coding', 0, 'display']),
            'subject' => returnAttribute($resourceContent, ['subject', 'reference']),
            'encounter' => returnAttribute($resourceContent, ['encounter', 'reference']),
            'performed' => returnVariableAttribute($resourceContent, Procedure::PERFORMED['variableTypes']),
            'recorder' => returnAttribute($resourceContent, ['recorder', 'reference']),
            'asserter' => returnAttribute($resourceContent, ['asserter', 'reference']),
            'location' => returnAttribute($resourceContent, ['location', 'reference']),
            'reason_code' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['reasonCode'])),
            'reason_reference' => $this->returnMultiReference(returnAttribute($resourceContent, ['reasonReference'])),
            'body_site' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['bodySite'])),
            'outcome' => returnAttribute($resourceContent, ['outcome', 'coding', 0, 'code']),
            'report' => $this->returnMultiReference(returnAttribute($resourceContent, ['report'])),
            'complication' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['complication'])),
            'complication_detail' => $this->returnMultiReference(returnAttribute($resourceContent, ['complicationDetail'])),
            'follow_up' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['followUp'])),
            'used_reference' => $this->returnMultiReference(returnAttribute($resourceContent, ['usedReference'])),
            'used_code' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['usedCode'])),
        ];

        $procedure = $resource->procedure()->createQuietly($procedureData);
        $procedure->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));

        $performers = returnAttribute($resourceContent, ['performer']);
        if (!empty($performers)) {
            foreach ($performers as $p) {
                $performerData = [
                    'function' => returnAttribute($p, ['function', 'coding', 0, 'code']),
                    'actor' => returnAttribute($p, ['actor', 'reference']),
                    'on_behalf_of' => returnAttribute($p, ['onBehalfOf', 'reference'])
                ];

                $procedure->performer()->createQuietly($performerData);
            }
        }

        $procedure->note()->createManyQuietly($this->returnAnnotation(returnAttribute($resourceContent, ['note'])));

        $focalDevices = returnAttribute($resourceContent, ['focalDevice']);
        if (!empty($focalDevices)) {
            foreach ($focalDevices as $fd) {
                $deviceData = [
                    'action' => returnAttribute($fd, ['action', 'coding', 0, 'code']),
                    'manipulated' => returnAttribute($fd, ['manipulated', 'reference'])
                ];

                $procedure->focalDevice()->createQuietly($deviceData);
            }
        }
    }


    private function seedObservation($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $observationData = [
            'based_on' => $this->returnMultiReference(returnAttribute($resourceContent, ['basedOn'])),
            'part_of' => $this->returnMultiReference(returnAttribute($resourceContent, ['partOf'])),
            'status' => returnAttribute($resourceContent, ['status']),
            'category' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['category'])),
            'code' => returnAttribute($resourceContent, ['code', 'coding', 0, 'code']),
            'subject' => returnAttribute($resourceContent, ['subject', 'reference']),
            'focus' => $this->returnMultiReference(returnAttribute($resourceContent, ['focus'])),
            'encounter' => returnAttribute($resourceContent, ['encounter', 'reference']),
            'effective' => returnVariableAttribute($resourceContent, Observation::EFFECTIVE['variableTypes']),
            'issued' => returnAttribute($resourceContent, ['issued']),
            'performer' => $this->returnMultiReference(returnAttribute($resourceContent, ['performer'])),
            'value' => returnVariableAttribute($resourceContent, Observation::VALUE['variableTypes']),
            'data_absent_reason' => returnAttribute($resourceContent, ['dataAbsentReason', 'coding', 0, 'code']),
            'interpretation' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['interpretation'])),
            'body_site' => returnAttribute($resourceContent, ['bodySite', 'coding', 0, 'code']),
            'method' => returnAttribute($resourceContent, ['method', 'coding', 0, 'code']),
            'specimen' => returnAttribute($resourceContent, ['specimen', 'reference']),
            'device' => returnAttribute($resourceContent, ['device', 'reference']),
            'has_member' => $this->returnMultiReference(returnAttribute($resourceContent, ['hasMember'])),
            'derived_from' => $this->returnMultiReference(returnAttribute($resourceContent, ['derivedFrom'])),
        ];

        $observation = $resource->observation()->createQuietly($observationData);
        $observation->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));
        $observation->note()->createManyQuietly($this->returnAnnotation(returnAttribute($resourceContent, ['note'])));

        $refRanges = returnAttribute($resourceContent, ['referenceRange']);
        if (!empty($refRanges)) {
            foreach ($refRanges as $rr) {
                $refRangeData = [
                    'low_value' => returnAttribute($rr, ['low', 'value']),
                    'low_unit' => returnAttribute($rr, ['low', 'unit']),
                    'low_system' => returnAttribute($rr, ['low', 'system']),
                    'low_code' => returnAttribute($rr, ['low', 'code']),
                    'high_value' => returnAttribute($rr, ['high', 'value']),
                    'high_unit' => returnAttribute($rr, ['high', 'unit']),
                    'high_system' => returnAttribute($rr, ['high', 'system']),
                    'high_code' => returnAttribute($rr, ['high', 'code']),
                    'type' => returnAttribute($rr, ['type', 'coding', 0, 'code']),
                    'applies_to' => $this->returnMultiCodeableConcept(returnAttribute($rr, ['appliesTo'])),
                    'age_low' => returnAttribute($rr, ['age', 'low', 'value']),
                    'age_high' => returnAttribute($rr, ['age', 'high', 'value']),
                    'text' => returnAttribute($rr, ['text']),
                ];

                $observation->referenceRange()->createQuietly($refRangeData);
            }
        }

        $components = returnAttribute($resourceContent, ['component']);
        if (!empty($components)) {
            foreach ($components as $c) {
                $componentData = [
                    'code' => returnAttribute($c, ['code', 'coding', 0, 'code']),
                    'value' => returnVariableAttribute($c, ObservationComponent::VALUE['variableTypes']),
                    'data_absent_reason' => returnAttribute($c, ['dataAbsentReason', 'coding', 0, 'code']),
                    'interpretation' => $this->returnMultiCodeableConcept(returnAttribute($c, ['interpretation'])),
                ];

                $component = $observation->component()->createQuietly($componentData);

                $refRanges = returnAttribute($c, ['referenceRange']);
                if (!empty($refRanges)) {
                    foreach ($refRanges as $rr) {
                        $refRangeData = [
                            'low_value' => returnAttribute($rr, ['low', 'value']),
                            'low_unit' => returnAttribute($rr, ['low', 'unit']),
                            'low_system' => returnAttribute($rr, ['low', 'system']),
                            'low_code' => returnAttribute($rr, ['low', 'code']),
                            'high_value' => returnAttribute($rr, ['high', 'value']),
                            'high_unit' => returnAttribute($rr, ['high', 'unit']),
                            'high_system' => returnAttribute($rr, ['high', 'system']),
                            'high_code' => returnAttribute($rr, ['high', 'code']),
                            'type' => returnAttribute($rr, ['type', 'coding', 0, 'code']),
                            'applies_to' => $this->returnMultiCodeableConcept(returnAttribute($rr, ['appliesTo'])),
                            'age_low' => returnAttribute($rr, ['age', 'low', 'value']),
                            'age_high' => returnAttribute($rr, ['age', 'high', 'value']),
                            'text' => returnAttribute($rr, ['text']),
                        ];

                        $component->referenceRange()->createQuietly($refRangeData);
                    }
                }
            }
        }
    }


    private function seedCondition($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $conditionData = [
            'clinical_status' => returnAttribute($resourceContent, ['clinicalStatus', 'coding', 0, 'code']),
            'verification_status' => returnAttribute($resourceContent, ['verificationStatus', 'coding', 0, 'code']),
            'category' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['category'])),
            'severity' => returnAttribute($resourceContent, ['severity', 'coding', 0, 'code']),
            'code_system' => returnAttribute($resourceContent, ['code', 'coding', 0, 'system']),
            'code_code' => returnAttribute($resourceContent, ['code', 'coding', 0, 'code']),
            'code_display' => returnAttribute($resourceContent, ['code', 'coding', 0, 'display']),
            'body_site' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['bodySite'])),
            'subject' => returnAttribute($resourceContent, ['subject', 'reference']),
            'encounter' => returnAttribute($resourceContent, ['encounter', 'reference']),
            'onset' => returnVariableAttribute($resourceContent, Condition::ONSET),
            'abatement' => returnVariableAttribute($resourceContent, Condition::ABATEMENT),
            'recorded_date' => returnAttribute($resourceContent, ['recordedDate']),
            'recorder' => returnAttribute($resourceContent, ['recorder', 'reference']),
            'asserter' => returnAttribute($resourceContent, ['asserter', 'reference'])
        ];

        $condition = $resource->condition()->createQuietly($conditionData);
        $condition->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));

        $stageData = returnAttribute($resourceContent, ['stage']);
        if (!empty($stageData)) {
            foreach ($stageData as $s) {
                $stage = [
                    'summary' => returnAttribute($s, ['summary', 'coding', 0, 'code']),
                    'assessment' => $this->returnMultiReference(returnAttribute($s, ['assessment'])),
                    'type' => returnAttribute($s, ['type', 'coding', 0, 'code'])
                ];
                $condition->stage()->createQuietly($stage);
            }
        }

        $evidenceData = returnAttribute($resourceContent, ['evidence']);
        if (!empty($evidenceData)) {
            foreach ($evidenceData as $e) {
                $evidence = [
                    'code' => $this->returnMultiCodeableConcept(returnAttribute($e, ['code'])),
                    'detail' => $this->returnMultiReference(returnAttribute($e, ['detail']))
                ];
                $condition->evidence()->createQuietly($evidence);
            }
        }

        $condition->note()->createManyQuietly($this->returnAnnotation(returnAttribute($resourceContent, ['note'])));
    }


    private function seedEncounter($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $encounterData = [
            'status' => returnAttribute($resourceContent, ['status']),
            'class' => returnAttribute($resourceContent, ['class', 'code']),
            'type' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['type'])),
            'service_type' => returnAttribute($resourceContent, ['serviceType', 'coding', 0, 'code']),
            'priority' => returnAttribute($resourceContent, ['priority', 'coding', 0, 'code']),
            'subject' => returnAttribute($resourceContent, ['subject', 'reference']),
            'episode_of_care' => $this->returnMultiReference(returnAttribute($resourceContent, ['episodeOfCare'])),
            'based_on' => $this->returnMultiReference(returnAttribute($resourceContent, ['basedOn'])),
            'period_start' => returnAttribute($resourceContent, ['period', 'start']),
            'period_end' => returnAttribute($resourceContent, ['period', 'end']),
            'length_value' => returnAttribute($resourceContent, ['length', 'value']),
            'length_comparator' => returnAttribute($resourceContent, ['length', 'comparator']),
            'length_unit' => returnAttribute($resourceContent, ['length', 'unit']),
            'length_system' => returnAttribute($resourceContent, ['length', 'system']),
            'length_code' => returnAttribute($resourceContent, ['length', 'code']),
            'reason_code' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['reasonCode'])),
            'reason_reference' => $this->returnMultiReference(returnAttribute($resourceContent, ['reasonReference'])),
            'account' => $this->returnMultiReference(returnAttribute($resourceContent, ['account'])),
            'hospitalization_preadmission_identifier_system' => returnAttribute($resourceContent, ['hospitalization', 'preAdmissionIdentifier', 'system']),
            'hospitalization_preadmission_identifier_use' => returnAttribute($resourceContent, ['hospitalization', 'preAdmissionIdentifier', 'use']),
            'hospitalization_preadmission_identifier_value' => returnAttribute($resourceContent, ['hospitalization', 'preAdmissionIdentifier', 'value']),
            'hospitalization_origin' => returnAttribute($resourceContent, ['hospitalization', 'origin', 'reference']),
            'hospitalization_admit_source' => returnAttribute($resourceContent, ['hospitalization', 'admitSource', 'coding', 0, 'code']),
            'hospitalization_re_admission' => returnAttribute($resourceContent, ['hospitalization', 'reAdmission', 'coding', 0, 'code']),
            'hospitalization_diet_preference' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['hospitalization', 'dietPreference'])),
            'hospitalization_special_arrangement' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['hospitalization', 'specialArrangement'])),
            'hospitalization_destination' => returnAttribute($resourceContent, ['hospitalization', 'destination', 'reference']),
            'hospitalization_discharge_disposition' => returnAttribute($resourceContent, ['hospitalization', 'dischargeDisposition', 'coding', 0, 'code']),
            'service_provider' => returnAttribute($resourceContent, ['serviceProvider', 'reference']),
            'part_of' => returnAttribute($resourceContent, ['partOf', 'reference'])
        ];

        $encounter = $resource->encounter()->createQuietly($encounterData);
        $encounter->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));

        $statusHistories = returnAttribute($resourceContent, ['statusHistory']);
        if (!empty($statusHistories)) {
            foreach ($statusHistories as $sh) {
                $statusHistoryData = [
                    'status' => returnAttribute($sh, ['status']),
                    'period_start' => returnAttribute($sh, ['period', 'start']),
                    'period_end' => returnAttribute($sh, ['period', 'end'])
                ];
                $encounter->statusHistory()->createQuietly($statusHistoryData);
            }
        }

        $classHistories = returnAttribute($resourceContent, ['classHistory']);
        if (!empty($classHistories)) {
            foreach ($classHistories as $ch) {
                $classHistory = [
                    'status' => returnAttribute($ch, ['status']),
                    'period_start' => returnAttribute($ch, ['period', 'start']),
                    'period_end' => returnAttribute($ch, ['period', 'end'])
                ];
                $encounter->classHistory()->createQuietly($classHistory);
            }
        }

        $participants = returnAttribute($resourceContent, ['participant']);
        if (!empty($participants)) {
            foreach ($participants as $p) {
                $participant = [
                    'type' => $this->returnMultiCodeableConcept(returnAttribute($p, ['type'])),
                    'individual' => returnAttribute($p, ['individual', 'reference'])
                ];
                $encounter->participant()->createQuietly($participant);
            }
        }

        $diagnoses = returnAttribute($resourceContent, ['diagnosis']);
        if (!empty($diagnoses)) {
            foreach ($diagnoses as $d) {
                $diagnosis = [
                    'condition' => returnAttribute($d, ['condition', 'reference']),
                    'use' => returnAttribute($d, ['use', 'coding', 0, 'code']),
                    'rank' => returnAttribute($d, ['rank']),
                ];
                $encounter->diagnosis()->createQuietly($diagnosis);
            }
        }

        $locations = returnAttribute($resourceContent, ['location']);
        if (!empty($locations)) {
            foreach ($locations as $l) {
                $location = [
                    'location' => returnAttribute($l, ['location', 'reference']),
                ];
                $encounter->location()->createQuietly($location);
            }
        }
    }


    private function seedQuestionnaireResponse($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);
        $items = returnAttribute($resourceContent, ['item']);

        $questionnaireData = [
            'identifier_system' => returnAttribute($resourceContent, ['identifier', 'system']),
            'identifier_use' => returnAttribute($resourceContent, ['identifier', 'use']),
            'identifier_value' => returnAttribute($resourceContent, ['identifier', 'value']),
            'based_on' => $this->returnMultiReference(returnAttribute($resourceContent, ['basedOn'])),
            'part_of' => $this->returnMultiReference(returnAttribute($resourceContent, ['partOf'])),
            'questionnaire' => returnAttribute($resourceContent, ['questionnaire']),
            'status' => returnAttribute($resourceContent, ['status']),
            'subject' => returnAttribute($resourceContent, ['subject', 'reference']),
            'encounter' => returnAttribute($resourceContent, ['encounter', 'reference']),
            'authored' => returnAttribute($resourceContent, ['authored']),
            'author' => returnAttribute($resourceContent, ['author', 'reference']),
            'source' => returnAttribute($resourceContent, ['source', 'reference']),
        ];

        $questionnaireResponse = $resource->questionnaireResponse()->createQuietly($questionnaireData);

        if (!empty($items)) {
            foreach ($items as $i) {
                $itemData = [
                    'link_id' => returnAttribute($i, ['linkId']),
                    'definition' => returnAttribute($i, ['definition']),
                    'text' => returnAttribute($i, ['text']),
                    'answer' => returnAttribute($i, ['answer']),
                    'item' => returnAttribute($i, ['item'])
                ];

                $questionnaireResponse->item()->createQuietly($itemData);
            }
        }
    }


    private function seedPatient($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);
        $name = returnHumanName(returnAttribute($resourceContent, ['name', 0]));
        $extension = returnAttribute($resourceContent, ['extension']);
        $birthPlace = $this->returnBirthPlace($extension);
        $contacts = returnAttribute($resourceContent, ['contact']);

        $patientData = [
            'active' => returnAttribute($resourceContent, ['active']),
            'name' => $name['name'],
            'prefix' => $name['prefix'],
            'suffix' => $name['suffix'],
            'gender' => returnAttribute($resourceContent, ['gender'], 'unknown'),
            'birth_date' => returnAttribute($resourceContent, ['birthDate']),
            'deceased' => returnVariableAttribute($resourceContent, ['deceasedBoolean', 'deceasedDateTime']),
            'marital_status' => returnAttribute($resourceContent, ['maritalStatus', 'coding', 0, 'code']),
            'multiple_birth' => returnVariableAttribute($resourceContent, ['multipleBirthBoolean', 'multipleBirthInteger']),
            'communication' => $this->returnCommunication(returnAttribute($resourceContent, ['communication'])),
            'general_practitioner' => $this->returnMultiReference(returnAttribute($resourceContent, ['generalPractitioner'])),
            'managing_organization' => returnAttribute($resourceContent, ['managingOrganization', 'reference']),
            'link' => $this->returnLink(returnAttribute($resourceContent, ['link'])),
            'birth_city' => $birthPlace['city'],
            'birth_country' => $birthPlace['country']
        ];

        $patient = $resource->patient()->createQuietly($patientData);
        $patient->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));
        $patient->telecom()->createManyQuietly($this->returnTelecom(returnAttribute($resourceContent, ['telecom'])));
        $patient->address()->createManyQuietly($this->returnAddress(returnAttribute($resourceContent, ['address'])));

        if (!empty($contacts)) {
            foreach ($contacts as $c) {
                $contactName = returnHumanName(returnAttribute($c, ['name']));
                $addressExtension = $this->returnAdministrativeAddress(returnAttribute($c, ['address', 'extension']));

                $contactData = merge_array(
                    [
                        'relationship' => $this->returnMultiCodeableConcept(returnAttribute($c, ['relationship'])),
                        'name' => $contactName['name'],
                        'prefix' => $contactName['prefix'],
                        'suffix' => $contactName['suffix'],
                        'gender' => returnAttribute($c, ['gender'], 'unknown'),
                        'address_use' => returnAttribute($c, ['address', 'use']),
                        'address_line' => returnAttribute($c, ['address', 'line']),
                        'country' => returnAttribute($c, ['address', 'country']),
                        'postal_code' => returnAttribute($c, ['address', 'postalCode']),
                    ],
                    $addressExtension
                );

                $contact = $patient->contact()->createQuietly($contactData);
                $contact->telecom()->createManyQuietly($this->returnTelecom(returnAttribute($c, ['telecom'])));
            }
        }
    }


    private function returnBirthPlace($extensions): array
    {
        $birthPlace = [
            'city' => null,
            'country' => null
        ];

        if (!empty($extensions)) {
            foreach ($extensions as $e) {
                if ($e['url'] == "https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace") {
                    $birthPlace = [
                        'city' => returnAttribute($e, ['valueAddress', 'city']),
                        'country' => returnAttribute($e, ['valueAddress', 'country'])
                    ];
                }
            }
        }

        return $birthPlace;
    }


    private function returnLink($links): array
    {
        $link = [];

        if (!empty($links)) {
            foreach ($links as $l) {
                $link[] = [
                    'other' => returnAttribute($l, ['other', 'reference']),
                    'type' => returnAttribute($l, ['type'])
                ];
            }
        }

        return $link;
    }


    private function returnCommunication($communications): array
    {
        $comms = [];

        if (!empty($communications)) {
            foreach ($communications as $c) {
                $comms[] = returnAttribute($c, ['coding', 0, 'code']);
            }
        }

        return $comms;
    }


    private function seedPractitioner($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);
        $identifiers = returnAttribute($resourceContent, ['identifier']);
        $name = returnHumanName(returnAttribute($resourceContent, ['name']));

        $practitionerData = [
            'nik' => $this->returnNik($identifiers),
            'nakes_id' => $this->returnNakesId($identifiers),
            'active' => returnAttribute($resourceContent, ['active']),
            'name' => $name['name'],
            'prefix' => $name['prefix'],
            'suffix' => $name['suffix'],
            'gender' => returnAttribute($resourceContent, ['gender'], 'unknown'),
            'birth_date' => returnAttribute($resourceContent, ['birthDate']),
            'communication' => returnAttribute($resourceContent, ['communication'])
        ];

        $practitioner = $resource->practitioner()->createQuietly($practitionerData);
        $practitioner->telecom()->createManyQuietly($this->returnTelecom(returnAttribute($resourceContent, ['telecom'])));
        $practitioner->address()->createManyQuietly($this->returnAddress(returnAttribute($resourceContent, ['address'])));
        $practitioner->qualification()->createManyQuietly($this->returnQualification(returnAttribute($resourceContent, ['qualification'])));
    }


    private function returnQualification($qualifications): array
    {
        $qualification = [];

        if (!empty($qualifications)) {
            foreach ($qualifications as $q) {
                $qualification[] = [
                    'identifier' => returnAttribute($q, ['identifier']),
                    'code' => returnAttribute($q, ['code']),
                    'period_start' => returnAttribute($q, ['period', 'start']),
                    'period_end' => returnAttribute($q, ['period', 'end']),
                    'issuer' => returnAttribute($q, ['issuer', 'reference'])
                ];
            }
        }

        return $qualification;
    }


    private function returnNik($identifiers)
    {
        if (!empty($identifiers)) {
            foreach ($identifiers as $i) {
                if ($i['system'] == Constants::NIK_SYSTEM) {
                    return $i['value'];
                }
            }
        } else {
            return null;
        }
    }


    private function returnNakesId($identifiers)
    {
        if (!empty($identifiers)) {
            foreach ($identifiers as $i) {
                if ($i['system'] == Constants::NAKES_SYSTEM) {
                    return $i['value'];
                }
            }
        } else {
            return null;
        }
    }


    private function returnAdministrativeAddress($addressExtension): array
    {
        $addressDetails = [];

        if (!empty($addressExtension)) {
            foreach ($addressExtension as $extension) {
                $url = $extension['url'];
                $value = (int)preg_replace("/[^0-9]/", "", $extension['valueCode']);
                $addressDetails[$url] = $value;
            }
        }

        return $addressDetails;
    }


    private function seedLocation($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $addressDetails = [];
        $extensionData = returnAttribute($resourceContent, ['address', 'extension', 0, 'extension'], null);

        if (!empty($extensionData)) {
            foreach ($extensionData as $extension) {
                $url = $extension['url'];
                $value = (int)preg_replace("/[^0-9]/", "", $extension['valueCode']);
                $addressDetails[$url] = $value;
            }
        }

        $locationData = array_merge(
            [
                'status' => returnAttribute($resourceContent, ['status']),
                'operational_status' => returnAttribute($resourceContent, ['operationalStatus', 'code']),
                'name' => returnAttribute($resourceContent, ['name'], 'unknown'),
                'alias' => returnAttribute($resourceContent, ['alias']),
                'description' => returnAttribute($resourceContent, ['description']),
                'mode' => returnAttribute($resourceContent, ['mode']),
                'type' => $this->returnLocationType(returnAttribute($resourceContent, ['type'])),
                'address_use' => returnAttribute($resourceContent, ['address', 'use']),
                'address_line' => returnAttribute($resourceContent, ['address', 'line']),
                'country' => returnAttribute($resourceContent, ['address', 'country']),
                'postal_code' => returnAttribute($resourceContent, ['address', 'postalCode']),
                'physical_type' => returnAttribute($resourceContent, ['physicalType', 'coding', 0, 'code']),
                'longitude' => returnAttribute($resourceContent, ['position', 'longitude']),
                'latitude' => returnAttribute($resourceContent, ['position', 'latitude']),
                'altitude' => returnAttribute($resourceContent, ['position', 'altitude']),
                'managing_organization' => returnAttribute($resourceContent, ['managingOrganization', 'reference']),
                'part_of' => returnAttribute($resourceContent, ['partOf', 'reference']),
                'availability_exceptions' => returnAttribute($resourceContent, ['availabilityExceptions']),
                'endpoint' => $this->returnMultiReference(returnAttribute($resourceContent, ['endpoint'])),
                'service_class' => $this->returnLocationServiceClass(returnAttribute($resourceContent, ['extension']))
            ],
            $addressDetails
        );

        $location = $resource->location()->createQuietly($locationData);
        $location->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));
        $location->telecom()->createManyQuietly($this->returnTelecom(returnAttribute($resourceContent, ['telecom'])));
        $location->operationHours()->createManyQuietly($this->returnOperationHours(returnAttribute($resourceContent, ['hoursOfOperation'])));
    }


    private function returnOperationHours($operationHours): array
    {
        $hour = [];

        if (!empty($operationHours)) {
            foreach ($operationHours as $o) {
                $hour[] = [
                    'days_of_week' => returnAttribute($o, ['daysOfWeek']),
                    'all_day' => returnAttribute($o, ['allDay']),
                    'opening_time' => returnAttribute($o, ['openingTime']),
                    'closing_time' => returnAttribute($o, ['closingTime'])
                ];
            }
        }

        return $hour;
    }


    private function returnLocationServiceClass($extension)
    {
        if (!empty($extension)) {
            foreach ($extension as $e) {
                if ($e['url'] == "https://fhir.kemkes.go.id/r4/StructureDefinition/LocationServiceClass") {
                    return returnAttribute($e, ['valueCodeableConcept', 'coding', 0, 'code']);
                } else {
                    return null;
                }
            }
        } else {
            return null;
        }
    }


    private function returnLocationType($types): array
    {
        $type = [];

        if (!empty($types)) {
            foreach ($types as $t) {
                $type[] = returnAttribute($t, ['coding', 0, 'code']);
            }
        }

        return $type;
    }


    private function seedOrganization($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);
        $contactData = returnAttribute($resourceContent, ['contact']);

        $organizationData = [
            'active' => returnAttribute($resourceContent, ['active'], false),
            'type' => $this->returnMultiCodeableConcept(returnAttribute($resourceContent, ['type'])),
            'name' => returnAttribute($resourceContent, ['name'], 'unknown'),
            'alias' => returnAttribute($resourceContent, ['alias']),
            'part_of' => returnAttribute($resourceContent, ['partOf', 'reference']),
            'endpoint' => $this->returnMultiReference(returnAttribute($resourceContent, ['endpoint'])),
        ];

        $organizationData = removeEmptyValues($organizationData);
        $organization = $resource->organization()->createQuietly($organizationData);
        $organization->identifier()->createManyQuietly($this->returnIdentifier(returnAttribute($resourceContent, ['identifier'])));
        $organization->telecom()->createManyQuietly($this->returnTelecom(returnAttribute($resourceContent, ['telecom'])));
        $organization->address()->createManyQuietly($this->returnAddress(returnAttribute($resourceContent, ['address'])));

        if (!empty($contactData)) {
            foreach ($contactData as $c) {
                $addressDetails = [];
                $extensionData = returnAttribute($c, ['address', 'extension', 0, 'extension'], null);

                if (!empty($extensionData)) {
                    foreach ($extensionData as $extension) {
                        $url = $extension['url'];
                        $value = (int)preg_replace("/[^0-9]/", "", $extension['valueCode']);
                        $addressDetails[$url] = $value;
                    }
                }

                $line = returnAttribute($c, ['address', 'line']) ? returnAttribute($c, ['address', 'line']) : returnAttribute($c, ['address', 'text'], '');

                $contactArray = array_merge(
                    $addressDetails,
                    [
                        'purpose' => returnAttribute($c, ['purpose', 'coding', 0, 'code']),
                        'name_use' => returnAttribute($c, ['name', 'use']),
                        'name_text' => returnAttribute($c, ['name', 'text']),
                        'address_use' => returnAttribute($c, ['address', 'use']),
                        'address_type' => returnAttribute($c, ['address', 'type']),
                        'address_line' => $line,
                        'country' => returnAttribute($c, ['address', 'country'], 'ID'),
                        'postal_code' => returnAttribute($c, ['address', 'postalCode']),
                    ],
                );
                $contact = $organization->contact()->createQuietly($contactArray);
                $contact->telecom()->createManyQuietly($this->returnTelecom(returnAttribute($c, ['telecom'])));
            }
        }
    }


    private function returnAddress($addresses): array
    {
        $address = [];
        $addressDetails = [];

        if (!empty($addresses)) {
            foreach ($addresses as $a) {
                $extensionData = returnAttribute($a, ['extension', 0, 'extension'], null);

                if (!empty($extensionData)) {
                    foreach ($extensionData as $extension) {
                        $url = $extension['url'];
                        $value = (int)preg_replace("/[^0-9]/", "", $extension['valueCode']);
                        $addressDetails[$url] = $value;
                    }
                }

                $line = returnAttribute($a, ['line']) ? returnAttribute($a, ['line']) : returnAttribute($a, ['text']);

                $address[] = array_merge(
                    $addressDetails,
                    [
                        'use' => returnAttribute($a, ['use']),
                        'line' => $line,
                        'country' => returnAttribute($a, ['country'], 'ID'),
                        'postal_code' => returnAttribute($a, ['postalCode']),
                    ],
                );
            }
        }

        return $address;
    }


    private function returnTelecom($telecoms): array
    {
        $telecom = [];

        if (!empty($telecoms)) {
            foreach ($telecoms as $t) {
                $telecom[] = [
                    'system' => returnAttribute($t, ['system']),
                    'use' => returnAttribute($t, ['use']),
                    'value' => returnAttribute($t, ['value'])
                ];
            }
        }

        return $telecom;
    }


    private function returnMultiCodeableConcept($codeableConcepts): array
    {
        $codeableConcept = [];

        if (!empty($codeableConcepts)) {
            foreach ($codeableConcepts as $cc) {
                $codeableConcept[] = returnAttribute($cc, ['coding', 0, 'code']);
            }
        }

        return $codeableConcept;
    }


    private function returnMultiReference($references): array
    {
        $reference = [];

        if (!empty($references)) {
            foreach ($references as $r) {
                $reference[] = returnAttribute($r, ['reference']);
            }
        }

        return $reference;
    }


    private function returnIdentifier(array $identifiers, string $prefix = null): array
    {
        $identifier = [];

        if (!empty($identifiers)) {
            foreach ($identifiers as $i) {
                $identifier[] = [
                    $prefix . 'system' => returnAttribute($i, ['system'], 'unknown'),
                    $prefix . 'use' => returnAttribute($i, ['use']),
                    $prefix . 'value' => returnAttribute($i, ['value'])
                ];
            }
        }
        return $identifier;
    }


    private function returnReference($references): array
    {
        $reference = [];

        if (!empty($references)) {
            foreach ($references as $r) {
                $reference[] = [
                    'reference' => returnAttribute($r, ['reference'])
                ];
            }
        }

        return $reference;
    }


    private function returnProcessing($processings): array
    {
        $processing = [];

        if (!empty($processings)) {
            foreach ($processings as $p) {
                $processing[] = [
                    'description' => returnAttribute($p, ['description']),
                    'procedure' => returnAttribute($p, ['procedure', 'coding', 0, 'code']),
                    'additive' => returnAttribute($p, ['additive']),
                    'time' => returnVariableAttribute($p, ['timeDateTime', 'timePeriod'])
                ];
            }
        }

        return $processing;
    }


    private function returnCodeableConcept($codeableConcepts): array
    {
        $codeableConcept = [];

        if (!empty($codeableConcepts)) {
            foreach ($codeableConcepts as $cc) {
                $codeableConcept[] = [
                    'system' => returnAttribute($cc, ['coding', 0, 'system']),
                    'code' => returnAttribute($cc, ['coding', 0, 'code']),
                    'display' => returnAttribute($cc, ['coding', 0, 'display'])
                ];
            }
        }

        return $codeableConcept;
    }


    private function returnAnnotation($annotations): array
    {
        $annotation = [];

        if (!empty($annotations)) {
            foreach ($annotations as $a) {
                $annotation[] = [
                    'author' => returnVariableAttribute($a, ['authorString', 'authorReference']),
                    'time' => returnAttribute($a, ['time']),
                    'text' => returnAttribute($a, ['text'])
                ];
            }
        }

        return $annotation;
    }
}
