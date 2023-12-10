<?php

namespace Database\Seeders;

use App\Models\Fhir\{
    AllergyIntolerance,
    ClinicalImpression,
    Condition,
    MedicationRequest,
    Observation,
    ObservationComponent,
    Patient,
    Procedure,
    QuestionnaireResponseItemAnswer,
    Resource,
    ServiceRequest
};
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
                case 'Organization':
                    $this->seedOrganization($res, $resText);
                    break;
                case 'Location':
                    $this->seedLocation($res, $resText);
                    break;
                case 'Practitioner':
                    $this->seedPractitioner($res, $resText);
                    break;
                case 'Patient':
                    $this->seedPatient($res, $resText);
                    break;
                case 'Encounter':
                    $this->seedEncounter($res, $resText);
                    break;
                case 'Condition':
                    $this->seedCondition($res, $resText);
                    break;
                case 'Observation':
                    $this->seedObservation($res, $resText);
                    break;
                case 'Procedure':
                    $this->seedProcedure($res, $resText);
                    break;
                case 'Medication':
                    $this->seedMedication($res, $resText);
                    break;
                case 'MedicationRequest':
                    $this->seedMedicationRequest($res, $resText);
                    break;
                case 'Composition':
                    $this->seedComposition($res, $resText);
                    break;
                case 'AllergyIntolerance':
                    $this->seedAllergyIntolerance($res, $resText);
                    break;
                case 'ClinicalImpression':
                    $this->seedClinicalImpression($res, $resText);
                    break;
                case 'ServiceRequest':
                    $this->seedServiceRequest($res, $resText);
                    break;
                case 'MedicationDispense':
                    $this->seedMedicationDispense($res, $resText);
                    break;
                case 'MedicationStatement':
                    // $this->seedMedicationStatement($res, $resText);  // Not yet implemented
                    break;
                case 'QuestionnaireResponse':
                    $this->seedQuestionnaireResponse($res, $resText);
                    break;
                default:
                    break;
            }
        }
    }


    private function seedMedicationDispense($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $medicationDispenseData = [
            'part_of' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['partOf'])),
            'status' => $this->returnAttribute($resourceContent, ['status']),
            'category' => $this->returnAttribute($resourceContent, ['category', 'coding', 0, 'code']),
            'medication' => $this->returnAttribute($resourceContent, ['medicationReference', 'reference']),
            'subject' => $this->returnAttribute($resourceContent, ['subject', 'reference']),
            'context' => $this->returnAttribute($resourceContent, ['context', 'reference']),
            'location' => $this->returnAttribute($resourceContent, ['location', 'reference']),
            'authorizing_prescription' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['authorizingPrescription'])),
            'quantity_value' => $this->returnAttribute($resourceContent, ['quantity', 'value']),
            'quantity_unit' => $this->returnAttribute($resourceContent, ['quantity', 'unit']),
            'quantity_system' => $this->returnAttribute($resourceContent, ['quantity', 'system']),
            'quantity_code' => $this->returnAttribute($resourceContent, ['quantity', 'code']),
            'days_supply_value' => $this->returnAttribute($resourceContent, ['daysSupply', 'value']),
            'days_supply_unit' => $this->returnAttribute($resourceContent, ['daysSupply', 'unit']),
            'days_supply_system' => $this->returnAttribute($resourceContent, ['daysSupply', 'system']),
            'days_supply_code' => $this->returnAttribute($resourceContent, ['daysSupply', 'code']),
            'when_prepared' => $this->returnAttribute($resourceContent, ['whenPrepared']),
            'when_handed_over' => $this->returnAttribute($resourceContent, ['whenHandedOver']),
            'substitution_was_substituted' => $this->returnAttribute($resourceContent, ['substitution', 'wasSubstituted']),
            'substitution_type' => $this->returnAttribute($resourceContent, ['substitution', 'type', 'coding', 0, 'code']),
            'substitution_reason' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['substitution', 'reason'])),
            'substitution_responsible_party' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['substitution', 'responsibleParty'])),
        ];

        $medicationDispenseData = $this->removeEmptyValues($medicationDispenseData);

        $medicationDispense = $resource->medicationDispense()->createQuietly($medicationDispenseData);
        $medicationDispense->identifier()->createManyQuietly($this->returnIdentifier($this->returnAttribute($resourceContent, ['identifier'])));

        $performers = $this->returnAttribute($resourceContent, ['performer']);
        if (!empty($performers)) {
            foreach ($performers as $p) {
                $performerData = [
                    'function' => $this->returnAttribute($p, ['function', 'coding', 0, 'code']),
                    'actor' => $this->returnAttribute($p, ['actor', 'reference']),
                ];

                $medicationDispense->performer()->createQuietly($performerData);
            }
        }

        $dosageInstructions = $this->returnAttribute($resourceContent, ['dosageInstruction']);
        if (!empty($dosageInstructions)) {
            foreach ($dosageInstructions as $di) {
                $dosageData = [
                    'sequence' => $this->returnAttribute($di, ['sequence']),
                    'text' => $this->returnAttribute($di, ['text']),
                    'additional_instruction' => $this->returnMultiCodeableConcept($this->returnAttribute($di, ['additionalInstruction'])),
                    'patient_instruction' => $this->returnAttribute($di, ['patientInstruction']),
                    'timing_event' => $this->returnAttribute($di, ['timing', 'event']),
                    'timing_repeat' => $this->returnAttribute($di, ['timing', 'repeat']),
                    'timing_code' => $this->returnAttribute($di, ['timing', 'code', 'coding', 0, 'code']),
                    'site' => $this->returnAttribute($di, ['site', 'coding', 0, 'code']),
                    'route' => $this->returnAttribute($di, ['route', 'coding', 0, 'code']),
                    'method' => $this->returnAttribute($di, ['method', 'coding', 0, 'code']),
                    'max_dose_per_period_numerator_value' => $this->returnAttribute($di, ['maxDosePerPeriod', 'numerator', 'value']),
                    'max_dose_per_period_numerator_comparator' => $this->returnAttribute($di, ['maxDosePerPeriod', 'numerator', 'comparator']),
                    'max_dose_per_period_numerator_unit' => $this->returnAttribute($di, ['maxDosePerPeriod', 'numerator', 'unit']),
                    'max_dose_per_period_numerator_system' => $this->returnAttribute($di, ['maxDosePerPeriod', 'numerator', 'system']),
                    'max_dose_per_period_numerator_code' => $this->returnAttribute($di, ['maxDosePerPeriod', 'numerator', 'code']),
                    'max_dose_per_period_denominator_value' => $this->returnAttribute($di, ['maxDosePerPeriod', 'denominator', 'value']),
                    'max_dose_per_period_denominator_comparator' => $this->returnAttribute($di, ['maxDosePerPeriod', 'denominator', 'comparator']),
                    'max_dose_per_period_denominator_unit' => $this->returnAttribute($di, ['maxDosePerPeriod', 'denominator', 'unit']),
                    'max_dose_per_period_denominator_system' => $this->returnAttribute($di, ['maxDosePerPeriod', 'denominator', 'system']),
                    'max_dose_per_period_denominator_code' => $this->returnAttribute($di, ['maxDosePerPeriod', 'denominator', 'code']),
                    'max_dose_per_administration_value' => $this->returnAttribute($di, ['maxDosePerAdministration', 'value']),
                    'max_dose_per_administration_unit' => $this->returnAttribute($di, ['maxDosePerAdministration', 'unit']),
                    'max_dose_per_administration_system' => $this->returnAttribute($di, ['maxDosePerAdministration', 'system']),
                    'max_dose_per_administration_code' => $this->returnAttribute($di, ['maxDosePerAdministration', 'code']),
                    'max_dose_per_lifetime_value' => $this->returnAttribute($di, ['maxDosePerLifetime', 'value']),
                    'max_dose_per_lifetime_unit' => $this->returnAttribute($di, ['maxDosePerLifetime', 'unit']),
                    'max_dose_per_lifetime_system' => $this->returnAttribute($di, ['maxDosePerLifetime', 'system']),
                    'max_dose_per_lifetime_code' => $this->returnAttribute($di, ['maxDosePerLifetime', 'code']),
                ];

                $dosageInstruction = $medicationDispense->dosageInstruction()->createQuietly($dosageData);

                $doseRates = $this->returnAttribute($di, ['doseAndRate']);
                if (!empty($doseRates)) {
                    foreach ($doseRates as $dr) {
                        $doseRateData = [
                            'type' => $this->returnAttribute($dr, ['type', 'coding', 0, 'code']),
                            'dose' => $this->returnVariableAttribute($dr, ['doseRange', 'doseQuantity']),
                            'rate' => $this->returnVariableAttribute($dr, ['rateRatio', 'rateRange', 'rateQuantity']),
                        ];

                        $dosageInstruction->doseRate()->createQuietly($doseRateData);
                    }
                }
            }
        }
    }


    private function seedServiceRequest($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $serviceRequestData = [
            'based_on' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['basedOn'])),
            'replaces' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['replaces'])),
            'requisition_system' => $this->returnAttribute($resourceContent, ['requisition', 'system']),
            'requisition_use' => $this->returnAttribute($resourceContent, ['requisition', 'use']),
            'requisition_value' => $this->returnAttribute($resourceContent, ['requisition', 'value']),
            'status' => $this->returnAttribute($resourceContent, ['status']),
            'intent' => $this->returnAttribute($resourceContent, ['intent']),
            'category' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['category'])),
            'priority' => $this->returnAttribute($resourceContent, ['priority']),
            'do_not_perform' => $this->returnAttribute($resourceContent, ['doNotPerform']),
            'code_system' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'system']),
            'code_code' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'code']),
            'code_display' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'display']),
            'order_detail' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['orderDetail'])),
            'quantity' => $this->returnVariableAttribute($resourceContent, ServiceRequest::QUANTITY['variableTypes']),
            'subject' => $this->returnAttribute($resourceContent, ['subject', 'reference']),
            'encounter' => $this->returnAttribute($resourceContent, ['encounter', 'reference']),
            'occurrence' => $this->returnVariableAttribute($resourceContent, ServiceRequest::OCCURRENCE['variableTypes']),
            'as_needed' => $this->returnVariableAttribute($resourceContent, ServiceRequest::AS_NEEDED['variableTypes']),
            'authored_on' => $this->returnAttribute($resourceContent, ['authoredOn']),
            'requester' => $this->returnAttribute($resourceContent, ['requester', 'reference']),
            'performer_type' => $this->returnAttribute($resourceContent, ['performerType', 'coding', 0, 'code']),
            'performer' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['performer'])),
            'location_code' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['locationCode'])),
            'location_reference' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['locationReference'])),
            'reason_code' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['reasonCode'])),
            'reason_reference' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['reasonReference'])),
            'insurance' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['insurance'])),
            'supporting_info' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['supportingInfo'])),
            'specimen' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['specimen'])),
            'body_site' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['bodySite'])),
            'patient_instruction' => $this->returnAttribute($resourceContent, ['patientInstruction']),
            'relevant_history' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['relevantHistory'])),
        ];

        $serviceRequestData = $this->removeEmptyValues($serviceRequestData);

        $serviceRequest = $resource->serviceRequest()->createQuietly($serviceRequestData);
        $serviceRequest->identifier()->createManyQuietly($this->returnIdentifier($this->returnAttribute($resourceContent, ['identifier'])));
        $serviceRequest->note()->createManyQuietly($this->returnAnnotation($this->returnAttribute($resourceContent, ['note'])));
    }


    private function seedClinicalImpression($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $clinicalImpressionData = [
            'status' => $this->returnAttribute($resourceContent, ['status']),
            'status_reason_code' => $this->returnAttribute($resourceContent, ['statusReason', 'coding', 0, 'code']),
            'status_reason_text' => $this->returnAttribute($resourceContent, ['statusReason', 'text']),
            'code_system' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'system']),
            'code_code' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'code']),
            'code_display' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'display']),
            'code_text' => $this->returnAttribute($resourceContent, ['code', 'text']),
            'description' => $this->returnAttribute($resourceContent, ['description']),
            'subject' => $this->returnAttribute($resourceContent, ['subject', 'reference']),
            'encounter' => $this->returnAttribute($resourceContent, ['encounter', 'reference']),
            'effective' => $this->returnVariableAttribute($resourceContent, ClinicalImpression::EFFECTIVE['variableTypes']),
            'date' => $this->returnAttribute($resourceContent, ['date']),
            'assessor' => $this->returnAttribute($resourceContent, ['assessor', 'reference']),
            'previous' => $this->returnAttribute($resourceContent, ['previous', 'reference']),
            'problem' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['problem'])),
            'protocol' => $this->returnAttribute($resourceContent, ['protocol']),
            'summary' => $this->returnAttribute($resourceContent, ['summary']),
            'prognosis_codeable_concept' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['prognosisCodeableConcept'])),
            'prognosis_reference' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['prognosisReference'])),
            'supporting_info' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['supportingInfo'])),
        ];

        $clinicalImpressionData = $this->removeEmptyValues($clinicalImpressionData);

        $clinicalImpression = $resource->clinicalImpression()->createQuietly($clinicalImpressionData);
        $clinicalImpression->identifier()->createManyQuietly($this->returnIdentifier($this->returnAttribute($resourceContent, ['identifier'])));

        $investigations = $this->returnAttribute($resourceContent, ['investigation']);
        if (!empty($investigations)) {
            foreach ($investigations as $i) {
                $investigationData = [
                    'code' => $this->returnAttribute($i, ['code', 'coding', 0, 'code']),
                    'code_text' => $this->returnAttribute($i, ['code', 'text']),
                    'item' => $this->returnMultiReference($this->returnAttribute($i, ['item'])),
                ];

                $clinicalImpression->investigation()->createQuietly($investigationData);
            }
        }

        $findings = $this->returnAttribute($resourceContent, ['finding']);
        if (!empty($findings)) {
            foreach ($findings as $f) {
                $findingData = [
                    'item_codeable_concept' => $this->returnAttribute($f, ['itemCodeableConcept', 'coding', 0, 'code']),
                    'item_reference' => $this->returnAttribute($f, ['itemReference', 'reference']),
                    'basis' => $this->returnAttribute($f, ['basis']),
                ];

                $clinicalImpression->finding()->createQuietly($findingData);
            }
        }

        $clinicalImpression->note()->createManyQuietly($this->returnAnnotation($this->returnAttribute($resourceContent, ['note'])));
    }


    private function seedAllergyIntolerance($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $allergyIntoleranceData = [
            'clinical_status' => $this->returnAttribute($resourceContent, ['clinicalStatus', 'coding', 0, 'code']),
            'verification_status' => $this->returnAttribute($resourceContent, ['verificationStatus', 'coding', 0, 'code']),
            'type' => $this->returnAttribute($resourceContent, ['type', 'coding', 0, 'code']),
            'category' => $this->returnAttribute($resourceContent, ['category']),
            'criticality' => $this->returnAttribute($resourceContent, ['criticality']),
            'code_system' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'system']),
            'code_code' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'code']),
            'code_display' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'display']),
            'patient' => $this->returnAttribute($resourceContent, ['patient', 'reference']),
            'encounter' => $this->returnAttribute($resourceContent, ['encounter', 'reference']),
            'onset' => $this->returnVariableAttribute($resourceContent, AllergyIntolerance::ONSET['variableTypes']),
            'recorded_date' => $this->returnAttribute($resourceContent, ['recordedDate']),
            'recorder' => $this->returnAttribute($resourceContent, ['recorder', 'reference']),
            'asserter' => $this->returnAttribute($resourceContent, ['asserter', 'reference']),
            'last_occurence' => $this->returnAttribute($resourceContent, ['lastOccurence']),
        ];

        $allergyIntoleranceData = $this->removeEmptyValues($allergyIntoleranceData);
        $allergyIntolerance = $resource->allergyIntolerance()->createQuietly($allergyIntoleranceData);

        $allergyIntolerance->identifier()->createManyQuietly($this->returnIdentifier($this->returnAttribute($resourceContent, ['identifier'])));
        $allergyIntolerance->note()->createManyQuietly($this->returnAnnotation($this->returnAttribute($resourceContent, ['note'])));

        $reactions = $this->returnAttribute($resourceContent, ['reaction']);
        if (!empty($reactions)) {
            foreach ($reactions as $r) {
                $reactionData = [
                    'substance_system' => $this->returnAttribute($r, ['substance', 'coding', 0, 'system']),
                    'substance_code' => $this->returnAttribute($r, ['substance', 'coding', 0, 'code']),
                    'substance_display' => $this->returnAttribute($r, ['substance', 'coding', 0, 'display']),
                    'manifestation' => $this->returnMultiCodeableConcept($this->returnAttribute($r, ['manifestation'])),
                    'description' => $this->returnAttribute($r, ['description']),
                    'onset' => $this->returnAttribute($r, ['onset']),
                    'severity' => $this->returnAttribute($r, ['severity']),
                    'exposure_route' => $this->returnAttribute($r, ['exposureRoute', 'coding', 0, 'code']),
                ];

                $reaction = $allergyIntolerance->reaction()->createQuietly($reactionData);
                $reaction->note()->createManyQuietly($this->returnAnnotation($this->returnAttribute($r, ['note'])));
            }
        }
    }


    private function seedComposition($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $compositionData = [
            'identifier_system' => $this->returnAttribute($resourceContent, ['identifier', 'system']),
            'identifier_use' => $this->returnAttribute($resourceContent, ['identifier', 'use']),
            'identifier_value' => $this->returnAttribute($resourceContent, ['identifier', 'value']),
            'status' => $this->returnAttribute($resourceContent, ['status']),
            'type_system' => $this->returnAttribute($resourceContent, ['type', 'coding', 0, 'system']),
            'type_code' => $this->returnAttribute($resourceContent, ['type', 'coding', 0, 'code']),
            'type_display' => $this->returnAttribute($resourceContent, ['type', 'coding', 0, 'display']),
            'category' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['category'])),
            'subject' => $this->returnAttribute($resourceContent, ['subject', 'reference']),
            'encounter' => $this->returnAttribute($resourceContent, ['encounter', 'reference']),
            'date' => $this->returnAttribute($resourceContent, ['date']),
            'author' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['author'])),
            'title' => $this->returnAttribute($resourceContent, ['title']),
            'confidentiality' => $this->returnAttribute($resourceContent, ['confidentiality']),
            'custodian' => $this->returnAttribute($resourceContent, ['custodian', 'reference']),
        ];

        $composition = $resource->composition()->createQuietly($compositionData);

        $attester = $this->returnAttribute($resourceContent, ['attester']);
        if (!empty($attester)) {
            foreach ($attester as $a) {
                $attesterData = [
                    'mode' => $this->returnAttribute($a, ['mode']),
                    'time' => $this->returnAttribute($a, ['time']),
                    'party' => $this->returnAttribute($a, ['party', 'reference']),
                ];

                $composition->attester()->createQuietly($attesterData);
            }
        }

        $relatesTo = $this->returnAttribute($resourceContent, ['relatesTo']);
        if (!empty($relatesTo)) {
            foreach ($relatesTo as $r) {
                $relatesToData = [
                    'code' => $this->returnAttribute($r, ['code']),
                    'target' => $this->returnVariableAttribute($r, ['targetIdentifier', 'targetReference']),
                ];

                $composition->relatesTo()->createQuietly($relatesToData);
            }
        }

        $event = $this->returnAttribute($resourceContent, ['event']);
        if (!empty($event)) {
            $eventData = [
                'code' => $this->returnMultiCodeableConcept($this->returnAttribute($event, ['code'])),
                'period_start' => $this->returnAttribute($event, ['period', 'start']),
                'period_end' => $this->returnAttribute($event, ['period', 'end']),
                'detail' => $this->returnMultiReference($this->returnAttribute($event, ['detail'])),
            ];

            $composition->event()->createQuietly($eventData);
        }

        $section = $this->returnAttribute($resourceContent, ['section']);
        if (!empty($section)) {
            foreach ($section as $s) {
                $sectionData = [
                    'title' => $this->returnAttribute($s, ['title']),
                    'code' => $this->returnAttribute($s, ['code', 'coding', 0, 'code']),
                    'author' => $this->returnMultiReference($this->returnAttribute($s, ['author'])),
                    'focus' => $this->returnAttribute($s, ['focus', 'reference']),
                    'text_status' => $this->returnAttribute($s, ['text', 'status']),
                    'text_div' => $this->returnAttribute($s, ['text', 'div']),
                    'mode' => $this->returnAttribute($s, ['mode']),
                    'ordered_by' => $this->returnAttribute($s, ['orderedBy', 'coding', 0, 'code']),
                    'entry' => $this->returnMultiReference($this->returnAttribute($s, ['entry'])),
                    'empty_reason' => $this->returnAttribute($s, ['emptyReason', 'coding', 0, 'code']),
                    'section' => $this->returnAttribute($s, ['section']),
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
                    'type' => $this->returnAttribute($dr, ['type', 'coding', 0, 'code']),
                    'dose' => $this->returnVariableAttribute($dr, ['doseRange', 'doseQuantity']),
                    'rate' => $this->returnVariableAttribute($dr, ['rateRatio', 'rateRange', 'rateQuantity']),
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
                'sequence' => $this->returnAttribute($dosageData, ['sequence']),
                'text' => $this->returnAttribute($dosageData, ['text']),
                'additional_instruction' => $this->returnMultiCodeableConcept($this->returnAttribute($dosageData, ['additionalInstruction'])),
                'patient_instruction' => $this->returnAttribute($dosageData, ['patientInstruction']),
                'timing_event' => $this->returnAttribute($dosageData, ['timing', 'event']),
                'timing_repeat' => $this->returnAttribute($dosageData, ['timing', 'repeat']),
                'timing_code' => $this->returnAttribute($dosageData, ['timing', 'code', 'coding', 0, 'code']),
                'site' => $this->returnAttribute($dosageData, ['site', 'coding', 0, 'code']),
                'route' => $this->returnAttribute($dosageData, ['route', 'coding', 0, 'code']),
                'method' => $this->returnAttribute($dosageData, ['method', 'coding', 0, 'code']),
                'max_dose_per_period_numerator_value' => $this->returnAttribute($dosageData, ['maxDosePerPeriod', 'numerator', 'value']),
                'max_dose_per_period_numerator_comparator' => $this->returnAttribute($dosageData, ['maxDosePerPeriod', 'numerator', 'comparator']),
                'max_dose_per_period_numerator_unit' => $this->returnAttribute($dosageData, ['maxDosePerPeriod', 'numerator', 'unit']),
                'max_dose_per_period_numerator_system' => $this->returnAttribute($dosageData, ['maxDosePerPeriod', 'numerator', 'system']),
                'max_dose_per_period_numerator_code' => $this->returnAttribute($dosageData, ['maxDosePerPeriod', 'numerator', 'code']),
                'max_dose_per_period_denominator_value' => $this->returnAttribute($dosageData, ['maxDosePerPeriod', 'denominator', 'value']),
                'max_dose_per_period_denominator_comparator' => $this->returnAttribute($dosageData, ['maxDosePerPeriod', 'denominator', 'comparator']),
                'max_dose_per_period_denominator_unit' => $this->returnAttribute($dosageData, ['maxDosePerPeriod', 'denominator', 'unit']),
                'max_dose_per_period_denominator_system' => $this->returnAttribute($dosageData, ['maxDosePerPeriod', 'denominator', 'system']),
                'max_dose_per_period_denominator_code' => $this->returnAttribute($dosageData, ['maxDosePerPeriod', 'denominator', 'code']),
                'max_dose_per_administration_value' => $this->returnAttribute($dosageData, ['maxDosePerAdministration', 'value']),
                'max_dose_per_administration_unit' => $this->returnAttribute($dosageData, ['maxDosePerAdministration', 'unit']),
                'max_dose_per_administration_system' => $this->returnAttribute($dosageData, ['maxDosePerAdministration', 'system']),
                'max_dose_per_administration_code' => $this->returnAttribute($dosageData, ['maxDosePerAdministration', 'code']),
                'max_dose_per_lifetime_value' => $this->returnAttribute($dosageData, ['maxDosePerLifetime', 'value']),
                'max_dose_per_lifetime_unit' => $this->returnAttribute($dosageData, ['maxDosePerLifetime', 'unit']),
                'max_dose_per_lifetime_system' => $this->returnAttribute($dosageData, ['maxDosePerLifetime', 'system']),
                'max_dose_per_lifetime_code' => $this->returnAttribute($dosageData, ['maxDosePerLifetime', 'code']),
            ];
        }

        return $dosage;
    }


    private function seedMedicationRequest($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $medicationRequestData = [
            'status' => $this->returnAttribute($resourceContent, ['status']),
            'status_reason' => $this->returnAttribute($resourceContent, ['statusReason', 'coding', 0, 'code']),
            'intent' => $this->returnAttribute($resourceContent, ['intent']),
            'category' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['category'])),
            'priority' => $this->returnAttribute($resourceContent, ['priority']),
            'do_not_perform' => $this->returnAttribute($resourceContent, ['doNotPerform']),
            'reported' => $this->returnAttribute($resourceContent, ['reportedBoolean']),
            'medication' => $this->returnAttribute($resourceContent, ['medicationReference', 'reference']),
            'subject' => $this->returnAttribute($resourceContent, ['subject', 'reference']),
            'encounter' => $this->returnAttribute($resourceContent, ['encounter', 'reference']),
            'supporting_information' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['supportingInformation'])),
            'authored_on' => $this->returnAttribute($resourceContent, ['authoredOn']),
            'requester' => $this->returnAttribute($resourceContent, ['requester', 'reference']),
            'performer' => $this->returnAttribute($resourceContent, ['performer', 'reference']),
            'performer_type' => $this->returnAttribute($resourceContent, ['performerType', 'coding', 0, 'code']),
            'recorder' => $this->returnAttribute($resourceContent, ['recorder', 'reference']),
            'reason_code' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['reasonCode'])),
            'reason_reference' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['reasonReference'])),
            'based_on' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['basedOn'])),
            'course_of_therapy' => $this->returnAttribute($resourceContent, ['courseOfTherapyType', 'coding', 0, 'code']),
            'insurance' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['insurance'])),
            'dispense_interval_value' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'dispenseInterval', 'value']),
            'dispense_interval_comparator' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'dispenseInterval', 'comparator']),
            'dispense_interval_unit' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'dispenseInterval', 'unit']),
            'dispense_interval_system' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'dispenseInterval', 'system']),
            'dispense_interval_code' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'dispenseInterval', 'code']),
            'validity_period_start' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'validityPeriod', 'start']),
            'validity_period_end' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'validityPeriod', 'end']),
            'repeats_allowed' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'numberOfRepeatsAllowed']),
            'quantity_value' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'quantity', 'value']),
            'quantity_unit' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'quantity', 'unit']),
            'quantity_system' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'quantity', 'system']),
            'quantity_code' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'quantity', 'code']),
            'supply_duration_value' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'expectedSupplyDuration', 'value']),
            'supply_duration_comparator' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'expectedSupplyDuration', 'comparator']),
            'supply_duration_unit' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'expectedSupplyDuration', 'unit']),
            'supply_duration_system' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'expectedSupplyDuration', 'system']),
            'supply_duration_code' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'expectedSupplyDuration', 'code']),
            'dispense_performer' => $this->returnAttribute($resourceContent, ['dispenseRequest', 'performer', 'reference']),
            'substitution_allowed' => $this->returnVariableAttribute($resourceContent, MedicationRequest::SUBSTITUTION_ALLOWED['variableTypes']),
            'substitution_reason' => $this->returnAttribute($resourceContent, ['substitution', 'reason', 'coding', 0, 'code']),
        ];

        $medicationRequestData = $this->removeEmptyValues($medicationRequestData);

        $medicationRequest = $resource->medicationRequest()->createQuietly($medicationRequestData);
        $medicationRequest->identifier()->createManyQuietly($this->returnIdentifier($this->returnAttribute($resourceContent, ['identifier'])));
        $medicationRequest->note()->createManyQuietly($this->returnAnnotation($this->returnAttribute($resourceContent, ['note'])));

        $dosages = $this->returnAttribute($resourceContent, ['dosageInstruction']);
        if (!empty($dosages)) {
            foreach ($dosages as $d) {
                $dosageData = $this->returnDosage($d);
                $dosage = $medicationRequest->dosage()->createQuietly($dosageData);
                $dosage->doseRate()->createManyQuietly($this->returnDoseAndRate($this->returnAttribute($d, ['doseAndRate'])));
            }
        }
    }


    private function seedMedication($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);
        $extension = $this->returnAttribute($resourceContent, ['extension']);
        $medicationType = null;
        if (!empty($extension)) {
            foreach ($extension as $e) {
                if ($e['url'] == "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType") {
                    $medicationType = $this->returnAttribute($e, ['valueCodeableConcept', 'coding', 0, 'code']);
                }
            }
        }

        $medicationData = [
            'system' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'system']),
            'code' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'code']),
            'display' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'display']),
            'status' => $this->returnAttribute($resourceContent, ['status']),
            'manufacturer' => $this->returnAttribute($resourceContent, ['manufacturer', 'reference']),
            'form' => $this->returnAttribute($resourceContent, ['form', 'coding', 0, 'code']),
            'amount_numerator_value' => $this->returnAttribute($resourceContent, ['amount', 'numerator', 'value']),
            'amount_numerator_comparator' => $this->returnAttribute($resourceContent, ['amount', 'numerator', 'comparator']),
            'amount_numerator_unit' => $this->returnAttribute($resourceContent, ['amount', 'numerator', 'unit']),
            'amount_numerator_system' => $this->returnAttribute($resourceContent, ['amount', 'numerator', 'system']),
            'amount_numerator_code' => $this->returnAttribute($resourceContent, ['amount', 'numerator', 'code']),
            'amount_denominator_value' => $this->returnAttribute($resourceContent, ['amount', 'denominator', 'value']),
            'amount_denominator_comparator' => $this->returnAttribute($resourceContent, ['amount', 'denominator', 'comparator']),
            'amount_denominator_unit' => $this->returnAttribute($resourceContent, ['amount', 'denominator', 'unit']),
            'amount_denominator_system' => $this->returnAttribute($resourceContent, ['amount', 'denominator', 'system']),
            'amount_denominator_code' => $this->returnAttribute($resourceContent, ['amount', 'denominator', 'code']),
            'batch_lot_number' => $this->returnAttribute($resourceContent, ['batch', 'lotNumber']),
            'batch_expiration_date' => $this->returnAttribute($resourceContent, ['batch', 'expirationDate']),
            'medication_type' => $medicationType
        ];

        $medication = $resource->medication()->createQuietly($medicationData);
        $medication->identifier()->createManyQuietly($this->returnIdentifier($this->returnAttribute($resourceContent, ['identifier'])));

        $ingredients = $this->returnAttribute($resourceContent, ['ingredient']);

        if (!empty($ingredients)) {
            foreach ($ingredients as $i) {
                $ingredientData = [
                    'system' => $this->returnAttribute($i, ['itemCodeableConcept', 'coding', 0, 'system']),
                    'code' => $this->returnAttribute($i, ['itemCodeableConcept', 'coding', 0, 'code']),
                    'display' => $this->returnAttribute($i, ['itemCodeableConcept', 'coding', 0, 'display']),
                    'is_active' => $this->returnAttribute($i, ['isActive']),
                    'strength_numerator_value' => $this->returnAttribute($i, ['strength', 'numerator', 'value']),
                    'strength_numerator_comparator' => $this->returnAttribute($i, ['strength', 'numerator', 'comparator']),
                    'strength_numerator_unit' => $this->returnAttribute($i, ['strength', 'numerator', 'unit']),
                    'strength_numerator_system' => $this->returnAttribute($i, ['strength', 'numerator', 'system']),
                    'strength_numerator_code' => $this->returnAttribute($i, ['strength', 'numerator', 'code']),
                    'strength_denominator_value' => $this->returnAttribute($i, ['strength', 'denominator', 'value']),
                    'strength_denominator_comparator' => $this->returnAttribute($i, ['strength', 'denominator', 'comparator']),
                    'strength_denominator_unit' => $this->returnAttribute($i, ['strength', 'denominator', 'unit']),
                    'strength_denominator_system' => $this->returnAttribute($i, ['strength', 'denominator', 'system']),
                    'strength_denominator_code' => $this->returnAttribute($i, ['strength', 'denominator', 'code']),
                ];

                $medication->ingredient()->createQuietly($ingredientData);
            }
        }
    }


    private function seedProcedure($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $procedureData = [
            'based_on' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['basedOn'])),
            'part_of' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['partOf'])),
            'status' => $this->returnAttribute($resourceContent, ['status']),
            'status_reason' => $this->returnAttribute($resourceContent, ['statusReason', 'coding', 0, 'code']),
            'category' => $this->returnAttribute($resourceContent, ['category', 'coding', 0, 'code']),
            'code_system' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'system']),
            'code_code' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'code']),
            'code_display' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'display']),
            'subject' => $this->returnAttribute($resourceContent, ['subject', 'reference']),
            'encounter' => $this->returnAttribute($resourceContent, ['encounter', 'reference']),
            'performed' => $this->returnVariableAttribute($resourceContent, Procedure::PERFORMED['variableTypes']),
            'recorder' => $this->returnAttribute($resourceContent, ['recorder', 'reference']),
            'asserter' => $this->returnAttribute($resourceContent, ['asserter', 'reference']),
            'location' => $this->returnAttribute($resourceContent, ['location', 'reference']),
            'reason_code' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['reasonCode'])),
            'reason_reference' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['reasonReference'])),
            'body_site' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['bodySite'])),
            'outcome' => $this->returnAttribute($resourceContent, ['outcome', 'coding', 0, 'code']),
            'report' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['report'])),
            'complication' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['complication'])),
            'complication_detail' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['complicationDetail'])),
            'follow_up' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['followUp'])),
            'used_reference' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['usedReference'])),
            'used_code' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['usedCode'])),
        ];

        $procedure = $resource->procedure()->createQuietly($procedureData);
        $procedure->identifier()->createManyQuietly($this->returnIdentifier($this->returnAttribute($resourceContent, ['identifier'])));

        $performers = $this->returnAttribute($resourceContent, ['performer']);
        if (!empty($performers)) {
            foreach ($performers as $p) {
                $performerData = [
                    'function' => $this->returnAttribute($p, ['function', 'coding', 0, 'code']),
                    'actor' => $this->returnAttribute($p, ['actor', 'reference']),
                    'on_behalf_of' => $this->returnAttribute($p, ['onBehalfOf', 'reference'])
                ];

                $procedure->performer()->createQuietly($performerData);
            }
        }

        $procedure->note()->createManyQuietly($this->returnAnnotation($this->returnAttribute($resourceContent, ['note'])));

        $focalDevices = $this->returnAttribute($resourceContent, ['focalDevice']);
        if (!empty($focalDevices)) {
            foreach ($focalDevices as $fd) {
                $deviceData = [
                    'action' => $this->returnAttribute($fd, ['action', 'coding', 0, 'code']),
                    'manipulated' => $this->returnAttribute($fd, ['manipulated', 'reference'])
                ];

                $procedure->focalDevice()->createQuietly($deviceData);
            }
        }
    }


    private function seedObservation($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $observationData = [
            'based_on' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['basedOn'])),
            'part_of' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['partOf'])),
            'status' => $this->returnAttribute($resourceContent, ['status']),
            'category' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['category'])),
            'code' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'code']),
            'subject' => $this->returnAttribute($resourceContent, ['subject', 'reference']),
            'focus' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['focus'])),
            'encounter' => $this->returnAttribute($resourceContent, ['encounter', 'reference']),
            'effective' => $this->returnVariableAttribute($resourceContent, Observation::EFFECTIVE['variableTypes']),
            'issued' => $this->returnAttribute($resourceContent, ['issued']),
            'performer' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['performer'])),
            'value' => $this->returnVariableAttribute($resourceContent, Observation::VALUE['variableTypes']),
            'data_absent_reason' => $this->returnAttribute($resourceContent, ['dataAbsentReason', 'coding', 0, 'code']),
            'interpretation' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['interpretation'])),
            'body_site' => $this->returnAttribute($resourceContent, ['bodySite', 'coding', 0, 'code']),
            'method' => $this->returnAttribute($resourceContent, ['method', 'coding', 0, 'code']),
            'specimen' => $this->returnAttribute($resourceContent, ['specimen', 'reference']),
            'device' => $this->returnAttribute($resourceContent, ['device', 'reference']),
            'has_member' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['hasMember'])),
            'derived_from' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['derivedFrom'])),
        ];

        $observation = $resource->observation()->createQuietly($observationData);
        $observation->identifier()->createManyQuietly($this->returnIdentifier($this->returnAttribute($resourceContent, ['identifier'])));
        $observation->note()->createManyQuietly($this->returnAnnotation($this->returnAttribute($resourceContent, ['note'])));

        $refRanges = $this->returnAttribute($resourceContent, ['referenceRange']);
        if (!empty($refRanges)) {
            foreach ($refRanges as $rr) {
                $refRangeData = [
                    'low_value' => $this->returnAttribute($rr, ['low', 'value']),
                    'low_unit' => $this->returnAttribute($rr, ['low', 'unit']),
                    'low_system' => $this->returnAttribute($rr, ['low', 'system']),
                    'low_code' => $this->returnAttribute($rr, ['low', 'code']),
                    'high_value' => $this->returnAttribute($rr, ['high', 'value']),
                    'high_unit' => $this->returnAttribute($rr, ['high', 'unit']),
                    'high_system' => $this->returnAttribute($rr, ['high', 'system']),
                    'high_code' => $this->returnAttribute($rr, ['high', 'code']),
                    'type' => $this->returnAttribute($rr, ['type', 'coding', 0, 'code']),
                    'applies_to' => $this->returnMultiCodeableConcept($this->returnAttribute($rr, ['appliesTo'])),
                    'age_low' => $this->returnAttribute($rr, ['age', 'low', 'value']),
                    'age_high' => $this->returnAttribute($rr, ['age', 'high', 'value']),
                    'text' => $this->returnAttribute($rr, ['text']),
                ];

                $observation->referenceRange()->createQuietly($refRangeData);
            }
        }

        $components = $this->returnAttribute($resourceContent, ['component']);
        if (!empty($components)) {
            foreach ($components as $c) {
                $componentData = [
                    'code' => $this->returnAttribute($c, ['code', 'coding', 0, 'code']),
                    'value' => $this->returnVariableAttribute($c, ObservationComponent::VALUE['variableTypes']),
                    'data_absent_reason' => $this->returnAttribute($c, ['dataAbsentReason', 'coding', 0, 'code']),
                    'interpretation' => $this->returnMultiCodeableConcept($this->returnAttribute($c, ['interpretation'])),
                ];

                $component = $observation->component()->createQuietly($componentData);

                $refRanges = $this->returnAttribute($c, ['referenceRange']);
                if (!empty($refRanges)) {
                    foreach ($refRanges as $rr) {
                        $refRangeData = [
                            'low_value' => $this->returnAttribute($rr, ['low', 'value']),
                            'low_unit' => $this->returnAttribute($rr, ['low', 'unit']),
                            'low_system' => $this->returnAttribute($rr, ['low', 'system']),
                            'low_code' => $this->returnAttribute($rr, ['low', 'code']),
                            'high_value' => $this->returnAttribute($rr, ['high', 'value']),
                            'high_unit' => $this->returnAttribute($rr, ['high', 'unit']),
                            'high_system' => $this->returnAttribute($rr, ['high', 'system']),
                            'high_code' => $this->returnAttribute($rr, ['high', 'code']),
                            'type' => $this->returnAttribute($rr, ['type', 'coding', 0, 'code']),
                            'applies_to' => $this->returnMultiCodeableConcept($this->returnAttribute($rr, ['appliesTo'])),
                            'age_low' => $this->returnAttribute($rr, ['age', 'low', 'value']),
                            'age_high' => $this->returnAttribute($rr, ['age', 'high', 'value']),
                            'text' => $this->returnAttribute($rr, ['text']),
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
            'clinical_status' => $this->returnAttribute($resourceContent, ['clinicalStatus', 'coding', 0, 'code']),
            'verification_status' => $this->returnAttribute($resourceContent, ['verificationStatus', 'coding', 0, 'code']),
            'category' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['category'])),
            'severity' => $this->returnAttribute($resourceContent, ['severity', 'coding', 0, 'code']),
            'code_system' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'system']),
            'code_code' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'code']),
            'code_display' => $this->returnAttribute($resourceContent, ['code', 'coding', 0, 'display']),
            'body_site' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['bodySite'])),
            'subject' => $this->returnAttribute($resourceContent, ['subject', 'reference']),
            'encounter' => $this->returnAttribute($resourceContent, ['encounter', 'reference']),
            'onset' => $this->returnVariableAttribute($resourceContent, Condition::ONSET),
            'abatement' => $this->returnVariableAttribute($resourceContent, Condition::ABATEMENT),
            'recorded_date' => $this->returnAttribute($resourceContent, ['recordedDate']),
            'recorder' => $this->returnAttribute($resourceContent, ['recorder', 'reference']),
            'asserter' => $this->returnAttribute($resourceContent, ['asserter', 'reference'])
        ];

        $condition = $resource->condition()->createQuietly($conditionData);

        $condition->identifier()->createManyQuietly($this->returnIdentifier($this->returnAttribute($resourceContent, ['identifier'])));

        $stageData = $this->returnAttribute($resourceContent, ['stage']);
        if (!empty($stageData)) {
            foreach ($stageData as $s) {
                $stage = [
                    'summary' => $this->returnAttribute($s, ['summary', 'coding', 0, 'code']),
                    'assessment' => $this->returnMultiReference($this->returnAttribute($s, ['assessment'])),
                    'type' => $this->returnAttribute($s, ['type', 'coding', 0, 'code'])
                ];
                $condition->stage()->createQuietly($stage);
            }
        }

        $evidenceData = $this->returnAttribute($resourceContent, ['evidence']);
        if (!empty($evidenceData)) {
            foreach ($evidenceData as $e) {
                $evidence = [
                    'code' => $this->returnMultiCodeableConcept($this->returnAttribute($e, ['code'])),
                    'detail' => $this->returnMultiReference($this->returnAttribute($e, ['detail']))
                ];
                $condition->evidence()->createQuietly($evidence);
            }
        }

        $condition->note()->createManyQuietly($this->returnAnnotation($this->returnAttribute($resourceContent, ['note'])));
    }


    private function seedEncounter($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $encounterData = [
            'status' => $this->returnAttribute($resourceContent, ['status']),
            'class' => $this->returnAttribute($resourceContent, ['class', 'code']),
            'type' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['type'])),
            'service_type' => $this->returnAttribute($resourceContent, ['serviceType', 'coding', 0, 'code']),
            'priority' => $this->returnAttribute($resourceContent, ['priority', 'coding', 0, 'code']),
            'subject' => $this->returnAttribute($resourceContent, ['subject', 'reference']),
            'episode_of_care' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['episodeOfCare'])),
            'based_on' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['basedOn'])),
            'period_start' => $this->returnAttribute($resourceContent, ['period', 'start']),
            'period_end' => $this->returnAttribute($resourceContent, ['period', 'end']),
            'length_value' => $this->returnAttribute($resourceContent, ['length', 'value']),
            'length_comparator' => $this->returnAttribute($resourceContent, ['length', 'comparator']),
            'length_unit' => $this->returnAttribute($resourceContent, ['length', 'unit']),
            'length_system' => $this->returnAttribute($resourceContent, ['length', 'system']),
            'length_code' => $this->returnAttribute($resourceContent, ['length', 'code']),
            'reason_code' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['reasonCode'])),
            'reason_reference' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['reasonReference'])),
            'account' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['account'])),
            'hospitalization_preadmission_identifier_system' => $this->returnAttribute($resourceContent, ['hospitalization', 'preAdmissionIdentifier', 'system']),
            'hospitalization_preadmission_identifier_use' => $this->returnAttribute($resourceContent, ['hospitalization', 'preAdmissionIdentifier', 'use']),
            'hospitalization_preadmission_identifier_value' => $this->returnAttribute($resourceContent, ['hospitalization', 'preAdmissionIdentifier', 'value']),
            'hospitalization_origin' => $this->returnAttribute($resourceContent, ['hospitalization', 'origin', 'reference']),
            'hospitalization_admit_source' => $this->returnAttribute($resourceContent, ['hospitalization', 'admitSource', 'coding', 0, 'code']),
            'hospitalization_re_admission' => $this->returnAttribute($resourceContent, ['hospitalization', 'reAdmission', 'coding', 0, 'code']),
            'hospitalization_diet_preference' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['hospitalization', 'dietPreference'])),
            'hospitalization_special_arrangement' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['hospitalization', 'specialArrangement'])),
            'hospitalization_destination' => $this->returnAttribute($resourceContent, ['hospitalization', 'destination', 'reference']),
            'hospitalization_discharge_disposition' => $this->returnAttribute($resourceContent, ['hospitalization', 'dischargeDisposition', 'coding', 0, 'code']),
            'service_provider' => $this->returnAttribute($resourceContent, ['serviceProvider', 'reference']),
            'part_of' => $this->returnAttribute($resourceContent, ['partOf', 'reference'])
        ];

        $encounter = $resource->encounter()->createQuietly($encounterData);
        $encounter->identifier()->createManyQuietly($this->returnIdentifier($this->returnAttribute($resourceContent, ['identifier'])));

        $statusHistories = $this->returnAttribute($resourceContent, ['statusHistory']);
        if (!empty($statusHistories)) {
            foreach ($statusHistories as $sh) {
                $statusHistoryData = [
                    'status' => $this->returnAttribute($sh, ['status']),
                    'period_start' => $this->returnAttribute($sh, ['period', 'start']),
                    'period_end' => $this->returnAttribute($sh, ['period', 'end'])
                ];
                $encounter->statusHistory()->createQuietly($statusHistoryData);
            }
        }

        $classHistories = $this->returnAttribute($resourceContent, ['classHistory']);
        if (!empty($classHistories)) {
            foreach ($classHistories as $ch) {
                $classHistory = [
                    'status' => $this->returnAttribute($ch, ['status']),
                    'period_start' => $this->returnAttribute($ch, ['period', 'start']),
                    'period_end' => $this->returnAttribute($ch, ['period', 'end'])
                ];
                $encounter->classHistory()->createQuietly($classHistory);
            }
        }

        $participants = $this->returnAttribute($resourceContent, ['participant']);
        if (!empty($participants)) {
            foreach ($participants as $p) {
                $participant = [
                    'type' => $this->returnMultiCodeableConcept($this->returnAttribute($p, ['type'])),
                    'individual' => $this->returnAttribute($p, ['individual', 'reference'])
                ];
                $encounter->participant()->createQuietly($participant);
            }
        }

        $diagnoses = $this->returnAttribute($resourceContent, ['diagnosis']);
        if (!empty($diagnoses)) {
            foreach ($diagnoses as $d) {
                $diagnosis = [
                    'condition' => $this->returnAttribute($d, ['condition', 'reference']),
                    'use' => $this->returnAttribute($d, ['use', 'coding', 0, 'code']),
                    'rank' => $this->returnAttribute($d, ['rank']),
                ];
                $encounter->diagnosis()->createQuietly($diagnosis);
            }
        }

        $locations = $this->returnAttribute($resourceContent, ['location']);
        if (!empty($locations)) {
            foreach ($locations as $l) {
                $location = [
                    'location' => $this->returnAttribute($l, ['location', 'reference']),
                ];
                $encounter->location()->createQuietly($location);
            }
        }
    }


    private function seedQuestionnaireResponse($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $questionnaireData = [
            'identifier_system' => $this->returnAttribute($resourceContent, ['identifier', 'system']),
            'identifier_use' => $this->returnAttribute($resourceContent, ['identifier', 'use']),
            'identifier_value' => $this->returnAttribute($resourceContent, ['identifier', 'value']),
            'based_on' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['basedOn'])),
            'part_of' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['partOf'])),
            'questionnaire' => $this->returnAttribute($resourceContent, ['questionnaire']),
            'status' => $this->returnAttribute($resourceContent, ['status']),
            'subject' => $this->returnAttribute($resourceContent, ['subject', 'reference']),
            'encounter' => $this->returnAttribute($resourceContent, ['encounter', 'reference']),
            'authored' => $this->returnAttribute($resourceContent, ['authored']),
            'author' => $this->returnAttribute($resourceContent, ['author', 'reference']),
            'source' => $this->returnAttribute($resourceContent, ['source', 'reference']),
        ];

        $questionnaireResponse = $resource->questionnaireResponse()->createQuietly($questionnaireData);

        $items = $this->returnAttribute($resourceContent, ['item']);
        if (!empty($items)) {
            foreach ($items as $i) {
                $itemData = [
                    'link_id' => $this->returnAttribute($i, ['linkId']),
                    'definition' => $this->returnAttribute($i, ['definition']),
                    'text' => $this->returnAttribute($i, ['text']),
                    'item' => $this->returnAttribute($i, ['item'])
                ];

                $item = $questionnaireResponse->item()->createQuietly($itemData);

                $answers = $this->returnAttribute($i, ['answer']);
                if (!empty($answers)) {
                    foreach ($answers as $a) {
                        $answerData = [
                            'value' => $this->returnVariableAttribute($a, QuestionnaireResponseItemAnswer::VALUE['variableTypes']),
                            'item' => $this->returnAttribute($a, ['item'])
                        ];

                        $item->answer()->createQuietly($answerData);
                    }
                }
            }
        }
    }


    private function seedPatient($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);
        $extension = $this->returnAttribute($resourceContent, ['extension']);
        $birthPlace = $this->returnBirthPlace($extension);

        $patientData = [
            'active' => $this->returnAttribute($resourceContent, ['active']),
            'gender' => $this->returnAttribute($resourceContent, ['gender'], 'unknown'),
            'birth_date' => $this->returnAttribute($resourceContent, ['birthDate']),
            'deceased' => $this->returnVariableAttribute($resourceContent, Patient::DECEASED['variableTypes']),
            'marital_status' => $this->returnAttribute($resourceContent, ['maritalStatus', 'coding', 0, 'code']),
            'multiple_birth' => [
                'multipleBirthBoolean' => false
            ],
            'general_practitioner' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['generalPractitioner'])),
            'managing_organization' => $this->returnAttribute($resourceContent, ['managingOrganization', 'reference']),
            'birth_city' => $birthPlace['city'],
            'birth_country' => $birthPlace['country']
        ];

        $patient = $resource->patient()->createQuietly($patientData);
        $patient->identifier()->createManyQuietly($this->returnIdentifier($this->returnAttribute($resourceContent, ['identifier'])));
        $patient->name()->createManyQuietly($this->returnHumanName($this->returnAttribute($resourceContent, ['name'])));
        $patient->telecom()->createManyQuietly($this->returnTelecom($this->returnAttribute($resourceContent, ['telecom'])));
        $patient->address()->createManyQuietly($this->returnAddress($this->returnAttribute($resourceContent, ['address'])));
        $patient->photo()->createManyQuietly($this->returnPhoto($this->returnAttribute($resourceContent, ['photo'])));

        $contacts = $this->returnAttribute($resourceContent, ['contact']);
        if (!empty($contacts)) {
            foreach ($contacts as $c) {
                $addressExtension = $this->returnAdministrativeAddress($this->returnAttribute($c, ['address', 'extension']));

                $contactData = $this->mergeArray(
                    [
                        'relationship' => $this->returnMultiCodeableConcept($this->returnAttribute($c, ['relationship'])),
                        'name_text' => $this->returnAttribute($c, ['name', 'text']),
                        'name_family' => $this->returnAttribute($c, ['name', 'family']),
                        'name_given' => $this->returnAttribute($c, ['name', 'given']),
                        'name_prefix' => $this->returnAttribute($c, ['name', 'prefix']),
                        'name_suffix' => $this->returnAttribute($c, ['name', 'suffix']),
                        'gender' => $this->returnAttribute($c, ['gender'], 'unknown'),
                        'address_use' => $this->returnAttribute($c, ['address', 'use']),
                        'address_type' => $this->returnAttribute($c, ['address', 'type']),
                        'address_line' => $this->returnAttribute($c, ['address', 'line']),
                        'country' => $this->returnAttribute($c, ['address', 'country']),
                        'postal_code' => $this->returnAttribute($c, ['address', 'postalCode']),
                        'organization' => $this->returnAttribute($c, ['organization', 'reference']),
                        'period_start' => $this->returnAttribute($c, ['period', 'start']),
                        'period_end' => $this->returnAttribute($c, ['period', 'end']),
                    ],
                    $addressExtension
                );

                $contact = $patient->contact()->createQuietly($contactData);
                $contact->telecom()->createManyQuietly($this->returnTelecom($this->returnAttribute($c, ['telecom'])));
            }
        }

        $communications = $this->returnAttribute($resourceContent, ['communication']);
        if (!empty($communications)) {
            foreach ($communications as $c) {
                $communicationData = [
                    'language' => $this->returnAttribute($c, ['language', 'coding', 0, 'code']),
                    'preferred' => $this->returnAttribute($c, ['preferred'])
                ];

                $patient->communication()->createQuietly($communicationData);
            }
        }

        $links = $this->returnAttribute($resourceContent, ['link']);
        if (!empty($links)) {
            foreach ($links as $l) {
                $linkData = [
                    'other' => $this->returnAttribute($l, ['other', 'reference']),
                    'type' => $this->returnAttribute($l, ['type'])
                ];

                $patient->link()->createQuietly($linkData);
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
                        'city' => $this->returnAttribute($e, ['valueAddress', 'city']),
                        'country' => $this->returnAttribute($e, ['valueAddress', 'country'])
                    ];
                }
            }
        }

        return $birthPlace;
    }


    private function seedPractitioner($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);

        $practitionerData = [
            'active' => $this->returnAttribute($resourceContent, ['active']),
            'gender' => $this->returnAttribute($resourceContent, ['gender'], 'unknown'),
            'birth_date' => $this->returnAttribute($resourceContent, ['birthDate']),
            'communication' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['communication']))
        ];

        $practitioner = $resource->practitioner()->createQuietly($practitionerData);
        $practitioner->identifier()->createManyQuietly($this->returnIdentifier($this->returnAttribute($resourceContent, ['identifier'])));
        $practitioner->name()->createManyQuietly($this->returnHumanName($this->returnAttribute($resourceContent, ['name'])));
        $practitioner->telecom()->createManyQuietly($this->returnTelecom($this->returnAttribute($resourceContent, ['telecom'])));
        $practitioner->address()->createManyQuietly($this->returnAddress($this->returnAttribute($resourceContent, ['address'])));
        $practitioner->photo()->createManyQuietly($this->returnPhoto($this->returnAttribute($resourceContent, ['photo'])));

        $qualifications = $this->returnAttribute($resourceContent, ['qualification']);
        if (!empty($qualifications)) {
            foreach ($qualifications as $q) {
                $qualificationData = [
                    'identifier' => $this->returnAttribute($q, ['identifier']),
                    'code_system' => $this->returnAttribute($q, ['code', 'coding', 0, 'system']),
                    'code_code' => $this->returnAttribute($q, ['code', 'coding', 0, 'code']),
                    'code_display' => $this->returnAttribute($q, ['code', 'coding', 0, 'display']),
                    'period_start' => $this->returnAttribute($q, ['period', 'start']),
                    'period_end' => $this->returnAttribute($q, ['period', 'end']),
                    'issuer' => $this->returnAttribute($q, ['issuer', 'reference'])
                ];

                $practitioner->qualification()->createQuietly($qualificationData);
            }
        }
    }


    private function returnPhoto($attachment): array
    {
        $photo = [];

        if (!empty($attachment)) {
            foreach ($attachment as $a) {
                $photo[] = [
                    'data' => $this->returnAttribute($a, ['data']),
                    'url' => $this->returnAttribute($a, ['url']),
                    'size' => $this->returnAttribute($a, ['size']),
                    'hash' => $this->returnAttribute($a, ['hash']),
                    'title' => $this->returnAttribute($a, ['title']),
                    'creation' => $this->returnAttribute($a, ['creation'])
                ];
            }
        }

        return $photo;
    }


    private function returnHumanName($names): array
    {
        $name = [];

        if (!empty($names)) {
            foreach ($names as $n) {
                $name[] = [
                    'use' => $this->returnAttribute($n, ['use']),
                    'text' => $this->returnAttribute($n, ['text']),
                    'family' => $this->returnAttribute($n, ['family']),
                    'given' => $this->returnAttribute($n, ['given']),
                    'prefix' => $this->returnAttribute($n, ['prefix']),
                    'suffix' => $this->returnAttribute($n, ['suffix']),
                    'period_start' => $this->returnAttribute($n, ['period', 'start']),
                    'period_end' => $this->returnAttribute($n, ['period', 'end'])
                ];
            }
        }

        return $name;
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
        $extensionData = $this->returnAttribute($resourceContent, ['address', 'extension', 0, 'extension'], null);

        if (!empty($extensionData)) {
            foreach ($extensionData as $extension) {
                $url = $extension['url'];
                $value = (int)preg_replace("/[^0-9]/", "", $extension['valueCode']);
                $addressDetails[$url] = $value;
            }
        }

        $locationData = array_merge(
            [
                'status' => $this->returnAttribute($resourceContent, ['status']),
                'operational_status' => $this->returnAttribute($resourceContent, ['operationalStatus', 'code']),
                'name' => $this->returnAttribute($resourceContent, ['name'], 'unknown'),
                'alias' => $this->returnAttribute($resourceContent, ['alias']),
                'description' => $this->returnAttribute($resourceContent, ['description']),
                'mode' => $this->returnAttribute($resourceContent, ['mode']),
                'type' => $this->returnLocationType($this->returnAttribute($resourceContent, ['type'])),
                'address_use' => $this->returnAttribute($resourceContent, ['address', 'use']),
                'address_type' => $this->returnAttribute($resourceContent, ['address', 'type']),
                'address_line' => $this->returnAttribute($resourceContent, ['address', 'line']),
                'country' => $this->returnAttribute($resourceContent, ['address', 'country']),
                'postal_code' => $this->returnAttribute($resourceContent, ['address', 'postalCode']),
                'physical_type' => $this->returnAttribute($resourceContent, ['physicalType', 'coding', 0, 'code']),
                'longitude' => $this->returnAttribute($resourceContent, ['position', 'longitude']),
                'latitude' => $this->returnAttribute($resourceContent, ['position', 'latitude']),
                'altitude' => $this->returnAttribute($resourceContent, ['position', 'altitude']),
                'managing_organization' => $this->returnAttribute($resourceContent, ['managingOrganization', 'reference']),
                'part_of' => $this->returnAttribute($resourceContent, ['partOf', 'reference']),
                'availability_exceptions' => $this->returnAttribute($resourceContent, ['availabilityExceptions']),
                'endpoint' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['endpoint'])),
                'service_class' => $this->returnLocationServiceClass($this->returnAttribute($resourceContent, ['extension']))
            ],
            $addressDetails
        );

        $location = $resource->location()->createQuietly($locationData);
        $location->identifier()->createManyQuietly($this->returnIdentifier($this->returnAttribute($resourceContent, ['identifier'])));
        $location->telecom()->createManyQuietly($this->returnTelecom($this->returnAttribute($resourceContent, ['telecom'])));
        $location->operationHours()->createManyQuietly($this->returnOperationHours($this->returnAttribute($resourceContent, ['hoursOfOperation'])));
    }


    private function returnOperationHours($operationHours): array
    {
        $hour = [];

        if (!empty($operationHours)) {
            foreach ($operationHours as $o) {
                $hour[] = [
                    'days_of_week' => $this->returnAttribute($o, ['daysOfWeek']),
                    'all_day' => $this->returnAttribute($o, ['allDay']),
                    'opening_time' => $this->returnAttribute($o, ['openingTime']),
                    'closing_time' => $this->returnAttribute($o, ['closingTime'])
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
                    return $this->returnAttribute($e, ['valueCodeableConcept', 'coding', 0, 'code']);
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
                $type[] = $this->returnAttribute($t, ['coding', 0, 'code']);
            }
        }

        return $type;
    }


    private function seedOrganization($resource, $resourceText)
    {
        $resourceContent = json_decode($resourceText, true);
        $contactData = $this->returnAttribute($resourceContent, ['contact']);

        $organizationData = [
            'active' => $this->returnAttribute($resourceContent, ['active'], false),
            'type' => $this->returnMultiCodeableConcept($this->returnAttribute($resourceContent, ['type'])),
            'name' => $this->returnAttribute($resourceContent, ['name'], 'unknown'),
            'alias' => $this->returnAttribute($resourceContent, ['alias']),
            'part_of' => $this->returnAttribute($resourceContent, ['partOf', 'reference']),
            'endpoint' => $this->returnMultiReference($this->returnAttribute($resourceContent, ['endpoint'])),
        ];

        $organizationData = $this->removeEmptyValues($organizationData);
        $organization = $resource->organization()->createQuietly($organizationData);
        $organization->identifier()->createManyQuietly($this->returnIdentifier($this->returnAttribute($resourceContent, ['identifier'])));
        $organization->telecom()->createManyQuietly($this->returnTelecom($this->returnAttribute($resourceContent, ['telecom'])));
        $organization->address()->createManyQuietly($this->returnAddress($this->returnAttribute($resourceContent, ['address'])));

        if (!empty($contactData)) {
            foreach ($contactData as $c) {
                $addressDetails = [];
                $extensionData = $this->returnAttribute($c, ['address', 'extension', 0, 'extension'], null);

                if (!empty($extensionData)) {
                    foreach ($extensionData as $extension) {
                        $url = $extension['url'];
                        $value = (int)preg_replace("/[^0-9]/", "", $extension['valueCode']);
                        $addressDetails[$url] = $value;
                    }
                }

                $line = $this->returnAttribute($c, ['address', 'line']) ? $this->returnAttribute($c, ['address', 'line']) : $this->returnAttribute($c, ['address', 'text'], '');

                $contactArray = array_merge(
                    $addressDetails,
                    [
                        'purpose' => $this->returnAttribute($c, ['purpose', 'coding', 0, 'code']),
                        'name_text' => $this->returnAttribute($c, ['name', 'text']),
                        'name_family' => $this->returnAttribute($c, ['name', 'family']),
                        'name_given' => $this->returnAttribute($c, ['name', 'given']),
                        'name_prefix' => $this->returnAttribute($c, ['name', 'prefix']),
                        'name_suffix' => $this->returnAttribute($c, ['name', 'suffix']),
                        'address_use' => $this->returnAttribute($c, ['address', 'use']),
                        'address_type' => $this->returnAttribute($c, ['address', 'type']),
                        'address_line' => $line,
                        'country' => $this->returnAttribute($c, ['address', 'country'], 'ID'),
                        'postal_code' => $this->returnAttribute($c, ['address', 'postalCode']),
                    ],
                );
                $contact = $organization->contact()->createQuietly($contactArray);
                $contact->telecom()->createManyQuietly($this->returnTelecom($this->returnAttribute($c, ['telecom'])));
            }
        }
    }


    private function returnAddress($addresses): array
    {
        $address = [];
        $addressDetails = [];

        if (!empty($addresses)) {
            foreach ($addresses as $a) {
                $extensionData = $this->returnAttribute($a, ['extension', 0, 'extension'], null);

                if (!empty($extensionData)) {
                    foreach ($extensionData as $extension) {
                        $url = $extension['url'];
                        $value = (int)preg_replace("/[^0-9]/", "", $extension['valueCode']);
                        $addressDetails[$url] = $value;
                    }
                }

                $line = $this->returnAttribute($a, ['line']) ? $this->returnAttribute($a, ['line']) : $this->returnAttribute($a, ['text']);

                $address[] = array_merge(
                    $addressDetails,
                    [
                        'use' => $this->returnAttribute($a, ['use']),
                        'type' => $this->returnAttribute($a, ['type']),
                        'line' => $line,
                        'country' => $this->returnAttribute($a, ['country'], 'ID'),
                        'postal_code' => $this->returnAttribute($a, ['postalCode']),
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
                    'system' => $this->returnAttribute($t, ['system']),
                    'use' => $this->returnAttribute($t, ['use']),
                    'value' => $this->returnAttribute($t, ['value'])
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
                $codeableConcept[] = $this->returnAttribute($cc, ['coding', 0, 'code']);
            }
        }

        return $codeableConcept;
    }


    private function returnMultiReference($references): array
    {
        $reference = [];

        if (!empty($references)) {
            foreach ($references as $r) {
                $reference[] = $this->returnAttribute($r, ['reference']);
            }
        }

        return $reference;
    }


    private function returnIdentifier($identifiers, string $prefix = null): array
    {
        $identifier = [];

        if (!empty($identifiers)) {
            foreach ($identifiers as $i) {
                $identifier[] = [
                    $prefix . 'system' => $this->returnAttribute($i, ['system'], 'unknown'),
                    $prefix . 'use' => $this->returnAttribute($i, ['use']),
                    $prefix . 'value' => $this->returnAttribute($i, ['value'])
                ];
            }
        }
        return $identifier;
    }


    private function returnAnnotation($annotations): array
    {
        $annotation = [];

        if (!empty($annotations)) {
            foreach ($annotations as $a) {
                $annotation[] = [
                    'author' => $this->returnVariableAttribute($a, ['authorString', 'authorReference']),
                    'time' => $this->returnAttribute($a, ['time']),
                    'text' => $this->returnAttribute($a, ['text'])
                ];
            }
        }

        return $annotation;
    }


    private function returnAttribute($array, $keys, $defaultValue = null)
    {
        $value = $array;
        foreach ($keys as $key) {
            if (isset($value[$key]) && !empty($value[$key])) {
                $value = $value[$key];
            } else {
                return $defaultValue;
            }
        }
        if ($value === null) {
            return $defaultValue;
        }
        return $value;
    }


    private function returnVariableAttribute($resource, $variableAttributes)
    {
        $variableAttribute = [];

        foreach ($variableAttributes as $va) {
            if (!empty($resource[$va])) {
                $variableAttribute[$va] = $resource[$va];
            }
        }

        return empty($variableAttribute) ? null : $variableAttribute;
    }


    private function mergeArray(...$arrays)
    {
        $arr = [];

        foreach ($arrays as $a) {
            if ($a != null) {
                $arr = array_merge($arr, $a);
            }
        }

        return $arr;
    }


    private function removeEmptyValues($array)
    {
        return array_filter($array, function ($value) {
            if (is_array($value)) {
                return !empty($this->removeEmptyValues($value));
            }
            return $value !== null && $value !== "" && !(is_array($value) && empty($value));
        });
    }
}
