<?php

namespace App\Fhir;

use App\Models\Fhir\BackboneElements\{
    AllergyIntoleranceReaction,
    ClinicalImpressionFinding,
    ClinicalImpressionInvestigation,
    CompositionAttester,
    CompositionEvent,
    CompositionRelatesTo,
    CompositionSection,
    ConditionEvidence,
    ConditionStage,
    EncounterClassHistory,
    EncounterDiagnosis,
    EncounterHospitalization,
    EncounterLocation,
    EncounterParticipant,
    EncounterStatusHistory,
    LocationHoursOfOperation,
    LocationPosition,
    MedicationBatch,
    MedicationIngredient,
    MedicationRequestDispenseRequest,
    MedicationRequestDispenseRequestInitialFill,
    MedicationRequestSubstitution,
    ObservationComponent,
    ObservationComponentReferenceRange,
    ObservationReferenceRange,
    OrganizationContact,
    PatientCommunication,
    PatientContact,
    PatientLink,
    PractitionerQualification,
    ProcedureFocalDevice,
    ProcedurePerformer,
    QuestionnaireResponseItem,
    QuestionnaireResponseItemAnswer
};
use App\Models\Fhir\Datatypes\{
    Address,
    Age,
    Annotation,
    Attachment,
    CodeableConcept,
    Coding,
    ComplexExtension,
    ContactPoint,
    Dosage,
    DoseAndRate,
    Duration,
    Extension,
    HumanName,
    Identifier,
    Narrative,
    Period,
    Quantity,
    Range,
    Ratio,
    Reference,
    SampledData,
    SimpleQuantity,
    Timing,
    TimingRepeat
};
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\{
    AllergyIntolerance,
    ClinicalImpression,
    Composition,
    Condition,
    Encounter,
    Location,
    Medication,
    MedicationRequest,
    MedicationStatement,
    Observation,
    Organization,
    Patient,
    Practitioner,
    Procedure,
    QuestionnaireResponse,
    ServiceRequest
};
use Exception;
use Illuminate\Support\Facades\Log;

class Processor
{
    public function saveQuestionnaireResponse(Resource $resource, $array): QuestionnaireResponse
    {
        if (!empty($array)) {
            $questionnaireResponse = $resource->questionnaireResponse()->save($array['questionnaireResponse'] ?? null);
            $this->saveIdentifier($questionnaireResponse, 'identifier', $array['identifier'] ?? null);
            $this->saveMany($questionnaireResponse, 'basedOn', $array['basedOn'] ?? null, 'saveReference');
            $this->saveMany($questionnaireResponse, 'partOf', $array['partOf'] ?? null, 'saveReference');
            $this->saveReference($questionnaireResponse, 'subject', $array['subject'] ?? null);
            $this->saveReference($questionnaireResponse, 'encounter', $array['encounter'] ?? null);
            $this->saveReference($questionnaireResponse, 'author', $array['author'] ?? null);
            $this->saveReference($questionnaireResponse, 'source', $array['source'] ?? null);
            $this->saveMany($questionnaireResponse, 'item', $array['item'] ?? null, 'saveItem');

            return $questionnaireResponse;
        }
    }

    public function generateQuestionnaireResponse($jsonData): array
    {
        $array = $this->readJsonResource($jsonData);

        $questionnaireResponse = new QuestionnaireResponse([
            'questionnaire' => $array['questionnaire'] ?? null,
            'status' => $array['status'] ?? null,
            'authored' => $array['authored'] ?? null
        ]);

        $identifier = $this->generateIdentifier('identifier', $array['identifier'] ?? null);
        $basedOn = $this->hasMany('basedOn', $array['basedOn'] ?? null, 'generateReference');
        $partOf = $this->hasMany('partOf', $array['partOf'] ?? null, 'generateReference');
        $subject = $this->generateReference('subject', $array['subject'] ?? null);
        $encounter = $this->generateReference('encounter', $array['encounter'] ?? null);
        $author = $this->generateReference('author', $array['author'] ?? null);
        $source = $this->generateReference('source', $array['source'] ?? null);
        $item = $this->hasMany('item', $array['item'] ?? null, 'generateItem');

        return [
            'questionnaireResponse' => $questionnaireResponse,
            'identifier' => $identifier ?? null,
            'basedOn' => $basedOn ?? null,
            'partOf' => $partOf ?? null,
            'subject' => $subject ?? null,
            'encounter' => $encounter ?? null,
            'author' => $author ?? null,
            'source' => $source ?? null,
            'item' => $item ?? null
        ];
    }

    private function saveItem($parent, $attribute, $array)
    {
        if (!empty($array)) {
            $item = $parent->$attribute()->save($array['item'] ?? null);

            if (!empty($array['answer'])) {
                foreach ($array['answer'] as $a) {
                    $answer = $item->answer()->save($a['answer'] ?? null);
                    $this->saveAttachment($answer, 'valueAttachment', $a['valueAttachment'] ?? null);
                    $this->saveCoding($answer, 'valueCoding', $a['valueCoding'] ?? null);
                    $this->saveQuantity($answer, 'valueQuantity', $a['valueQuantity'] ?? null);
                    $this->saveReference($answer, 'valueReference', $a['valueReference'] ?? null);
                    $this->saveMany($answer, 'item', $a['item'] ?? null, 'saveItem');
                }
            }

            $this->saveMany($item, 'item', $array['childItem'] ?? null, 'saveItem');
        }
    }

    private function generateItem($attribute, $array): array
    {
        if (!empty($array)) {
            $item = new QuestionnaireResponseItem([
                'link_id' => $array['linkId'] ?? null,
                'definition' => $array['definition'] ?? null,
                'text' => $array['text'] ?? null,
            ]);

            if (!empty($array['answer'])) {
                $answer = [];
                foreach ($array['answer'] as $a) {
                    $answer[] = [
                        'answer' => new QuestionnaireResponseItemAnswer([
                            'value_boolean' => $a['valueBoolean'] ?? null,
                            'value_decimal' => $a['valueDecimal'] ?? null,
                            'value_integer' => $a['valueInteger'] ?? null,
                            'value_date' => $a['valueDate'] ?? null,
                            'value_date_time' => $a['valueDateTime'] ?? null,
                            'value_time' => $a['valueTime'] ?? null,
                            'value_string' => $a['valueString'] ?? null,
                            'value_uri' => $a['valueUri'] ?? null
                        ]),
                        'valueAttachment' => $this->generateAttachment('valueAttachment', $a['valueAttachment'] ?? null),
                        'valueCoding' => $this->generateCoding('valueCoding', $a['valueCoding'] ?? null),
                        'valueQuantity' => $this->generateQuantity('valueQuantity', $a['valueQuantity'] ?? null),
                        'valueReference' => $this->generateReference('valueReference', $a['valueReference'] ?? null),
                        'item' => $this->hasMany('item', $a['item'] ?? null, 'generateItem')
                    ];
                }
            }

            $childItem = $this->hasMany('item', $array['item'] ?? null, 'generateItem');

            return [
                'item' => $item,
                'answer' => $answer ?? null,
                'childItem' => $childItem ?? null
            ];
        }
    }

    public function saveMedicationStatement(Resource $resource, $array): MedicationStatement
    {
        if (!empty($array)) {
            $medicationStatement = $resource->medicationStatement()->save($array['medicationStatement'] ?? null);
            $this->saveMany($medicationStatement, 'identifier', $array['identifier'] ?? null, 'saveIdentifier');
            $this->saveMany($medicationStatement, 'basedOn', $array['basedOn'] ?? null, 'saveReference');
            $this->saveMany($medicationStatement, 'partOf', $array['partOf'] ?? null, 'saveReference');
            $this->saveMany($medicationStatement, 'statusReason', $array['statusReason'] ?? null, 'saveCodeableConcept');
            $this->saveCodeableConcept($medicationStatement, 'category', $array['category'] ?? null);
            $this->saveCodeableConcept($medicationStatement, 'medicationCodeableConcept', $array['medicationCodeableConcept'] ?? null);
            $this->saveReference($medicationStatement, 'medicationReference', $array['medicationReference'] ?? null);
            $this->saveReference($medicationStatement, 'subject', $array['subject'] ?? null);
            $this->saveReference($medicationStatement, 'context', $array['context'] ?? null);
            $this->savePeriod($medicationStatement, 'effectivePeriod', $array['effectivePeriod'] ?? null);
            $this->saveReference($medicationStatement, 'informationSource', $array['informationSource'] ?? null);
            $this->saveMany($medicationStatement, 'derivedFrom', $array['derivedFrom'] ?? null, 'saveReference');
            $this->saveMany($medicationStatement, 'reasonCode', $array['reasonCode'] ?? null, 'saveCodeableConcept');
            $this->saveMany($medicationStatement, 'reasonReference', $array['reasonReference'] ?? null, 'saveReference');
            $this->saveMany($medicationStatement, 'note', $array['note'] ?? null, 'saveAnnotation');
            $this->saveMany($medicationStatement, 'dosage', $array['dosage'] ?? null, 'saveDosage');

            return $medicationStatement;
        }
    }

    public function generateMedicationStatement($jsonData): array
    {
        $array = $this->readJsonResource($jsonData);

        $medicationStatement = new MedicationStatement([
            'status' => $array['status'] ?? null,
            'effective_date_time' => $array['effectiveDateTime'] ?? null,
            'date_asserted' => $array['dateAsserted'] ?? null,
        ]);

        $identifier = $this->hasMany('identifier', $array['identifier'] ?? null, 'generateIdentifier');
        $basedOn = $this->hasMany('basedOn', $array['basedOn'] ?? null, 'generateReference');
        $partOf = $this->hasMany('partOf', $array['partOf'] ?? null, 'generateReference');
        $statusReason = $this->hasMany('statusReason', $array['statusReason'] ?? null, 'generateCodeableConcept');
        $category = $this->generateCodeableConcept('category', $array['category'] ?? null);
        $medicationCodeableConcept = $this->generateCodeableConcept('medicationCodeableConcept', $array['medicationCodeableConcept'] ?? null);
        $medicationReference = $this->generateReference('medicationReference', $array['medicationReference'] ?? null);
        $subject = $this->generateReference('subject', $array['subject'] ?? null);
        $context = $this->generateReference('context', $array['context'] ?? null);
        $effectivePeriod = $this->generatePeriod('effectivePeriod', $array['effectivePeriod'] ?? null);
        $informationSource = $this->generateReference('informationSource', $array['informationSource'] ?? null);
        $derivedFrom = $this->hasMany('derivedFrom', $array['derivedFrom'] ?? null, 'generateReference');
        $reasonCode = $this->hasMany('reasonCode', $array['reasonCode'] ?? null, 'generateCodeableConcept');
        $reasonReference = $this->hasMany('reasonReference', $array['reasonReference'] ?? null, 'generateReference');
        $note = $this->hasMany('note', $array['note'] ?? null, 'generateAnnotation');
        $dosage = $this->hasMany('dosage', $array['dosage'] ?? null, 'generateDosage');

        return [
            'medicationStatement' => $medicationStatement,
            'identifier' => $identifier ?? null,
            'basedOn' => $basedOn ?? null,
            'partOf' => $partOf ?? null,
            'statusReason' => $statusReason ?? null,
            'category' => $category ?? null,
            'medicationCodeableConcept' => $medicationCodeableConcept ?? null,
            'medicationReference' => $medicationReference ?? null,
            'subject' => $subject ?? null,
            'context' => $context ?? null,
            'effectivePeriod' => $effectivePeriod ?? null,
            'informationSource' => $informationSource ?? null,
            'derivedFrom' => $derivedFrom ?? null,
            'reasonCode' => $reasonCode ?? null,
            'reasonReference' => $reasonReference ?? null,
            'note' => $note ?? null,
            'dosage' => $dosage ?? null
        ];
    }

    public function saveServiceRequest(Resource $resource, $array): ServiceRequest
    {
        if (!empty($array)) {
            $serviceRequest = $resource->serviceRequest()->save($array['serviceRequest'] ?? null);
            $this->saveMany($serviceRequest, 'identifier', $array['identifier'] ?? null, 'saveIdentifier');
            $this->saveReference($serviceRequest, 'basedOn', $array['basedOn'] ?? null);
            $this->saveReference($serviceRequest, 'replaces', $array['replaces'] ?? null);
            $this->saveIdentifier($serviceRequest, 'requisition', $array['requisition'] ?? null);
            $this->saveMany($serviceRequest, 'category', $array['category'] ?? null, 'saveCodeableConcept');
            $this->saveCodeableConcept($serviceRequest, 'code', $array['code'] ?? null);
            $this->saveMany($serviceRequest, 'orderDetail', $array['orderDetail'] ?? null, 'saveCodeableConcept');
            $this->saveQuantity($serviceRequest, 'quantityQuantity', $array['quantityQuantity'] ?? null);
            $this->saveRatio($serviceRequest, 'quantityRatio', $array['quantityRatio'] ?? null);
            $this->saveRange($serviceRequest, 'quantityRange', $array['quantityRange'] ?? null);
            $this->saveReference($serviceRequest, 'subject', $array['subject'] ?? null);
            $this->saveReference($serviceRequest, 'encounter', $array['encounter'] ?? null);
            $this->savePeriod($serviceRequest, 'occurrencePeriod', $array['occurrencePeriod'] ?? null);
            $this->saveTiming($serviceRequest, 'occurrenceTiming', $array['occurrenceTiming'] ?? null);
            $this->saveCodeableConcept($serviceRequest, 'asNeededCodeableConcept', $array['asNeededCodeableConcept'] ?? null);
            $this->saveReference($serviceRequest, 'requester', $array['requester'] ?? null);
            $this->saveCodeableConcept($serviceRequest, 'performerType', $array['performerType'] ?? null);
            $this->saveMany($serviceRequest, 'performer', $array['performer'] ?? null, 'saveReference');
            $this->saveMany($serviceRequest, 'locationCode', $array['locationCode'] ?? null, 'saveCodeableConcept');
            $this->saveMany($serviceRequest, 'locationReference', $array['locationReference'] ?? null, 'saveReference');
            $this->saveMany($serviceRequest, 'reasonCode', $array['reasonCode'] ?? null, 'saveCodeableConcept');
            $this->saveMany($serviceRequest, 'reasonReference', $array['reasonReference'] ?? null, 'saveReference');
            $this->saveMany($serviceRequest, 'insurance', $array['insurance'] ?? null, 'saveReference');
            $this->saveMany($serviceRequest, 'supportingInfo', $array['supportingInfo'] ?? null, 'saveReference');
            $this->saveMany($serviceRequest, 'specimen', $array['specimen'] ?? null, 'saveReference');
            $this->saveMany($serviceRequest, 'bodySite', $array['bodySite'] ?? null, 'saveCodeableConcept');
            $this->saveMany($serviceRequest, 'note', $array['note'] ?? null, 'saveAnnotation');
            $this->saveMany($serviceRequest, 'relevantHistory', $array['relevantHistory'] ?? null, 'saveReference');

            return $serviceRequest;
        }
    }

    public function generateServiceRequest($jsonData): array
    {
        $array = $this->readJsonResource($jsonData);

        $serviceRequest = new ServiceRequest([
            'instantiates_canonical' => $array['instantiatesCanonical'] ?? null,
            'instantiates_uri' => $array['instantiatesUri'] ?? null,
            'status' => $array['status'] ?? null,
            'intent' => $array['intent'] ?? null,
            'priority' => $array['priority'] ?? null,
            'do_not_perform' => $array['doNotPerform'] ?? null,
            'occurrence_date_time' => $array['occurrenceDateTime'] ?? null,
            'as_needed_boolean' => $array['asNeededBoolean'] ?? null,
            'authored_on' => $array['authoredOn'] ?? null,
            'patient_instruction' => $array['patientInstruction'] ?? null
        ]);

        $identifier = $this->hasMany('identifier', $array['identifier'] ?? null, 'generateIdentifier');
        $basedOn = $this->hasMany('basedOn', $array['basedOn'] ?? null, 'generateReference');
        $replaces = $this->hasMany('replaces', $array['replaces'] ?? null, 'generateReference');
        $requisition = $this->generateIdentifier('requisition', $array['requisition'] ?? null);
        $category = $this->hasMany('category', $array['category'] ?? null, 'generateCodeableConcept');
        $code = $this->generateCodeableConcept('code', $array['code'] ?? null);
        $orderDetail = $this->hasMany('orderDetail', $array['orderDetail'] ?? null, 'generateCodeableConcept');
        $quantityQuantity = $this->generateQuantity('quantityQuantity', $array['quantityQuantity'] ?? null);
        $quantityRatio = $this->generateRatio('quantityRatio', $array['quantityRatio'] ?? null);
        $quantityRange = $this->generateRange('quantityRange', $array['quantityRange'] ?? null);
        $subject = $this->generateReference('subject', $array['subject'] ?? null);
        $encounter = $this->generateReference('encounter', $array['encounter'] ?? null);
        $occurrencePeriod = $this->generatePeriod('occurrencePeriod', $array['occurrencePeriod'] ?? null);
        $occurrenceTiming = $this->generateTiming('occurrenceTiming', $array['occurrenceTiming'] ?? null);
        $asNeededCodeableConcept = $this->generateCodeableConcept('asNeededCodeableConcept', $array['asNeededCodeableConcept'] ?? null);
        $requester = $this->generateReference('requester', $array['requester'] ?? null);
        $performerType = $this->generateCodeableConcept('performerType', $array['performerType'] ?? null);
        $performer = $this->hasMany('performer', $array['performer'] ?? null, 'generateReference');
        $locationCode = $this->hasMany('locationCode', $array['locationCode'] ?? null, 'generateCodeableConcept');
        $locationReference = $this->hasMany('locationReference', $array['locationReference'] ?? null, 'generateReference');
        $reasonCode = $this->hasMany('reasonCode', $array['reasonCode'] ?? null, 'generateCodeableConcept');
        $reasonReference = $this->hasMany('reasonReference', $array['reasonReference'] ?? null, 'generateReference');
        $insurance = $this->hasMany('insurance', $array['insurance'] ?? null, 'generateReference');
        $supportingInfo = $this->hasMany('supportingInfo', $array['supportingInfo'] ?? null, 'generateReference');
        $specimen = $this->hasMany('specimen', $array['specimen'] ?? null, 'generateReference');
        $bodySite = $this->hasMany('bodySite', $array['bodySite'] ?? null, 'generateCodeableConcept');
        $note = $this->hasMany('note', $array['note'] ?? null, 'generateAnnotation');
        $relevantHistory = $this->hasMany('relevantHistory', $array['relevantHistory'] ?? null, 'generateReference');

        return [
            'serviceRequest' => $serviceRequest,
            'identifier' => $identifier ?? null,
            'basedOn' => $basedOn ?? null,
            'replaces' => $replaces ?? null,
            'requisition' => $requisition ?? null,
            'category' => $category ?? null,
            'code' => $code ?? null,
            'orderDetail' => $orderDetail ?? null,
            'quantityQuantity' => $quantityQuantity ?? null,
            'quantityRatio' => $quantityRatio ?? null,
            'quantityRange' => $quantityRange ?? null,
            'subject' => $subject ?? null,
            'encounter' => $encounter ?? null,
            'occurrencePeriod' => $occurrencePeriod ?? null,
            'occurrenceTiming' => $occurrenceTiming ?? null,
            'asNeededCodeableConcept' => $asNeededCodeableConcept ?? null,
            'requester' => $requester ?? null,
            'performerType' => $performerType ?? null,
            'performer' => $performer ?? null,
            'locationCode' => $locationCode ?? null,
            'locationReference' => $locationReference ?? null,
            'reasonCode' => $reasonCode ?? null,
            'reasonReference' => $reasonReference ?? null,
            'insurance' => $insurance ?? null,
            'supportingInfo' => $supportingInfo ?? null,
            'specimen' => $specimen ?? null,
            'bodySite' => $bodySite ?? null,
            'note' => $note ?? null,
            'relevantHistory' => $relevantHistory ?? null
        ];
    }

    public function saveClinicalImpression(Resource $resource, $array): ClinicalImpression
    {
        if (!empty($array)) {
            $clinicalImpression = $resource->clinicalImpression()->save($array['clinicalImpression'] ?? null);
            $this->saveMany($clinicalImpression, 'identifier', $array['identifier'] ?? null, 'saveIdentifier');
            $this->saveCodeableConcept($clinicalImpression, 'statusReason', $array['statusReason'] ?? null);
            $this->saveCodeableConcept($clinicalImpression, 'code', $array['code'] ?? null);
            $this->saveReference($clinicalImpression, 'subject', $array['subject'] ?? null);
            $this->saveReference($clinicalImpression, 'encounter', $array['encounter'] ?? null);
            $this->savePeriod($clinicalImpression, 'effectivePeriod', $array['effectivePeriod'] ?? null);
            $this->saveReference($clinicalImpression, 'assessor', $array['assessor'] ?? null);
            $this->saveReference($clinicalImpression, 'previous', $array['previous'] ?? null);
            $this->saveMany($clinicalImpression, 'problem', $array['problem'] ?? null, 'saveReference');

            if (!empty($array['investigation'])) {
                foreach ($array['investigation'] as $i) {
                    $investigation = $clinicalImpression->investigation()->save($i['investigation'] ?? null);
                    $this->saveCodeableConcept($investigation, 'code', $i['code'] ?? null);
                    $this->saveMany($investigation, 'item', $i['item'] ?? null, 'saveReference');
                }
            }

            if (!empty($array['finding'])) {
                foreach ($array['finding'] as $f) {
                    $finding = $clinicalImpression->finding()->save($f['finding'] ?? null);
                    $this->saveCodeableConcept($finding, 'itemCodeableConcept', $f['itemCodeableConcept'] ?? null);
                    $this->saveReference($finding, 'itemReference', $f['itemReference'] ?? null);
                }
            }

            $this->saveMany($clinicalImpression, 'prognosisCodeableConcept', $array['prognosisCodeableConcept'] ?? null, 'saveCodeableConcept');
            $this->saveMany($clinicalImpression, 'prognosisReference', $array['prognosisReference'] ?? null, 'saveReference');
            $this->saveMany($clinicalImpression, 'supportingInfo', $array['supportingInfo'] ?? null, 'saveReference');
            $this->saveMany($clinicalImpression, 'note', $array['note'] ?? null, 'saveAnnotation');

            return $clinicalImpression;
        }
    }

    public function generateClinicalImpression($jsonData): array
    {
        $array = $this->readJsonResource($jsonData);

        $clinicalImpression = new ClinicalImpression([
            'status' => $array['status'] ?? null,
            'description' => $array['description'] ?? null,
            'effective_date_time' => $array['effectiveDateTime'] ?? null,
            'date' => $array['date'] ?? null,
            'protocol' => $array['protocol'] ?? null,
            'summary' => $array['summary'] ?? null,
        ]);

        $identifier = $this->hasMany('identifier', $array['identifier'] ?? null, 'generateIdentifier');
        $statusReason = $this->generateCodeableConcept('statusReason', $array['statusReason'] ?? null);
        $code = $this->generateCodeableConcept('code', $array['code'] ?? null);
        $subject = $this->generateReference('subject', $array['subject'] ?? null);
        $encounter = $this->generateReference('encounter', $array['encounter'] ?? null);
        $effectivePeriod = $this->generatePeriod('effectivePeriod', $array['effectivePeriod'] ?? null);
        $assessor = $this->generateReference('assessor', $array['assessor'] ?? null);
        $previous = $this->generateReference('previous', $array['previous'] ?? null);
        $problem = $this->hasMany('problem', $array['problem'] ?? null, 'generateReference');

        if (!empty($array['investigation'])) {
            $investigation = [];
            foreach ($array['investigation'] as $i) {
                $investigation[] = [
                    'investigation' => new ClinicalImpressionInvestigation(),
                    'code' => $this->generateCodeableConcept('code', $i['code'] ?? null),
                    'item' => $this->hasMany('item', $i['item'] ?? null, 'generateReference')
                ];
            }
        }

        if (!empty($array['finding'])) {
            $finding = [];
            foreach ($array['finding'] as $f) {
                $finding[] = [
                    'finding' => new ClinicalImpressionFinding(['basis' => $f['basis'] ?? null]),
                    'itemCodeableConcept' => $this->generateCodeableConcept('itemCodeableConcept', $f['itemCodeableConcept'] ?? null),
                    'itemReference' => $this->generateReference('itemReference', $f['itemReference'] ?? null),
                ];
            }
        }

        $prognosisCodeableConcept = $this->hasMany('prognosisCodeableConcept', $array['prognosisCodeableConcept'] ?? null, 'generateCodeableConcept');
        $prognosisReference = $this->hasMany('prognosisReference', $array['prognosisReference'] ?? null, 'generateReference');
        $supportingInfo = $this->hasMany('supportingInfo', $array['supportingInfo'] ?? null, 'generateReference');
        $note = $this->hasMany('note', $array['note'] ?? null, 'generateAnnotation');

        return [
            'clinicalImpression' => $clinicalImpression,
            'identifier' => $identifier ?? null,
            'statusReason' => $statusReason ?? null,
            'code' => $code ?? null,
            'subject' => $subject ?? null,
            'encounter' => $encounter ?? null,
            'effectivePeriod' => $effectivePeriod ?? null,
            'assessor' => $assessor ?? null,
            'previous' => $previous ?? null,
            'problem' => $problem ?? null,
            'investigation' => $investigation ?? null,
            'finding' => $finding ?? null,
            'prognosisCodeableConcept' => $prognosisCodeableConcept ?? null,
            'prognosisReference' => $prognosisReference ?? null,
            'supportingInfo' => $supportingInfo ?? null,
            'note' => $note ?? null
        ];
    }

    public function saveAllergyIntolerance(Resource $resource, $array): AllergyIntolerance
    {
        if (!empty($array)) {
            $allergyIntolerance = $resource->allergyIntolerance()->save($array['allergyIntolerance'] ?? null);
            $this->saveMany($allergyIntolerance, 'identifier', $array['identifier'] ?? null, 'saveIdentifier');
            $this->saveCodeableConcept($allergyIntolerance, 'clinicalStatus', $array['clinicalStatus'] ?? null);
            $this->saveCodeableConcept($allergyIntolerance, 'verificationStatus', $array['verificationStatus'] ?? null);
            $this->saveCodeableConcept($allergyIntolerance, 'code', $array['code'] ?? null);
            $this->saveReference($allergyIntolerance, 'patient', $array['patient'] ?? null);
            $this->saveReference($allergyIntolerance, 'encounter', $array['encounter'] ?? null);
            $this->saveAge($allergyIntolerance, 'onsetAge', $array['onsetAge'] ?? null);
            $this->savePeriod($allergyIntolerance, 'onsetPeriod', $array['onsetPeriod'] ?? null);
            $this->saveRange($allergyIntolerance, 'onsetRange', $array['onsetRange'] ?? null);
            $this->saveReference($allergyIntolerance, 'recorder', $array['recorder'] ?? null);
            $this->saveReference($allergyIntolerance, 'asserter', $array['asserter'] ?? null);
            $this->saveMany($allergyIntolerance, 'note', $array['note'] ?? null, 'saveAnnotation');

            if (!empty($array['reaction'])) {
                foreach ($array['reaction'] as $r) {
                    $reaction = $allergyIntolerance->reaction()->save($r['reaction'] ?? null);
                    $this->saveCodeableConcept($reaction, 'substance', $r['substance'] ?? null);
                    $this->saveMany($reaction, 'manifestation', $r['manifestation'] ?? null, 'saveCodeableConcept');
                    $this->saveCodeableConcept($reaction, 'exposureRoute', $r['exposureRoute'] ?? null);
                    $this->saveMany($reaction, 'note', $r['note'] ?? null, 'saveAnnotation');
                }
            }

            return $allergyIntolerance;
        }
    }

    public function generateAllergyIntolerance($jsonData): array
    {
        $array = $this->readJsonResource($jsonData);

        $allergyIntolerance = new AllergyIntolerance([
            'type' => $array['type'] ?? null,
            'category' => $array['category'] ?? null,
            'criticality' => $array['criticality'] ?? null,
            'onset_date_time' => $array['onsetDateTime'] ?? null,
            'onset_string' => $array['onsetString'] ?? null,
            'recorded_date' => $array['recordedDate'] ?? null,
            'last_occurrence' => $array['lastOccurrence'] ?? null,
        ]);

        $identifier = $this->hasMany('identifier', $array['identifier'] ?? null, 'generateIdentifier');
        $clinicalStatus = $this->generateCodeableConcept('clinicalStatus', $array['clinicalStatus'] ?? null);
        $verificationStatus = $this->generateCodeableConcept('verificationStatus', $array['verificationStatus'] ?? null);
        $code = $this->generateCodeableConcept('code', $array['code'] ?? null);
        $patient = $this->generateReference('patient', $array['patient'] ?? null);
        $encounter = $this->generateReference('encounter', $array['encounter'] ?? null);
        $onsetAge = $this->generateAge('onsetAge', $array['onsetAge'] ?? null);
        $onsetPeriod = $this->generatePeriod('onsetPeriod', $array['onsetPeriod'] ?? null);
        $onsetRange = $this->generateRange('onsetRange', $array['onsetRange'] ?? null);
        $recorder = $this->generateReference('recorder', $array['recorder'] ?? null);
        $asserter = $this->generateReference('asserter', $array['asserter'] ?? null);
        $note = $this->hasMany('note', $array['note'] ?? null, 'generateAnnotation');

        if (!empty($array['reaction'])) {
            $reaction = [];
            foreach ($array['reaction'] as $r) {
                $reaction[] = [
                    'reaction' => new AllergyIntoleranceReaction([
                        'description' => $r['description'] ?? null,
                        'onset' => $r['onset'] ?? null,
                        'severity' => $r['severity'] ?? null,
                    ]),
                    'substance' => $this->generateCodeableConcept('substance', $r['substance'] ?? null),
                    'manifestation' => $this->hasMany('manifestation', $r['manifestation'] ?? null, 'generateCodeableConcept'),
                    'exposureRoute' => $this->generateCodeableConcept('exposureRoute', $r['exposureRoute'] ?? null),
                    'note' => $this->hasMany('note', $r['note'] ?? null, 'generateAnnotation')
                ];
            }
        }

        return [
            'allergyIntolerance' => $allergyIntolerance,
            'identifier' => $identifier ?? null,
            'clinicalStatus' => $clinicalStatus ?? null,
            'verificationStatus' => $verificationStatus ?? null,
            'code' => $code ?? null,
            'patient' => $patient ?? null,
            'encounter' => $encounter ?? null,
            'onsetAge' => $onsetAge ?? null,
            'onsetPeriod' => $onsetPeriod ?? null,
            'onsetRange' => $onsetRange ?? null,
            'recorder' => $recorder ?? null,
            'asserter' => $asserter ?? null,
            'note' => $note ?? null,
            'reaction' => $reaction ?? null
        ];
    }

    public function saveComposition(Resource $resource, $array): Composition
    {
        if (!empty($array)) {
            $composition = $resource->composition()->save($array['composition'] ?? null);
            $this->saveIdentifier($composition, 'identifier', $array['identifier'] ?? null);
            $this->saveCodeableConcept($composition, 'type', $array['type'] ?? null);
            $this->saveMany($composition, 'category', $array['category'] ?? null, 'saveCodeableConcept');
            $this->saveReference($composition, 'subject', $array['subject'] ?? null);
            $this->saveReference($composition, 'encounter', $array['encounter'] ?? null);
            $this->saveMany($composition, 'author', $array['author'] ?? null, 'saveReference');

            if (!empty($array['attester'])) {
                foreach ($array['attester'] as $a) {
                    $attester = $composition->attester()->save($a['attester'] ?? null);
                    $this->saveReference($attester, 'party', $a['party'] ?? null);
                }
            }

            $this->saveReference($composition, 'custodian', $array['custodian'] ?? null);

            if (!empty($array['relatesTo'])) {
                foreach ($array['relatesTo'] as $rt) {
                    $relatesTo = $composition->relatesTo()->save($rt['relatesTo'] ?? null);
                    $this->saveIdentifier($relatesTo, 'targetIdentifier', $rt['targetIdentifier'] ?? null);
                    $this->saveReference($relatesTo, 'targetReference', $rt['targetReference'] ?? null);
                }
            }

            if (!empty($array['event'])) {
                foreach ($array['event'] as $e) {
                    $event = $composition->event()->save($e['event'] ?? null);
                    $this->saveMany($event, 'code', $e['code'] ?? null, 'saveCodeableConcept');
                    $this->savePeriod($event, 'period', $e['period'] ?? null);
                    $this->saveMany($event, 'detail', $e['detail'] ?? null, 'saveReference');
                }
            }

            $this->saveMany($composition, 'section', $array['section'] ?? null, 'saveSection');

            $this->saveComplexExtension($composition, 'documentStatus', $array['documentStatus'] ?? null);

            return $composition;
        }
    }

    private function saveSection($parent, $attribute, $array)
    {
        if (!empty($array)) {
            $composition = $parent->$attribute()->save($array['section'] ?? null);
            $this->saveCodeableConcept($composition, 'code', $array['code'] ?? null);
            $this->saveMany($composition, 'author', $array['author'] ?? null, 'saveReference');
            $this->saveReference($composition, 'focus', $array['focus'] ?? null);
            $this->saveNarrative($composition, 'text', $array['text'] ?? null);
            $this->saveCodeableConcept($composition, 'orderedBy', $array['orderedBy'] ?? null);
            $this->saveMany($composition, 'entry', $array['entry'] ?? null, 'saveReference');
            $this->saveCodeableConcept($composition, 'emptyReason', $array['emptyReason'] ?? null);
            $this->saveMany($composition, 'section', $array['childSection'] ?? null, 'saveSection');
        }
    }

    private function saveNarrative($parent, $attribute, $array)
    {
        if (!empty($array)) {
            $parent->$attribute()->save($array ?? null);
        }
    }

    public function generateComposition($jsonData): array
    {
        $array = $this->readJsonResource($jsonData);

        $composition = new Composition([
            'status' => $array['status'] ?? null,
            'date' => $array['date'] ?? null,
            'title' => $array['title'] ?? null,
            'confidentiality' => $array['confidentiality'] ?? null
        ]);

        $identifier = $this->generateIdentifier('identifier', $array['identifier'] ?? null);
        $type = $this->generateCodeableConcept('type', $array['type'] ?? null);
        $category = $this->hasMany('category', $array['category'] ?? null, 'generateCodeableConcept');
        $subject = $this->generateReference('subject', $array['subject'] ?? null);
        $encounter = $this->generateReference('encounter', $array['encounter'] ?? null);
        $author = $this->hasMany('author', $array['author'] ?? null, 'generateReference');

        if (!empty($array['attester'])) {
            $attester = [];
            foreach ($array['attester'] as $a) {
                $attester[] = [
                    'attester' => new CompositionAttester([
                        'mode' => $a['mode'] ?? null,
                        'time' => $a['time'] ?? null,
                    ]),
                    'party' => $this->generateReference('party', $a['party'] ?? null)
                ];
            }
        }

        $custodian = $this->generateReference('custodian', $array['custodian'] ?? null);

        if (!empty($array['relatesTo'])) {
            $relatesTo = [];
            foreach ($array['relatesTo'] as $rt) {
                $relatesTo[] = [
                    'relatesTo' => new CompositionRelatesTo([
                        'code' => $rt['code'] ?? null,
                    ]),
                    'targetIdentifier' => $this->generateIdentifier('targetIdentifier', $rt['targetIdentifier'] ?? null),
                    'targetReference' => $this->generateReference('targetReference', $rt['targetReference'] ?? null)
                ];
            }
        }

        if (!empty($array['event'])) {
            $event = [];
            foreach ($array['event'] as $e) {
                $event[] = [
                    'event' => new CompositionEvent(),
                    'code' => $this->hasMany('code', $e['code'] ?? null, 'generateCodeableConcept'),
                    'period' => $this->generatePeriod('period', $e['period'] ?? null),
                    'detail' => $this->hasMany('detail', $e['detail'] ?? null, 'generateReference')
                ];
            }
        }

        $section = $this->hasMany('section', $array['section'] ?? null, 'generateSection');

        if (!empty($array['extension'])) {
            foreach ($array['extension'] as $e) {
                if ($e['url'] == 'https://fhir.kemkes.go.id/r4/StructureDefinition/DocumentStatus') {
                    $documentStatus = $this->generateComplexExtension('documentStatus', $e ?? null);
                }
            }
        }

        return [
            'composition' => $composition,
            'identifier' => $identifier ?? null,
            'type' => $type ?? null,
            'category' => $category ?? null,
            'subject' => $subject ?? null,
            'encounter' => $encounter ?? null,
            'author' => $author ?? null,
            'attester' => $attester ?? null,
            'custodian' => $custodian ?? null,
            'relatesTo' => $relatesTo ?? null,
            'event' => $event ?? null,
            'section' => $section ?? null,
            'documentStatus' => $documentStatus ?? null
        ];
    }

    private function generateNarrative($attribute, $array): Narrative
    {
        if (!empty($array)) {
            $narrative = new Narrative([
                'status' => $array['status'] ?? null,
                'div' => $array['div'] ?? null,
                'attr_type' => $attribute
            ]);

            return $narrative;
        }
    }

    private function generateSection($attribute, $array): array|null
    {
        if (!empty($array)) {
            return [
                'section' => new CompositionSection([
                    'title' => $array['title'] ?? null,
                    'mode' => $array['mode'] ?? null,
                ]),
                'code' => $this->generateCodeableConcept('code', $array['code'] ?? null),
                'author' => $this->hasMany('author', $array['author'] ?? null, 'generateReference'),
                'focus' => $this->generateReference('focus', $array['focus'] ?? null),
                'text' => $this->generateNarrative('text', $array['text'] ?? null),
                'orderedBy' => $this->generateCodeableConcept('orderedBy', $array['orderedBy'] ?? null),
                'entry' => $this->hasMany('entry', $array['entry'] ?? null, 'generateReference'),
                'emptyReason' => $this->generateCodeableConcept('emptyReason', $array['emptyReason'] ?? null),
                'childSection' => $this->hasMany('section', $array['section'] ?? null, 'generateSection')
            ];
        } else {
            return null;
        }
    }

    public function saveMedicationRequest(Resource $resource, $array): MedicationRequest
    {
        if (!empty($array)) {
            $medicationRequest = $resource->medicationRequest()->save($array['medicationRequest'] ?? null);
            $this->saveMany($medicationRequest, 'identifier', $array['identifier'] ?? null, 'saveIdentifier');
            $this->saveCodeableConcept($medicationRequest, 'statusReason', $array['statusReason'] ?? null);
            $this->saveMany($medicationRequest, 'category', $array['category'] ?? null, 'saveCodeableConcept');
            $this->saveReference($medicationRequest, 'reportedReference', $array['reportedReference'] ?? null);
            $this->saveCodeableConcept($medicationRequest, 'medicationCodeableConcept', $array['medicationCodeableConcept'] ?? null);
            $this->saveReference($medicationRequest, 'medicationReference', $array['medicationReference'] ?? null);
            $this->saveReference($medicationRequest, 'subject', $array['subject'] ?? null);
            $this->saveReference($medicationRequest, 'encounter', $array['encounter'] ?? null);
            $this->saveMany($medicationRequest, 'supportingInformation', $array['supportingInformation'] ?? null, 'saveReference');
            $this->saveReference($medicationRequest, 'requester', $array['requester'] ?? null);
            $this->saveReference($medicationRequest, 'performer', $array['performer'] ?? null);
            $this->saveCodeableConcept($medicationRequest, 'performerType', $array['performerType'] ?? null);
            $this->saveReference($medicationRequest, 'recorder', $array['recorder'] ?? null);
            $this->saveMany($medicationRequest, 'reasonCode', $array['reasonCode'] ?? null, 'saveCodeableConcept');
            $this->saveMany($medicationRequest, 'reasonReference', $array['reasonReference'] ?? null, 'saveReference');
            $this->saveMany($medicationRequest, 'basedOn', $array['basedOn'] ?? null, 'saveReference');
            $this->saveIdentifier($medicationRequest, 'groupIdentifier', $array['groupIdentifier'] ?? null);
            $this->saveCodeableConcept($medicationRequest, 'courseOfTherapyType', $array['courseOfTherapyType'] ?? null);
            $this->saveMany($medicationRequest, 'insurance', $array['insurance'] ?? null, 'saveReference');
            $this->saveMany($medicationRequest, 'note', $array['note'] ?? null, 'saveAnnotation');
            $this->saveMany($medicationRequest, 'dosageInstruction', $array['dosageInstruction'] ?? null, 'saveDosage');

            if (!empty($array['dispenseRequest'])) {
                $dispenseRequest = $medicationRequest->dispenseRequest()->save($array['dispenseRequest']['dispenseRequest']);

                if (!empty($array['initialFill'])) {
                    $initialFill = $dispenseRequest->initialFill()->save($array['initialFill']['initialFill']);
                    $this->saveSimpleQuantity($initialFill, 'quantity', $array['initialFill']['quantity'] ?? null);
                    $this->saveDuration($initialFill, 'duration', $array['initialFill']['duration'] ?? null);
                }

                $this->saveDuration($dispenseRequest, 'dispenseInterval', $array['dispenseRequest']['dispenseInterval'] ?? null);
                $this->savePeriod($dispenseRequest, 'validityPeriod', $array['dispenseRequest']['validityPeriod'] ?? null);
                $this->saveSimpleQuantity($dispenseRequest, 'quantity', $array['dispenseRequest']['quantity'] ?? null);
                $this->saveDuration($dispenseRequest, 'expectedSupplyDuration', $array['dispenseRequest']['expectedSupplyDuration'] ?? null);
                $this->saveReference($dispenseRequest, 'performer', $array['dispenseRequest']['performer'] ?? null);
            }

            if (!empty($array['substitution'])) {
                $substitution = $medicationRequest->substitution()->save($array['substitution']['substitution']);
                $this->saveCodeableConcept($substitution, 'allowedCodeableConcept', $array['substitution']['allowedCodeableConcept'] ?? null);
                $this->saveCodeableConcept($substitution, 'reason', $array['substitution']['reason'] ?? null);
            }

            $this->saveReference($medicationRequest, 'priorPrescription', $array['priorPrescription'] ?? null);
            $this->saveMany($medicationRequest, 'detectedIssue', $array['detectedIssue'] ?? null, 'saveReference');
            $this->saveMany($medicationRequest, 'eventHistory', $array['eventHistory'] ?? null, 'saveReference');

            return $medicationRequest;
        }
    }

    public function generateMedicationRequest($jsonData): array
    {
        $array = $this->readJsonResource($jsonData);

        $medicationRequest = new MedicationRequest([
            'status' => $array['status'] ?? null,
            'intent' => $array['intent'] ?? null,
            'priority' => $array['priority'] ?? null,
            'do_not_perform' => $array['doNotPerform'] ?? null,
            'reported_boolean' => $array['reportedBoolean'] ?? null,
            'authored_on' => $array['authoredOn'] ?? null,
            'instantiates_canonical' => $array['instantiatesCanonical'] ?? null,
            'instantiates_uri' => $array['instantiatesUri'] ?? null,
        ]);

        $identifier = $this->hasMany('identifier', $array['identifier'] ?? null, 'generateIdentifier');
        $statusReason = $this->generateCodeableConcept('statusReason', $array['statusReason'] ?? null);
        $category = $this->hasMany('category', $array['category'] ?? null, 'generateCodeableConcept');
        $reportedReference = $this->generateReference('reportedReference', $array['reportedReference'] ?? null);
        $medicationCodeableConcept = $this->generateCodeableConcept('medicationCodeableConcept', $array['medicationCodeableConcept'] ?? null);
        $medicationReference = $this->generateReference('medicationReference', $array['medicationReference'] ?? null);
        $subject = $this->generateReference('subject', $array['subject'] ?? null);
        $encounter = $this->generateReference('encounter', $array['encounter'] ?? null);
        $supportingInformation = $this->hasMany('supportingInformation', $array['supportingInformation'] ?? null, 'generateReference');
        $requester = $this->generateReference('requester', $array['requester'] ?? null);
        $performer = $this->generateReference('performer', $array['performer'] ?? null);
        $performerType = $this->generateCodeableConcept('performerType', $array['performerType'] ?? null);
        $recorder = $this->generateReference('recorder', $array['recorder'] ?? null);
        $reasonCode = $this->hasMany('reasonCode', $array['reasonCode'] ?? null, 'generateCodeableConcept');
        $reasonReference = $this->hasMany('reasonReference', $array['reasonReference'] ?? null, 'generateReference');
        $basedOn = $this->hasMany('basedOn', $array['basedOn'] ?? null, 'generateReference');
        $groupIdentifier = $this->generateIdentifier('groupIdentifier', $array['groupIdentifier'] ?? null);
        $courseOfTherapyType = $this->generateCodeableConcept('courseOfTherapyType', $array['courseOfTherapyType'] ?? null);
        $insurance = $this->hasMany('insurance', $array['insurance'] ?? null, 'generateReference');
        $note = $this->hasMany('note', $array['note'] ?? null, 'generateAnnotation');
        $dosageInstruction = $this->hasMany('dosageInstruction', $array['dosageInstruction'] ?? null, 'generateDosage');

        if (!empty($array['dispenseRequest'])) {
            $dispenseRequest = new MedicationRequestDispenseRequest([
                'number_of_repeats_allowed' => $array['dispenseRequest']['numberOfRepeatsAllowed'] ?? null,
            ]);

            if (!empty($array['dispenseRequest']['initialFill'])) {
                $initialFill = [
                    'initialFill' => new MedicationRequestDispenseRequestInitialFill(),
                    'quantity' => $this->generateSimpleQuantity('quantity', $array['dispenseRequest']['initialFill']['quantity'] ?? null),
                    'duration' => $this->generateDuration('duration', $array['dispenseRequest']['initialFill']['duration'] ?? null)
                ];
            }

            $dispenseInterval = $this->generateDuration('dispenseInterval', $array['dispenseRequest']['dispenseInterval'] ?? null);
            $validityPeriod = $this->generatePeriod('validityPeriod', $array['dispenseRequest']['validityPeriod'] ?? null);
            $quantity = $this->generateSimpleQuantity('quantity', $array['dispenseRequest']['quantity'] ?? null);
            $expectedSupplyDuration = $this->generateDuration('expectedSupplyDuration', $array['dispenseRequest']['expectedSupplyDuration'] ?? null);
            $performer = $this->generateReference('performer', $array['dispenseRequest']['performer'] ?? null);

            $dispenseRequest = [
                'dispenseRequest' => $dispenseRequest,
                'initialFill' => $initialFill ?? null,
                'dispenseInterval' => $dispenseInterval ?? null,
                'validityPeriod' => $validityPeriod ?? null,
                'quantity' => $quantity ?? null,
                'expectedSupplyDuration' => $expectedSupplyDuration ?? null,
                'performer' => $performer ?? null
            ];
        }

        if (!empty($array['substitution'])) {
            $substitution = [
                'substitution' => new MedicationRequestSubstitution([
                    'allowed_boolean' => $array['substitution']['allowedBoolean'] ?? null,
                ]),
                'allowedCodeableConcept' => $this->generateCodeableConcept('allowedCodeableConcept', $array['substitution']['allowedCodeableConcept'] ?? null),
                'reason' => $this->generateCodeableConcept('reason', $array['substitution']['reason'] ?? null),
            ];
        }

        $priorPrescription = $this->generateReference('priorPrescription', $array['priorPrescription'] ?? null);
        $detectedIssue = $this->hasMany('detectedIssue', $array['detectedIssue'] ?? null, 'generateReference');
        $eventHistory = $this->hasMany('eventHistory', $array['eventHistory'] ?? null, 'generateReference');

        return [
            'medicationRequest' => $medicationRequest,
            'identifier' => $identifier ?? null,
            'statusReason' => $statusReason ?? null,
            'category' => $category ?? null,
            'reportedReference' => $reportedReference ?? null,
            'medicationCodeableConcept' => $medicationCodeableConcept ?? null,
            'medicationReference' => $medicationReference ?? null,
            'subject' => $subject ?? null,
            'encounter' => $encounter ?? null,
            'supportingInformation' => $supportingInformation ?? null,
            'requester' => $requester ?? null,
            'performer' => $performer ?? null,
            'performerType' => $performerType ?? null,
            'recorder' => $recorder ?? null,
            'reasonCode' => $reasonCode ?? null,
            'reasonReference' => $reasonReference ?? null,
            'basedOn' => $basedOn ?? null,
            'groupIdentifier' => $groupIdentifier ?? null,
            'courseOfTherapyType' => $courseOfTherapyType ?? null,
            'insurance' => $insurance ?? null,
            'note' => $note ?? null,
            'dosageInstruction' => $dosageInstruction ?? null,
            'dispenseRequest' => $dispenseRequest ?? null,
            'substitution' => $substitution ?? null,
            'priorPrescription' => $priorPrescription ?? null,
            'detectedIssue' => $detectedIssue ?? null,
            'eventHistory' => $eventHistory ?? null
        ];
    }

    private function saveDosage($parent, $attribute, $array)
    {
        if (!empty($array)) {
            $dosage = $parent->$attribute()->save($array['dosage']);
            $this->saveMany($dosage, 'additionalInstruction', $array['additionalInstruction'] ?? null, 'saveCodeableConcept');
            $this->saveTiming($dosage, 'timing', $array['timing'] ?? null);
            $this->saveCodeableConcept($dosage, 'asNeededCodeableConcept', $array['asNeededCodeableConcept'] ?? null);
            $this->saveCodeableConcept($dosage, 'site', $array['site'] ?? null);
            $this->saveCodeableConcept($dosage, 'route', $array['route'] ?? null);
            $this->saveCodeableConcept($dosage, 'method', $array['method'] ?? null);

            if (!empty($array['doseAndRate'])) {
                foreach ($array['doseAndRate'] as $dar) {
                    $doseAndRate = $dosage->doseAndRate()->save($dar['doseAndRate']);
                    $this->saveCodeableConcept($doseAndRate, 'type', $dar['type'] ?? null);
                    $this->saveRange($doseAndRate, 'doseRange', $dar['doseRange'] ?? null);
                    $this->saveSimpleQuantity($doseAndRate, 'doseQuantity', $dar['doseQuantity'] ?? null);
                    $this->saveRatio($doseAndRate, 'rateRatio', $dar['rateRatio'] ?? null);
                    $this->saveRange($doseAndRate, 'rateRange', $dar['rateRange'] ?? null);
                    $this->saveSimpleQuantity($doseAndRate, 'rateQuantity', $dar['rateQuantity'] ?? null);
                }
            }

            $this->saveRatio($dosage, 'maxDosePerPeriod', $array['maxDosePerPeriod'] ?? null);
            $this->saveSimpleQuantity($dosage, 'maxDosePerAdministration', $array['maxDosePerAdministration'] ?? null);
            $this->saveSimpleQuantity($dosage, 'maxDosePerLifetime', $array['maxDosePerLifetime'] ?? null);
        }
    }

    private function generateDosage($attribute, $array): array
    {
        if (!empty($array)) {
            $dosage = new Dosage([
                'sequence' => $array['sequence'] ?? null,
                'text' => $array['text'] ?? null,
                'patient_instruction' => $array['patientInstruction'] ?? null,
                'as_needed_boolean' => $array['asNeededBoolean'] ?? null,
                'attr_type' => $attribute
            ]);

            $additionalInstruction = $this->hasMany('additionalInstruction', $array['additionalInstruction'] ?? null, 'generateCodeableConcept');
            $timing = $this->generateTiming('timing', $array['timing'] ?? null);
            $asNeededCodeableConcept = $this->generateCodeableConcept('asNeededCodeableConcept', $array['asNeededCodeableConcept'] ?? null);
            $site = $this->generateCodeableConcept('site', $array['site'] ?? null);
            $route = $this->generateCodeableConcept('route', $array['route'] ?? null);
            $method = $this->generateCodeableConcept('method', $array['method'] ?? null);

            if (!empty($array['doseAndRate'])) {
                $doseAndRate = [];
                foreach ($array['doseAndRate'] as $dar) {
                    $doseAndRate[] = [
                        'doseAndRate' => new DoseAndRate(),
                        'type' => $this->generateCodeableConcept('type', $dar['type'] ?? null),
                        'doseRange' => $this->generateRange('doseRange', $dar['doseRange'] ?? null),
                        'doseQuantity' => $this->generateSimpleQuantity('doseQuantity', $dar['doseQuantity'] ?? null),
                        'rateRatio' => $this->generateRatio('rateRatio', $dar['rateRatio'] ?? null),
                        'rateRange' => $this->generateRange('rateRange', $dar['rateRange'] ?? null),
                        'rateQuantity' => $this->generateSimpleQuantity('rateQuantity', $dar['rateQuantity'] ?? null)
                    ];
                }
            }

            $maxDosePerPeriod = $this->generateRatio('maxDosePerPeriod', $array['maxDosePerPeriod'] ?? null);
            $maxDosePerAdministration = $this->generateSimpleQuantity('maxDosePerAdministration', $array['maxDosePerAdministration'] ?? null);
            $maxDosePerLifetime = $this->generateSimpleQuantity('maxDosePerLifetime', $array['maxDosePerLifetime'] ?? null);

            return [
                'dosage' => $dosage,
                'additionalInstruction' => $additionalInstruction ?? null,
                'timing' => $timing ?? null,
                'asNeededCodeableConcept' => $asNeededCodeableConcept ?? null,
                'site' => $site ?? null,
                'route' => $route ?? null,
                'method' => $method ?? null,
                'doseAndRate' => $doseAndRate ?? null,
                'maxDosePerPeriod' => $maxDosePerPeriod ?? null,
                'maxDosePerAdministration' => $maxDosePerAdministration ?? null,
                'maxDosePerLifetime' => $maxDosePerLifetime ?? null
            ];
        }
    }

    public function saveMedication(Resource $resource, $array): Medication
    {
        if (!empty($array)) {
            $medication = $resource->medication()->save($array['medication'] ?? null);
            $this->saveMany($medication, 'identifier', $array['identifier'] ?? null, 'saveIdentifier');
            $this->saveCodeableConcept($medication, 'code', $array['code'] ?? null);
            $this->saveReference($medication, 'manufacturer', $array['manufacturer'] ?? null);
            $this->saveCodeableConcept($medication, 'form', $array['form'] ?? null);
            $this->saveRatio($medication, 'amount', $array['amount'] ?? null);

            if (!empty($array['ingredient'])) {
                foreach ($array['ingredient'] as $i) {
                    $ingredient = $medication->ingredient()->save($i['ingredient'] ?? null);
                    $this->saveCodeableConcept($ingredient, 'itemCodeableConcept', $i['itemCodeableConcept'] ?? null);
                    $this->saveReference($ingredient, 'itemReference', $i['itemReference'] ?? null);
                    $this->saveRatio($ingredient, 'strength', $i['strength'] ?? null);
                }
            }

            if (!empty($array['batch'])) {
                $medication->batch()->save($array['batch']);
            }

            $this->saveExtension($medication, 'medicationType', $array['medicationType'] ?? null);

            return $medication;
        }
    }

    public function generateMedication($jsonData): array
    {
        $array = $this->readJsonResource($jsonData);

        $medication = new Medication([
            'status' => $array['status'] ?? null,
        ]);

        $identifier = $this->hasMany('identifier', $array['identifier'] ?? null, 'generateIdentifier');
        $code = $this->generateCodeableConcept('code', $array['code'] ?? null);
        $manufacturer = $this->generateReference('manufacturer', $array['manufacturer'] ?? null);
        $form = $this->generateCodeableConcept('form', $array['form'] ?? null);
        $amount = $this->generateRatio('amount', $array['amount'] ?? null);

        if (!empty($array['ingredient'])) {
            $ingredient = [];
            foreach ($array['ingredient'] as $i) {
                $ingredient[] = [
                    'ingredient' => new MedicationIngredient(['is_active' => $i['isActive'] ?? null]),
                    'itemCodeableConcept' => $this->generateCodeableConcept('itemCodeableConcept', $i['itemCodeableConcept'] ?? null),
                    'itemReference' => $this->generateReference('itemReference', $i['itemReference'] ?? null),
                    'strength' => $this->generateRatio('strength', $i['strength'] ?? null)
                ];
            }
        }

        if (!empty($array['batch'])) {
            $batch = new MedicationBatch($array['batch']);
        }

        if (!empty($array['extension'])) {
            foreach ($array['extension'] as $e) {
                if ($e['url'] == 'https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType') {
                    $medicationType = $this->generateExtension('medicationType', $e ?? null);
                }
            }
        }

        return [
            'medication' => $medication,
            'identifier' => $identifier ?? null,
            'code' => $code ?? null,
            'manufacturer' => $manufacturer ?? null,
            'form' => $form ?? null,
            'amount' => $amount ?? null,
            'ingredient' => $ingredient ?? null,
            'batch' => $batch ?? null,
            'medicationType' => $medicationType ?? null
        ];
    }

    public function saveProcedure(Resource $resource, $array): Procedure
    {
        if (!empty($array)) {
            $procedure = $resource->procedure()->save($array['procedure'] ?? null);
            $this->saveMany($procedure, 'identifier', $array['identifier'] ?? null, 'saveIdentifier');
            $this->saveMany($procedure, 'basedOn', $array['basedOn'] ?? null, 'saveReference');
            $this->saveMany($procedure, 'partOf', $array['partOf'] ?? null, 'saveReference');
            $this->saveCodeableConcept($procedure, 'statusReason', $array['statusReason'] ?? null);
            $this->saveCodeableConcept($procedure, 'category', $array['category'] ?? null);
            $this->saveCodeableConcept($procedure, 'code', $array['code'] ?? null);
            $this->saveReference($procedure, 'subject', $array['subject'] ?? null);
            $this->saveReference($procedure, 'encounter', $array['encounter'] ?? null);
            $this->savePeriod($procedure, 'performedPeriod', $array['performedPeriod'] ?? null);
            $this->saveAge($procedure, 'performedAge', $array['performedAge'] ?? null);
            $this->saveRange($procedure, 'performedRange', $array['performedRange'] ?? null);
            $this->saveReference($procedure, 'recorder', $array['recorder'] ?? null);
            $this->saveReference($procedure, 'asserter', $array['asserter'] ?? null);

            if (!empty($array['performer'])) {
                foreach ($array['performer'] as $p) {
                    $performer = $procedure->performer()->save($p['performer'] ?? null);
                    $this->saveCodeableConcept($performer, 'function', $p['function'] ?? null);
                    $this->saveReference($performer, 'actor', $p['actor'] ?? null);
                    $this->saveReference($performer, 'onBehalfOf', $p['onBehalfOf'] ?? null);
                }
            }

            $this->saveReference($procedure, 'location', $array['location'] ?? null);
            $this->saveMany($procedure, 'reasonCode', $array['reasonCode'] ?? null, 'saveCodeableConcept');
            $this->saveMany($procedure, 'reasonReference', $array['reasonReference'] ?? null, 'saveReference');
            $this->saveMany($procedure, 'bodySite', $array['bodySite'] ?? null, 'saveCodeableConcept');
            $this->saveCodeableConcept($procedure, 'outcome', $array['outcome'] ?? null);
            $this->saveMany($procedure, 'report', $array['report'] ?? null, 'saveReference');
            $this->saveMany($procedure, 'complication', $array['complication'] ?? null, 'saveCodeableConcept');
            $this->saveMany($procedure, 'complicationDetail', $array['complicationDetail'] ?? null, 'saveReference');
            $this->saveMany($procedure, 'followUp', $array['followUp'] ?? null, 'saveCodeableConcept');
            $this->saveMany($procedure, 'note', $array['note'] ?? null, 'saveAnnotation');

            if (!empty($array['focalDevice'])) {
                foreach ($array['focalDevice'] as $fd) {
                    $focalDevice = $procedure->focalDevice()->save($fd['focalDevice'] ?? null);
                    $this->saveCodeableConcept($focalDevice, 'action', $fd['action'] ?? null);
                    $this->saveReference($focalDevice, 'manipulated', $fd['manipulated'] ?? null);
                }
            }

            $this->saveMany($procedure, 'usedReference', $array['usedReference'] ?? null, 'saveReference');
            $this->saveMany($procedure, 'usedCode', $array['usedCode'] ?? null, 'saveCodeableConcept');

            return $procedure;
        }
    }

    public function generateProcedure($jsonData): array
    {
        $array = $this->readJsonResource($jsonData);

        $procedure = new Procedure([
            'instantiates_canonical' => $array['instantiatesCanonical'] ?? null,
            'instantiates_uri' => $array['instantiatesUri'] ?? null,
            'status' => $array['status'] ?? null,
            'performed_date_time' => $array['performedDateTime'] ?? null,
            'performed_string' => $array['performedString'] ?? null,
        ]);

        $identifier = $this->hasMany('identifier', $array['identifier'] ?? null, 'generateIdentifier');
        $basedOn = $this->hasMany('basedOn', $array['basedOn'] ?? null, 'generateReference');
        $partOf = $this->hasMany('partOf', $array['partOf'] ?? null, 'generateReference');
        $statusReason = $this->generateCodeableConcept('statusReason', $array['statusReason'] ?? null);
        $category = $this->generateCodeableConcept('category', $array['category'] ?? null);
        $code = $this->generateCodeableConcept('code', $array['code'] ?? null);
        $subject = $this->generateReference('subject', $array['subject'] ?? null);
        $encounter = $this->generateReference('encounter', $array['encounter'] ?? null);
        $performedPeriod = $this->generatePeriod('performedPeriod', $array['performedPeriod'] ?? null);
        $performedAge = $this->generateAge('performedAge', $array['performedAge'] ?? null);
        $performedRange = $this->generateRange('performedRange', $array['performedRange'] ?? null);
        $recorder = $this->generateReference('recorder', $array['recorder'] ?? null);
        $asserter = $this->generateReference('asserter', $array['asserter'] ?? null);

        if (!empty($array['performer'])) {
            $performer = [];
            foreach ($array['performer'] as $p) {
                $performer[] = [
                    'performer' => new ProcedurePerformer(),
                    'function' => $this->generateCodeableConcept('function', $p['function'] ?? null),
                    'actor' => $this->generateReference('actor', $p['actor'] ?? null),
                    'onBehalfOf' => $this->generateReference('onBehalfOf', $p['onBehalfOf'] ?? null)
                ];
            }
        }

        $location = $this->generateReference('location', $array['location'] ?? null);
        $reasonCode = $this->hasMany('reasonCode', $array['reasonCode'] ?? null, 'generateCodeableConcept');
        $reasonReference = $this->hasMany('reasonReference', $array['reasonReference'] ?? null, 'generateReference');
        $bodySite = $this->hasMany('bodySite', $array['bodySite'] ?? null, 'generateCodeableConcept');
        $outcome = $this->generateCodeableConcept('outcome', $array['outcome'] ?? null);
        $report = $this->hasMany('report', $array['report'] ?? null, 'generateReference');
        $complication = $this->hasMany('complication', $array['complication'] ?? null, 'generateCodeableConcept');
        $complicationDetail = $this->hasMany('complicationDetail', $array['complicationDetail'] ?? null, 'generateReference');
        $followUp = $this->hasMany('followUp', $array['followUp'] ?? null, 'generateCodeableConcept');
        $note = $this->hasMany('note', $array['note'] ?? null, 'generateAnnotation');

        if (!empty($array['focalDevice'])) {
            $focalDevice = [];
            foreach ($array['focalDevice'] as $fd) {
                $focalDevice[] = [
                    'focalDevice' => new ProcedureFocalDevice(),
                    'action' => $this->generateCodeableConcept('action', $fd['action'] ?? null),
                    'manipulated' => $this->generateReference('manipulated', $fd['manipulated'] ?? null)
                ];
            }
        }

        $usedReference = $this->hasMany('usedReference', $array['usedReference'] ?? null, 'generateReference');
        $usedCode = $this->hasMany('usedCode', $array['usedCode'] ?? null, 'generateCodeableConcept');

        return [
            'procedure' => $procedure,
            'identifier' => $identifier ?? null,
            'basedOn' => $basedOn ?? null,
            'partOf' => $partOf ?? null,
            'statusReason' => $statusReason ?? null,
            'category' => $category ?? null,
            'code' => $code ?? null,
            'subject' => $subject ?? null,
            'encounter' => $encounter ?? null,
            'performedPeriod' => $performedPeriod ?? null,
            'performedAge' => $performedAge ?? null,
            'performedRange' => $performedRange ?? null,
            'recorder' => $recorder ?? null,
            'asserter' => $asserter ?? null,
            'performer' => $performer ?? null,
            'location' => $location ?? null,
            'reasonCode' => $reasonCode ?? null,
            'reasonReference' => $reasonReference ?? null,
            'bodySite' => $bodySite ?? null,
            'outcome' => $outcome ?? null,
            'report' => $report ?? null,
            'complication' => $complication ?? null,
            'complicationDetail' => $complicationDetail ?? null,
            'followUp' => $followUp ?? null,
            'note' => $note ?? null,
            'focalDevice' => $focalDevice ?? null,
            'usedReference' => $usedReference ?? null,
            'usedCode' => $usedCode ?? null
        ];
    }

    public function saveObservation(Resource $resource, $array): Observation
    {
        if (!empty($array)) {
            $observation = $resource->observation()->save($array['observation'] ?? null);
            $this->saveMany($observation, 'identifier', $array['identifier'] ?? null, 'saveIdentifier');
            $this->saveMany($observation, 'basedOn', $array['basedOn'] ?? null, 'saveReference');
            $this->saveMany($observation, 'partOf', $array['partOf'] ?? null, 'saveReference');
            $this->saveMany($observation, 'category', $array['category'] ?? null, 'saveCodeableConcept');
            $this->saveCodeableConcept($observation, 'code', $array['code'] ?? null);
            $this->saveReference($observation, 'subject', $array['subject'] ?? null);
            $this->saveMany($observation, 'focus', $array['focus'] ?? null, 'saveReference');
            $this->saveReference($observation, 'encounter', $array['encounter'] ?? null);
            $this->savePeriod($observation, 'effectivePeriod', $array['effectivePeriod'] ?? null);
            $this->saveTiming($observation, 'effectiveTiming', $array['effectiveTiming'] ?? null);
            $this->saveMany($observation, 'performer', $array['performer'] ?? null, 'saveReference');
            $this->saveQuantity($observation, 'valueQuantity', $array['valueQuantity'] ?? null);
            $this->saveCodeableConcept($observation, 'valueCodeableConcept', $array['valueCodeableConcept'] ?? null);
            $this->saveRange($observation, 'valueRange', $array['valueRange'] ?? null);
            $this->saveRatio($observation, 'valueRatio', $array['valueRatio'] ?? null);
            $this->saveSampledData($observation, 'valueSampledData', $array['valueSampledData'] ?? null);
            $this->savePeriod($observation, 'valuePeriod', $array['valuePeriod'] ?? null);
            $this->saveCodeableConcept($observation, 'dataAbsentReason', $array['dataAbsentReason'] ?? null);
            $this->saveMany($observation, 'interpretation', $array['interpretation'] ?? null, 'saveCodeableConcept');
            $this->saveMany($observation, 'note', $array['note'] ?? null, 'saveAnnotation');
            $this->saveCodeableConcept($observation, 'bodySite', $array['bodySite'] ?? null);
            $this->saveCodeableConcept($observation, 'method', $array['method'] ?? null);
            $this->saveReference($observation, 'specimen', $array['specimen'] ?? null);
            $this->saveReference($observation, 'device', $array['device'] ?? null);

            if (!empty($array['referenceRange'])) {
                foreach ($array['referenceRange'] as $rr) {
                    $referenceRange = $observation->referenceRange()->save($rr['referenceRange'] ?? null);
                    $this->saveSimpleQuantity($referenceRange, 'low', $rr['low'] ?? null);
                    $this->saveSimpleQuantity($referenceRange, 'high', $rr['high'] ?? null);
                    $this->saveCodeableConcept($referenceRange, 'type', $rr['type'] ?? null);
                    $this->saveMany($referenceRange, 'appliesTo', $rr['appliesTo'] ?? null, 'saveCodeableConcept');
                    $this->saveRange($referenceRange, 'age', $rr['age'] ?? null);
                }
            }

            $this->saveMany($observation, 'hasMember', $array['hasMember'] ?? null, 'saveReference');
            $this->saveMany($observation, 'derivedFrom', $array['derivedFrom'] ?? null, 'saveReference');

            if (!empty($array['component'])) {
                foreach ($array['component'] as $c) {
                    $component = $observation->component()->save($c['component'] ?? null);
                    $this->saveCodeableConcept($component, 'code', $c['code'] ?? null);
                    $this->saveQuantity($component, 'valueQuantity', $c['valueQuantity'] ?? null);
                    $this->saveCodeableConcept($component, 'valueCodeableConcept', $c['valueCodeableConcept'] ?? null);
                    $this->saveRange($component, 'valueRange', $c['valueRange'] ?? null);
                    $this->saveRatio($component, 'valueRatio', $c['valueRatio'] ?? null);
                    $this->saveSampledData($component, 'valueSampledData', $c['valueSampledData'] ?? null);
                    $this->savePeriod($component, 'valuePeriod', $c['valuePeriod'] ?? null);
                    $this->saveCodeableConcept($component, 'dataAbsentReason', $c['dataAbsentReason'] ?? null);
                    $this->saveMany($component, 'interpretation', $c['interpretation'] ?? null, 'saveCodeableConcept');

                    if (!empty($c['referenceRange'])) {
                        foreach ($c['referenceRange'] as $crr) {
                            $compRefRange = $component->referenceRange()->save($crr['referenceRange'] ?? null);
                            $this->saveSimpleQuantity($compRefRange, 'low', $crr['low'] ?? null);
                            $this->saveSimpleQuantity($compRefRange, 'high', $crr['high'] ?? null);
                            $this->saveCodeableConcept($compRefRange, 'type', $crr['type'] ?? null);
                            $this->saveMany($compRefRange, 'appliesTo', $crr['appliesTo'] ?? null, 'saveCodeableConcept');
                            $this->saveRange($compRefRange, 'age', $crr['age'] ?? null);
                        }
                    }
                }
            }

            return $observation;
        }
    }

    public function generateObservation($jsonData): array
    {
        $array = $this->readJsonResource($jsonData);

        $observation = new Observation([
            'status' => $array['status'] ?? null,
            'effective_date_time' => $array['effectiveDateTime'] ?? null,
            'effective_instant' => $array['effectiveInstant'] ?? null,
            'issued' => $array['issued'] ?? null,
            'value_string' => $array['valueString'] ?? null,
            'value_boolean' => $array['valueBoolean'] ?? null,
            'value_integer' => $array['valueInteger'] ?? null,
            'value_time' => $array['valueTime'] ?? null,
            'value_date_time' => $array['valueDateTime'] ?? null
        ]);

        $identifier = $this->hasMany('identifier', $array['identifier'] ?? null, 'generateIdentifier');
        $basedOn = $this->hasMany('basedOn', $array['basedOn'] ?? null, 'generateReference');
        $partOf = $this->hasMany('partOf', $array['partOf'] ?? null, 'generateReference');
        $category = $this->hasMany('category', $array['category'] ?? null, 'generateCodeableConcept');
        $code = $this->generateCodeableConcept('code', $array['code'] ?? null);
        $subject = $this->generateReference('subject', $array['subject'] ?? null);
        $focus = $this->hasMany('focus', $array['focus'] ?? null, 'generateReference');
        $encounter = $this->generateReference('encounter', $array['encounter'] ?? null);
        $effectivePeriod = $this->generatePeriod('effectivePeriod', $array['effectivePeriod'] ?? null);
        $effectiveTiming = $this->generateTiming('effectiveTiming', $array['effectiveTiming'] ?? null);
        $performer = $this->hasMany('performer', $array['performer'] ?? null, 'generateReference');
        $valueQuantity = $this->generateQuantity('valueQuantity', $array['valueQuantity'] ?? null);
        $valueCodeableConcept = $this->generateCodeableConcept('valueCodeableConcept', $array['valueCodeableConcept'] ?? null);
        $valueRange = $this->generateRange('valueRange', $array['valueRange'] ?? null);
        $valueRatio = $this->generateRatio('valueRatio', $array['valueRatio'] ?? null);
        $valueSampledData = $this->generateSampledData('valueSampledData', $array['valueSampledData'] ?? null);
        $valuePeriod = $this->generatePeriod('valuePeriod', $array['valuePeriod'] ?? null);
        $dataAbsentReason = $this->generateCodeableConcept('dataAbsentReason', $array['dataAbsentReason'] ?? null);
        $intepretation = $this->hasMany('interpretation', $array['interpretation'] ?? null, 'generateCodeableConcept');
        $note = $this->hasMany('note', $array['note'] ?? null, 'generateAnnotation');
        $bodySite = $this->generateCodeableConcept('bodySite', $array['bodySite'] ?? null);
        $method = $this->generateCodeableConcept('method', $array['method'] ?? null);
        $specimen = $this->generateReference('specimen', $array['specimen'] ?? null);
        $device = $this->generateReference('device', $array['device'] ?? null);

        if (!empty($array['referenceRange'])) {
            $referenceRange = [];
            foreach ($array['referenceRange'] as $rr) {
                $referenceRange[] = [
                    'referenceRange' => new ObservationReferenceRange(['text' => $rr['text'] ?? null]),
                    'low' => $this->generateSimpleQuantity('low', $rr['low'] ?? null),
                    'high' => $this->generateSimpleQuantity('high', $rr['high'] ?? null),
                    'type' => $this->generateCodeableConcept('type', $rr['type'] ?? null),
                    'appliesTo' => $this->hasMany('appliesTo', $rr['appliesTo'] ?? null, 'generateCodeableConcept'),
                    'age' => $this->generateRange('age', $rr['age'] ?? null),
                ];
            }
        }

        $hasMember = $this->hasMany('hasMember', $array['hasMember'] ?? null, 'generateReference');
        $derivedFrom = $this->hasMany('derivedFrom', $array['derivedFrom'] ?? null, 'generateReference');

        if (!empty($array['component'])) {
            $component = [];
            foreach ($array['component'] as $c) {
                if (!empty($c['referenceRange'])) {
                    $compRefRange = [];
                    foreach ($c['referenceRange'] as $crr) {
                        $compRefRange[] = [
                            'referenceRange' => new ObservationComponentReferenceRange(['text' => $crr['text'] ?? null]),
                            'low' => $this->generateSimpleQuantity('low', $crr['low'] ?? null),
                            'high' => $this->generateSimpleQuantity('high', $crr['high'] ?? null),
                            'type' => $this->generateCodeableConcept('type', $crr['type'] ?? null),
                            'appliesTo' => $this->hasMany('appliesTo', $crr['appliesTo'] ?? null, 'generateCodeableConcept'),
                            'age' => $this->generateRange('age', $crr['age'] ?? null),
                        ];
                    }
                }

                $component[] = [
                    'component' => new ObservationComponent([
                        'value_string' => $c['valueString'] ?? null,
                        'value_boolean' => $c['valueBoolean'] ?? null,
                        'value_integer' => $c['valueInteger'] ?? null,
                        'value_time' => $c['valueTime'] ?? null,
                        'value_date_time' => $c['valueDateTime'] ?? null
                    ]),
                    'code' => $this->generateCodeableConcept('code', $c['code'] ?? null),
                    'valueQuantity' => $this->generateQuantity('valueQuantity', $c['valueQuantity'] ?? null),
                    'valueCodeableConcept' => $this->generateCodeableConcept('valueCodeableConcept', $c['valueCodeableConcept'] ?? null),
                    'valueRange' => $this->generateRange('valueRange', $c['valueRange'] ?? null),
                    'valueRatio' => $this->generateRatio('valueRatio', $c['valueRatio'] ?? null),
                    'valueSampledData' => $this->generateSampledData('valueSampledData', $c['valueSampledData'] ?? null),
                    'valuePeriod' => $this->generatePeriod('valuePeriod', $c['valuePeriod'] ?? null),
                    'dataAbsentReason' => $this->generateCodeableConcept('dataAbsentReason', $c['dataAbsentReason'] ?? null),
                    'interpretation' => $this->hasMany('interpretation', $c['interpretation'] ?? null, 'generateCodeableConcept'),
                    'referenceRange' => $compRefRange ?? null
                ];
            }
        }

        return [
            'observation' => $observation,
            'identifier' => $identifier ?? null,
            'basedOn' => $basedOn ?? null,
            'partOf' => $partOf ?? null,
            'category' => $category ?? null,
            'code' => $code ?? null,
            'subject' => $subject ?? null,
            'focus' => $focus ?? null,
            'encounter' => $encounter ?? null,
            'effectivePeriod' => $effectivePeriod ?? null,
            'effectiveTiming' => $effectiveTiming ?? null,
            'performer' => $performer ?? null,
            'valueQuantity' => $valueQuantity ?? null,
            'valueCodeableConcept' => $valueCodeableConcept ?? null,
            'valueRange' => $valueRange ?? null,
            'valueRatio' => $valueRatio ?? null,
            'valueSampledData' => $valueSampledData ?? null,
            'valuePeriod' => $valuePeriod ?? null,
            'dataAbsentReason' => $dataAbsentReason ?? null,
            'interpretation' => $intepretation ?? null,
            'note' => $note ?? null,
            'bodySite' => $bodySite ?? null,
            'method' => $method ?? null,
            'specimen' => $specimen ?? null,
            'device' => $device ?? null,
            'referenceRange' => $referenceRange ?? null,
            'hasMember' => $hasMember ?? null,
            'derivedFrom' => $derivedFrom ?? null,
            'component' => $component ?? null
        ];
    }

    private function saveSampledData($parent, $attribute, $array)
    {
        if (!empty($array)) {
            $sampledData = $parent->$attribute()->save($array['sampledData']);
            $this->saveSimpleQuantity($sampledData, 'origin', $array['origin'] ?? null);
        }
    }

    private function generateSampledData($attribute, $array)
    {
        if (!empty($array)) {
            return [
                'sampledData' => new SampledData([
                    'period' => $array['period'] ?? null,
                    'factor' => $array['factor'] ?? null,
                    'lower_limit' => $array['lowerLimit'] ?? null,
                    'upper_limit' => $array['upperLimit'] ?? null,
                    'dimensions' => $array['dimensions'] ?? null,
                    'data' => $array['data'] ?? null,
                    'attr_type' => $attribute
                ]),
                'origin' => $this->generateSimpleQuantity('origin', $array['origin'] ?? null)
            ];
        } else {
            return null;
        }
    }

    private function saveRatio($parent, $attribute, $array)
    {
        if (!empty($array)) {
            $ratio = $parent->$attribute()->save($array['ratio']);
            $this->saveQuantity($ratio, 'numerator', $array['numerator'] ?? null);
            $this->saveQuantity($ratio, 'denominator', $array['denominator'] ?? null);
        }
    }

    private function generateRatio($attribute, $array)
    {
        if (!empty($array)) {
            return [
                'ratio' => new Ratio(['attr_type' => $attribute]),
                'numerator' => $this->generateQuantity('numerator', $array['numerator'] ?? null),
                'denominator' => $this->generateQuantity('denominator', $array['denominator'] ?? null)
            ];
        } else {
            return null;
        }
    }

    private function saveQuantity($parent, $attribute, $array)
    {
        if (!empty($array)) {
            $parent->$attribute()->save($array);
        }
    }

    private function generateQuantity($attribute, $array)
    {
        if (!empty($array)) {
            return new Quantity(
                array_merge(
                    $array,
                    ['attr_type' => $attribute]
                )
            );
        } else {
            return null;
        }
    }

    private function saveTiming($parent, $attribute, $array)
    {
        if (!empty($array)) {
            $timing = $parent->$attribute()->save($array['timing']);

            if (!empty($array['repeat'])) {
                if (!empty($array['repeat']['repeat'])) {
                    $repeat = $timing->repeat()->save($array['repeat']['repeat']);
                    $this->saveDuration($repeat, 'boundsDuration', $array['repeat']['boundsDuration'] ?? null);
                    $this->saveRange($repeat, 'boundsRange', $array['repeat']['boundsRange'] ?? null);
                    $this->savePeriod($repeat, 'boundsPeriod', $array['repeat']['boundsPeriod'] ?? null);
                }
            }

            $this->saveCodeableConcept($timing, 'code', $array['code'] ?? null);
        }
    }

    private function generateTiming($attribute, $array)
    {
        if (!empty($array)) {
            $timing = new Timing([
                'event' => $array['event'] ?? null,
                'attr_type' => $attribute
            ]);
            $timingRepeat = [
                'repeat' => new TimingRepeat([
                    'count' => $array['count'] ?? null,
                    'count_max' => $array['countMax'] ?? null,
                    'duration' => $array['duration'] ?? null,
                    'duration_max' => $array['durationMax'] ?? null,
                    'duration_unit' => $array['durationUnit'] ?? null,
                    'frequency' => $array['frequency'] ?? null,
                    'frequency_max' => $array['frequencyMax'] ?? null,
                    'period' => $array['period'] ?? null,
                    'period_max' => $array['periodMax'] ?? null,
                    'period_unit' => $array['periodUnit'] ?? null,
                    'day_of_week' => $array['dayOfWeek'] ?? null,
                    'time_of_day' => $array['timeOfDay'] ?? null,
                    'when' => $array['when'] ?? null,
                    'offset' => $array['offset'] ?? null,
                ]),
                'boundsDuration' => $this->generateDuration('boundsDuration', $array['boundsDuration'] ?? null),
                'boundsRange' => $this->generateRange('boundsRange', $array['boundsRange'] ?? null),
                'boundsPeriod' => $this->generatePeriod('boundsPeriod', $array['boundsPeriod'] ?? null),
            ];
            $code = $this->generateCodeableConcept('code', $array['code'] ?? null);

            return [
                'timing' => $timing,
                'repeat' => $timingRepeat,
                'code' => $code
            ];
        } else {
            return null;
        }
    }

    public function saveCondition(Resource $resource, $array): Condition
    {
        if (!empty($array)) {
            $condition = $resource->condition()->save($array['condition'] ?? null);
            $this->saveMany($condition, 'identifier', $array['identifier'] ?? null, 'saveIdentifier');
            $this->saveCodeableConcept($condition, 'clinicalStatus', $array['clinicalStatus'] ?? null);
            $this->saveCodeableConcept($condition, 'verificationStatus', $array['verificationStatus'] ?? null);
            $this->saveMany($condition, 'category', $array['category'] ?? null, 'saveCodeableConcept');
            $this->saveCodeableConcept($condition, 'severity', $array['severity'] ?? null);
            $this->saveCodeableConcept($condition, 'code', $array['code'] ?? null);
            $this->saveMany($condition, 'bodySite', $array['bodySite'] ?? null, 'saveCodeableConcept');
            $this->saveReference($condition, 'subject', $array['subject'] ?? null);
            $this->saveReference($condition, 'encounter', $array['encounter'] ?? null);
            $this->saveAge($condition, 'onsetAge', $array['onsetAge'] ?? null);
            $this->savePeriod($condition, 'onsetPeriod', $array['onsetPeriod'] ?? null);
            $this->saveRange($condition, 'onsetRange', $array['onsetRange'] ?? null);
            $this->saveAge($condition, 'abatementAge', $array['abatementAge'] ?? null);
            $this->savePeriod($condition, 'abatementPeriod', $array['abatementPeriod'] ?? null);
            $this->saveRange($condition, 'abatementRange', $array['abatementRange'] ?? null);
            $this->saveReference($condition, 'recorder', $array['recorder'] ?? null);
            $this->saveReference($condition, 'asserter', $array['asserter'] ?? null);

            if (!empty($array['stage'])) {
                foreach ($array['stage'] as $s) {
                    $stage = $condition->stage()->save($s['stage'] ?? null);
                    $this->saveCodeableConcept($stage, 'summary', $s['summary'] ?? null);
                    $this->saveMany($stage, 'assessment', $s['assessment'] ?? null, 'saveReference');
                    $this->saveCodeableConcept($stage, 'type', $s['type'] ?? null);
                }
            }

            if (!empty($array['evidence'])) {
                foreach ($array['evidence'] as $e) {
                    $evidence = $condition->evidence()->save($e['evidence'] ?? null);
                    $this->saveMany($evidence, 'code', $e['code'] ?? null, 'saveCodeableConcept');
                    $this->saveMany($evidence, 'detail', $e['detail'] ?? null, 'saveReference');
                }
            }

            $this->saveMany($condition, 'note', $array['note'] ?? null, 'saveAnnotation');

            return $condition;
        }
    }

    public function generateCondition($jsonData)
    {
        $array = $this->readJsonResource($jsonData);

        $condition = new Condition([
            'onset_date_time' => $array['onsetDateTime'] ?? null,
            'onset_string' => $array['onsetString'] ?? null,
            'abatement_date_time' => $array['abatementDateTime'] ?? null,
            'abatement_string' => $array['abatementString'] ?? null,
            'recorded_date' => $array['recordedDate'] ?? null
        ]);

        $identifier = $this->hasMany('identifier', $array['identifier'] ?? null, 'generateIdentifier');
        $clinicalStatus = $this->generateCodeableConcept('clinicalStatus', $array['clinicalStatus'] ?? null);
        $verificationStatus = $this->generateCodeableConcept('verificationStatus', $array['verificationStatus'] ?? null);
        $category = $this->hasMany('category', $array['category'] ?? null, 'generateCodeableConcept');
        $severity = $this->generateCodeableConcept('severity', $array['severity'] ?? null);
        $code = $this->generateCodeableConcept('code', $array['code'] ?? null);
        $bodySite = $this->hasMany('bodySite', $array['bodySite'] ?? null, 'generateCodeableConcept');
        $subject = $this->generateReference('subject', $array['subject'] ?? null);
        $encounter = $this->generateReference('encounter', $array['encounter'] ?? null);
        $onsetAge = $this->generateAge('onsetAge', $array['onsetAge'] ?? null);
        $onsetPeriod = $this->generatePeriod('onsetPeriod', $array['onsetPeriod'] ?? null);
        $onsetRange = $this->generateRange('onsetRange', $array['onsetRange'] ?? null);
        $abatementAge = $this->generateAge('abatementAge', $array['abatementAge'] ?? null);
        $abatementPeriod = $this->generatePeriod('abatementPeriod', $array['abatementPeriod'] ?? null);
        $abatementRange = $this->generateRange('abatementRange', $array['abatementRange'] ?? null);
        $recorder = $this->generateReference('recorder', $array['recorder'] ?? null);
        $asserter = $this->generateReference('asserter', $array['asserter'] ?? null);

        if (!empty($array['stage'])) {
            $stage = [];
            foreach ($array['stage'] as $s) {
                $stage[] = [
                    'stage' => new ConditionStage(),
                    'summary' => $this->generateCodeableConcept('summary', $s['summary'] ?? null),
                    'assessment' => $this->hasMany('assessment', $s['assessment'] ?? null, 'generateReference'),
                    'type' => $this->generateCodeableConcept('type', $s['type'] ?? null)
                ];
            }
        }

        if (!empty($array['evidence'])) {
            $evidence = [];
            foreach ($array['evidence'] as $e) {
                $evidence[] = [
                    'evidence' => new ConditionEvidence(),
                    'code' => $this->hasMany('code', $e['code'] ?? null, 'generateCodeableConcept'),
                    'detail' => $this->hasMany('detail', $e['detail'] ?? null, 'generateReference')
                ];
            }
        }

        $note = $this->hasMany('note', $array['note'] ?? null, 'generateAnnotation');

        return [
            'condition' => $condition,
            'identifier' => $identifier ?? null,
            'clinicalStatus' => $clinicalStatus ?? null,
            'verificationStatus' => $verificationStatus ?? null,
            'category' => $category ?? null,
            'severity' => $severity ?? null,
            'code' => $code ?? null,
            'bodySite' => $bodySite ?? null,
            'subject' => $subject ?? null,
            'encounter' => $encounter ?? null,
            'onsetAge' => $onsetAge ?? null,
            'onsetPeriod' => $onsetPeriod ?? null,
            'onsetRange' => $onsetRange ?? null,
            'abatementAge' => $abatementAge ?? null,
            'abatementPeriod' => $abatementPeriod ?? null,
            'abatementRange' => $abatementRange ?? null,
            'recorder' => $recorder ?? null,
            'asserter' => $asserter ?? null,
            'stage' => $stage ?? null,
            'evidence' => $evidence ?? null,
            'note' => $note ?? null
        ];
    }

    public function saveEncounter(Resource $resource, $array): Encounter
    {
        if (!empty($array)) {
            $encounter = $resource->encounter()->save($array['encounter'] ?? null);
            $this->saveMany($encounter, 'identifier', $array['identifier'] ?? null, 'saveIdentifier');

            if (!empty($array['statusHistory'])) {
                foreach ($array['statusHistory'] as $sh) {
                    $statusHistory = $encounter->statusHistory()->save($sh['statusHistory'] ?? null);
                    $this->savePeriod($statusHistory, 'period', $sh['period'] ?? null);
                }
            }

            $this->saveCoding($encounter, 'class', $array['class'] ?? null);

            if (!empty($array['classHistory'])) {
                foreach ($array['classHistory'] as $ch) {
                    $classHistory = $encounter->classHistory()->save($ch['classHistory'] ?? null);
                    $this->saveCoding($classHistory, 'class', $ch['class'] ?? null);
                    $this->savePeriod($classHistory, 'period', $ch['period'] ?? null);
                }
            }

            $this->saveMany($encounter, 'type', $array['type'] ?? null, 'saveCodeableConcept');
            $this->saveCodeableConcept($encounter, 'serviceType', $array['serviceType'] ?? null);
            $this->saveCodeableConcept($encounter, 'priority', $array['priority'] ?? null);
            $this->saveReference($encounter, 'subject', $array['subject'] ?? null);
            $this->saveMany($encounter, 'episodeOfCare', $array['episodeOfCare'] ?? null, 'saveReference');
            $this->saveMany($encounter, 'basedOn', $array['basedOn'] ?? null, 'saveReference');

            if (!empty($array['participant'])) {
                foreach ($array['participant'] as $p) {
                    $participant = $encounter->participant()->save($p['participant'] ?? null);
                    $this->saveMany($participant, 'type', $p['type'] ?? null, 'saveCodeableConcept');
                    $this->savePeriod($participant, 'period', $p['period'] ?? null);
                    $this->saveReference($participant, 'individual', $p['individual'] ?? null, 'saveReference');
                }
            }

            $this->saveMany($encounter, 'appointment', $array['appointment'] ?? null, 'saveReference');
            $this->savePeriod($encounter, 'period', $array['period'] ?? null);
            $this->saveDuration($encounter, 'length', $array['length'] ?? null);
            $this->saveMany($encounter, 'reasonCode', $array['reasonCode'] ?? null, 'saveCodeableConcept');
            $this->saveMany($encounter, 'reasonReference', $array['reasonReference'] ?? null, 'saveReference');

            if (!empty($array['diagnosis'])) {
                foreach ($array['diagnosis'] as $d) {
                    $diagnosis = $encounter->diagnosis()->save($d['diagnosis'] ?? null);
                    $this->saveReference($diagnosis, 'condition', $d['condition'] ?? null);
                    $this->saveCodeableConcept($diagnosis, 'use', $d['use'] ?? null);
                }
            }

            if (!empty($array['hospitalization'])) {
                $hospitalization = $encounter->hospitalization()->save($array['hospitalization']['hospitalization'] ?? null);
                $this->saveIdentifier($hospitalization, 'preAdmissionIdentifier', $array['hospitalization']['preAdmissionIdentifier'] ?? null);
                $this->saveReference($hospitalization, 'origin', $array['hospitalization']['origin'] ?? null, 'saveReference');
                $this->saveCodeableConcept($hospitalization, 'admitSource', $array['hospitalization']['admitSource'] ?? null);
                $this->saveCodeableConcept($hospitalization, 'reAdmission', $array['hospitalization']['reAdmission'] ?? null);
                $this->saveMany($hospitalization, 'dietPreference', $array['hospitalization']['dietPreference'] ?? null, 'saveCodeableConcept');
                $this->saveMany($hospitalization, 'specialCourtesy', $array['hospitalization']['specialCourtesy'] ?? null, 'saveCodeableConcept');
                $this->saveMany($hospitalization, 'specialArrangement', $array['hospitalization']['specialArrangement'] ?? null, 'saveCodeableConcept');
                $this->saveReference($hospitalization, 'destination', $array['hospitalization']['destination'] ?? null);
                $this->saveCodeableConcept($hospitalization, 'dischargeDisposition', $array['hospitalization']['dischargeDisposition'] ?? null);
            }

            if (!empty($array['location'])) {
                foreach ($array['location'] as $l) {
                    $location = $encounter->location()->save($l['encLocation'] ?? null);
                    $this->saveReference($location, 'location', $l['location'] ?? null, 'saveReference');
                    $this->saveCodeableConcept($location, 'physicalType', $l['physicalType'] ?? null);
                    $this->savePeriod($location, 'period', $l['period'] ?? null);

                    if (!empty($l['serviceClass'])) {
                        $this->saveComplexExtension($location, 'serviceClass', $l['serviceClass'] ?? null);
                    }
                }
            }

            $this->saveReference($encounter, 'serviceProvider', $array['serviceProvider'] ?? null);
            $this->saveReference($encounter, 'partOf', $array['partOf'] ?? null);

            return $encounter;
        }
    }
    public function generateEncounter(string $jsonData): array
    {
        $array = $this->readJsonResource($jsonData);

        $encounter = new Encounter([
            'status' => $array['status'] ?? null,
        ]);

        $identifier = $this->hasMany('identifier', $array['identifier'] ?? null, 'generateIdentifier');

        if (!empty($array['statusHistory'])) {
            $statusHistory = [];
            foreach ($array['statusHistory'] as $sh) {
                $statusHistory[] = [
                    'statusHistory' => new EncounterStatusHistory(['status' => $sh['status'] ?? null]),
                    'period' => $this->generatePeriod('period', $sh['period'] ?? null)
                ];
            }
        }

        $class = $this->generateCoding('class', $array['class'] ?? null);

        if (!empty($array['classHistory'])) {
            $classHistory = [];
            foreach ($array['classHistory'] as $ch) {
                $classHistory[] = [
                    'classHistory' => new EncounterClassHistory(),
                    'class' => $this->generateCoding('class', $ch['class'] ?? null),
                    'period' => $this->generatePeriod('period', $ch['period'] ?? null)
                ];
            }
        }

        $type = $this->hasMany('type', $array['type'] ?? null, 'generateCodeableConcept');
        $serviceType = $this->generateCodeableConcept('serviceType', $array['serviceType'] ?? null);
        $priority = $this->generateCodeableConcept('priority', $array['priority'] ?? null);
        $subject = $this->generateReference('subject', $array['subject'] ?? null);
        $episodeOfCare = $this->hasMany('episodeOfCare', $array['episodeOfCare'] ?? null, 'generateReference');
        $basedOn = $this->hasMany('basedOn', $array['basedOn'] ?? null, 'generateReference');

        if (!empty($array['participant'])) {
            $participant = [];
            foreach ($array['participant'] as $p) {
                $participant[] = [
                    'participant' => new EncounterParticipant(),
                    'type' => $this->hasMany('type', $p['type'] ?? null, 'generateCodeableConcept'),
                    'period' => $this->generatePeriod('period', $p['period'] ?? null),
                    'individual' => $this->generateReference('individual', $p['individual'] ?? null)
                ];
            }
        }

        $appointment = $this->hasMany('appointment', $array['appointment'] ?? null, 'generateReference');
        $period = $this->generatePeriod('period', $array['period'] ?? null);
        $duration = $this->generateDuration('length', $array['length'] ?? null);
        $reasonCode = $this->hasMany('reasonCode', $array['reasonCode'] ?? null, 'generateCodeableConcept');
        $reasonReference = $this->hasMany('reasonReference', $array['reasonReference'] ?? null, 'generateReference');

        if (!empty($array['diagnosis'])) {
            $diagnosis = [];
            foreach ($array['diagnosis'] as $d) {
                $diagnosis[] = [
                    'diagnosis' => new EncounterDiagnosis(['rank' => $d['rank'] ?? null]),
                    'condition' => $this->generateReference('condition', $d['condition'] ?? null),
                    'use' => $this->generateCodeableConcept('use', $d['use'] ?? null)
                ];
            }
        }

        if (!empty($array['hospitalization'])) {
            $hospitalization = [
                'hospitalization' => new EncounterHospitalization(),
                'preAdmissionIdentifier' => $this->generateIdentifier('preAdmissionIdentifier', $array['hospitalization']['preAdmissionIdentifier'] ?? null),
                'origin' => $this->generateReference('origin', $array['hospitalization']['origin'] ?? null),
                'admitSource' => $this->generateCodeableConcept('admitSource', $array['hospitalization']['admitSource'] ?? null),
                'reAdmission' => $this->generateCodeableConcept('reAdmission', $array['hospitalization']['reAdmission'] ?? null),
                'dierPreference' => $this->hasMany('dietPreference', $array['hospitalization']['dietPreference'] ?? null, 'generateCodeableConcept'),
                'sepcialCourtesy' => $this->hasMany('specialCourtesy', $array['hospitalization']['specialCourtesy'] ?? null, 'generateCodeableConcept'),
                'specialArrangmenet' => $this->hasMany('specialArrangement', $array['hospitalization']['specialArrangement'] ?? null, 'generateCodeableConcept'),
                'destination' => $this->generateReference('destination', $array['hospitalization']['destination'] ?? null),
                'dischargeDisposition' => $this->generateCodeableConcept('dischargeDisposition', $array['hospitalization']['dischargeDisposition'] ?? null)
            ];
        }

        if (!empty($array['location'])) {
            $locations = [];
            foreach ($array['location'] as $l) {
                $encLocation = new EncounterLocation(['status' => $array['location']['status'] ?? null]);
                $location = $this->generateReference('location', $l['location'] ?? null);
                $physicalType = $this->generateCodeableConcept('physicalType', $l['physicalType'] ?? null);
                $period = $this->generatePeriod('period', $l['period'] ?? null);

                if (!empty($l['extension'])) {
                    foreach ($l['extension'] as $e) {
                        if ($e['url'] == 'https://fhir.kemkes.go.id/r4/StructureDefinition/ServiceClass') {
                            $serviceClass = $this->generateComplexExtension('serviceClass', $e);
                        }
                    }
                }
                $locations[] = [
                    'encLocation' => $encLocation,
                    'location' => $location ?? null,
                    'physicalType' => $physicalType ?? null,
                    'period' => $period ?? null,
                    'serviceClass' => $serviceClass ?? null
                ];
            }
        }

        $serviceProvider = $this->generateReference('serviceProvider', $array['serviceProvider'] ?? null);
        $partOf = $this->generateReference('partOf', $array['partOf'] ?? null);

        return [
            'encounter' => $encounter,
            'identifier' => $identifier ?? null,
            'statusHistory' => $statusHistory ?? null,
            'class' => $class ?? null,
            'classHistory' => $classHistory ?? null,
            'type' => $type ?? null,
            'serviceType' => $serviceType ?? null,
            'priority' => $priority ?? null,
            'subject' => $subject ?? null,
            'episodeOfCare' => $episodeOfCare ?? null,
            'basedOn' => $basedOn ?? null,
            'participant' => $participant ?? null,
            'appointment' => $appointment ?? null,
            'period' => $period ?? null,
            'duration' => $duration ?? null,
            'reasonCode' => $reasonCode ?? null,
            'reasonReference' => $reasonReference ?? null,
            'hospitalization' => $hospitalization ?? null,
            'location' => $locations ?? null,
            'serviceProvider' => $serviceProvider ?? null,
            'partOf' => $partOf ?? null
        ];
    }

    private function saveDuration($model, $attribute, $array)
    {
        if (!empty($array)) {
            $model->$attribute()->save($array);
        }
    }

    private function generateDuration($attribute, $array)
    {
        if (!empty($array)) {
            return new Duration(
                array_merge(
                    $array,
                    ['attr_type' => $attribute]
                )
            );
        } else {
            return null;
        }
    }

    public function savePatient(Resource $resource, $array): Patient
    {
        if (!empty($array)) {
            $patient = $resource->patient()->save($array['patient'] ?? null);
            $this->saveMany($patient, 'identifier', $array['identifier'] ?? null, 'saveIdentifier');
            $this->saveMany($patient, 'name', $array['name'] ?? null, 'saveHumanName');
            $this->saveMany($patient, 'telecom', $array['telecom'] ?? null, 'saveContactPoint');
            $this->saveMany($patient, 'address', $array['address'] ?? null, 'saveAddress');
            $this->saveCodeableConcept($patient, 'maritalStatus', $array['maritalStatus'] ?? null);
            $this->saveMany($patient, 'photo', $array['photo'] ?? null, 'saveAttachment');

            if (!empty($array['contact'])) {
                foreach ($array['contact'] as $c) {
                    $contact = $patient->contact()->save($c['contact'] ?? null);
                    $this->saveCodeableConcept($contact, 'relationship', $c['relationship'] ?? null);
                    $this->saveHumanName($contact, 'name', $c['name'] ?? null);
                    $this->saveMany($contact, 'telecom', $c['telecom'] ?? null, 'saveContactPoint');
                    $this->saveAddress($contact, 'address', $c['address'] ?? null);
                    $this->saveReference($contact, 'organization', $c['organization'] ?? null);
                    $this->savePeriod($contact, 'period', $c['period'] ?? null);
                }
            }

            if (!empty($array['communication'])) {
                foreach ($array['communication'] as $c) {
                    $communication = $patient->communication()->save($c['communication'] ?? null);
                    $this->saveCodeableConcept($communication, 'language', $c['language'] ?? null);
                }
            }

            $this->saveMany($patient, 'generalPractitioner', $array['generalPractitioner'] ?? null, 'saveReference');
            $this->saveReference($patient, 'managingOrganization', $array['managingOrganization'] ?? null);

            if (!empty($array['link'])) {
                foreach ($array['link'] as $l) {
                    $link = $patient->link()->save($l['link'] ?? null);
                    $this->saveReference($link, 'other', $l['other'] ?? null);
                }
            }

            $this->saveComplexExtension($patient, 'citizenship', $array['citizenship'] ?? null);
            $this->saveExtension($patient, 'birthPlace', $array['birthPlace'] ?? null);
            $this->saveExtension($patient, 'religion', $array['religion'] ?? null);
            $this->saveExtension($patient, 'citizenshipStatus', $array['citizenshipStatus'] ?? null);
            $this->saveExtension($patient, 'extendedBirthDate', $array['extendedBirthDate'] ?? null);

            return $patient;
        }
    }

    public function generatePatient(string $jsonData): array
    {
        $array = $this->readJsonResource($jsonData);

        $patient = new Patient([
            'active' => $array['active'] ?? null,
            'gender' => $array['gender'] ?? 'unknown',
            'birth_date' => $array['birthDate'] ?? null,
            'deceased_boolean' => $array['deceasedBoolean'] ?? null,
            'deceased_date_time' => $array['deceasedDateTime'] ?? null,
            'multiple_birth_boolean' => $array['multipleBirthBoolean'] ?? null,
            'multiple_birth_integer' => $array['multipleBirthInteger'] ?? null,
        ]);

        $identifier = $this->hasMany('identifier', $array['identifier'] ?? null, 'generateIdentifier');
        $name = $this->hasMany('name', $array['name'] ?? null, 'generateHumanName');
        $telecom = $this->hasMany('telecom', $array['telecom'] ?? null, 'generateContactPoint');
        $address = $this->hasMany('address', $array['address'] ?? null, 'generateAddress');
        $maritalStatus = $this->generateCodeableConcept('maritalStatus', $array['maritalStatus'] ?? null,);
        $photo = $this->hasMany('photo', $array['photo'] ?? null, 'generateAttachment');

        if (!empty($array['contact'])) {
            $contacts = [];
            foreach ($array['contact'] as $c) {
                $contacts[] = [
                    'contact' => new PatientContact(['gender' => $c['gender'] ?? null]),
                    'relationship' => $this->generateCodeableConcept('relationship', $c['relationship'] ?? null),
                    'name' => $this->generateHumanName('name', $c['name'] ?? null),
                    'telecom' => $this->hasMany('telecom', $c['telecom'] ?? null, 'generateContactPoint'),
                    'address' => $this->generateAddress('address', $c['address'] ?? null),
                    'organization' => $this->generateReference('organization', $c['organization'] ?? null),
                    'period' => $this->generatePeriod('period', $c['period'] ?? null)
                ];
            }
        }

        if (!empty($array['communication'])) {
            $communications = [];
            foreach ($array['communication'] as $c) {
                $communications[] = [
                    'communication' => new PatientCommunication(['preferred' => $c['preferred'] ?? null]),
                    'language' => $this->generateCodeableConcept('language', $c['language'] ?? null)
                ];
            }
        }

        $generalPractitioner = $this->hasMany('generalPractitioner', $array['generalPractitioner'] ?? null, 'generateReference');
        $managingOrganization = $this->generateReference('managingOrganization', $array['managingOrganization'] ?? null);

        if (!empty($array['link'])) {
            $links = [];
            foreach ($array['link'] as $l) {
                $links[] = [
                    'link' => new PatientLink(['type' => $l['type'] ?? null]),
                    'other' => $this->generateReference('other', $l['other'] ?? null)
                ];
            }
        }

        if (!empty($array['extension'])) {
            foreach ($array['extension'] as $e) {
                if ($e['url'] == 'https://fhir.kemkes.go.id/r4/StructureDefinition/patient-citizenship') {
                    $citizenship = $this->generateComplexExtension('citizenship', $e ?? null);
                } elseif ($e['url'] == 'https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace') {
                    $birthPlace = $this->generateExtension('birthPlace', $e ?? null);
                } elseif ($e['url'] == 'https://fhir.kemkes.go.id/r4/StructureDefinition/patient-religion') {
                    $religion = $this->generateExtension('religion', $e ?? null);
                } elseif ($e['url'] == 'https://fhir.kemkes.go.id/r4/StructureDefinition/citizenshipStatus') {
                    $citizenshipStatus = $this->generateExtension('citizenshipStatus', $e ?? null);
                }
            }
        }

        if (!empty($array['_birthDate'])) {
            if (!empty($array['_birthDate']['extension'])) {
                foreach ($array['_birthDate']['extension'] as $be) {
                    if ($be['url'] == 'https://fhir.kemkes.go.id/r4/StructureDefinition/patient-birthTime') {
                        $extendedBirthDate = $this->generateExtension('birthTime', $be ?? null);
                    }
                }
            }
        }

        return [
            'patient' => $patient,
            'identifier' => $identifier ?? null,
            'name' => $name ?? null,
            'telecom' => $telecom ?? null,
            'address' => $address ?? null,
            'maritalStatus' => $maritalStatus ?? null,
            'photo' => $photo ?? null,
            'contacts' => $contacts ?? null,
            'communication' => $communications ?? null,
            'generalPractitioner' => $generalPractitioner ?? null,
            'managingOrganization' => $managingOrganization ?? null,
            'links' => $links ?? null,
            'citizenship' => $citizenship ?? null,
            'birthPlace' => $birthPlace ?? null,
            'religion' => $religion ?? null,
            'citizenshipStatus' => $citizenshipStatus ?? null,
            'extendedBirthDate' => $extendedBirthDate ?? null
        ];
    }

    public function savePractitioner(Resource $resource, $array): Practitioner
    {
        if (!empty($array)) {
            $practitioner = $resource->practitioner()->save($array['practitioner'] ?? null);
            $this->saveMany($practitioner, 'identifier', $array['identifier'] ?? null, 'saveIdentifier');
            $this->saveMany($practitioner, 'name', $array['name'] ?? null, 'saveHumanName');
            $this->saveMany($practitioner, 'telecom', $array['telecom'] ?? null, 'saveContactPoint');
            $this->saveMany($practitioner, 'address', $array['address'] ?? null, 'saveAddress');
            $this->saveMany($practitioner, 'photo', $array['photo'] ?? null, 'saveAttachment');

            if (!empty($array['qualification'])) {
                foreach ($array['qualification'] as $q) {
                    $qualification = $practitioner->qualification()->save($q['qualification'] ?? null);
                    $this->saveMany($qualification, 'identifier', $q['identifier'] ?? null, 'saveIdentifier');
                    $this->saveCodeableConcept($qualification, 'code', $q['code'] ?? null);
                    $this->savePeriod($qualification, 'period', $q['period'] ?? null);
                    $this->saveReference($qualification, 'issuer', $q['issuer'] ?? null, 'saveReference');
                }
            }

            $this->saveMany($practitioner, 'communication', $array['communication'] ?? null, 'saveCodeableConcept');

            return $practitioner;
        }
    }

    public function generatePractitioner(string $jsonData): array
    {
        $array = $this->readJsonResource($jsonData);

        $practitioner = new Practitioner([
            'active' => $array['active'] ?? null,
            'gender' => $array['gender'] ?? null,
            'birth_date' => $array['birthDate'] ?? null
        ]);

        $identifier = $this->hasMany('identifier', $array['identifier'] ?? null, 'generateIdentifier');
        $name = $this->hasMany('name', $array['name'] ?? null, 'generateHumanName');
        $telecom = $this->hasMany('telecom', $array['telecom'] ?? null, 'generateContactPoint');
        $address = $this->hasMany('address', $array['address'] ?? null, 'generateAddress');
        $photo = $this->hasMany('photo', $array['photo'] ?? null, 'generateAttachment');

        if (!empty($array['qualification'])) {
            $qualifications = [];
            foreach ($array['qualification'] as $q) {
                $qualifications[] = [
                    'qualification' => new PractitionerQualification(),
                    'identifier' => $this->hasMany('identifier', $q['identifier'] ?? null, 'generateIdentifier'),
                    'code' => $this->generateCodeableConcept('code', $q['code'] ?? null),
                    'period' => $this->generatePeriod('period', $q['period'] ?? null),
                    'issuer' => $this->generateReference('issuer', $q['issuer'] ?? null),
                ];
            }
        }

        $communication = $this->hasMany('communication', $array['communication'] ?? null, 'generateCodeableConcept');

        return [
            'practitioner' => $practitioner,
            'identifier' => $identifier ?? null,
            'name' => $name ?? null,
            'telecom' => $telecom ?? null,
            'address' => $address ?? null,
            'photo' => $photo ?? null,
            'qualification' => $qualifications ?? null,
            'communication' => $communication ?? null
        ];
    }

    private function saveAttachment($model, $attribute, $array)
    {
        if (!empty($array)) {
            $model->$attribute()->save($array);
        }
    }

    private function generateAttachment($attribute, $array)
    {
        if (!empty($array)) {
            return new Attachment([
                'content_type' => $array['contentType'] ?? null,
                'language' => $array['language'] ?? null,
                'data' => $array['data'] ?? null,
                'url' => $array['url'] ?? null,
                'size' => $array['size'] ?? null,
                'hash' => $array['hash'] ?? null,
                'title' => $array['title'] ?? null,
                'creation' => $array['creation'] ?? null,
                'attr_type' => $attribute
            ]);
        } else {
            return null;
        }
    }

    public function saveLocation(Resource $resource, $array): Location
    {
        if (!empty($array)) {
            $location = $resource->location()->save($array['location']);
            $this->saveMany($location, 'identifier', $array['identifier'] ?? null, 'saveIdentifier');
            $this->saveCoding($location, 'operationalStatus', $array['operationalStatus'] ?? null);
            $this->saveMany($location, 'telecom', $array['telecom'] ?? null, 'saveContactPoint');
            $this->saveAddress($location, 'address', $array['address'] ?? null);
            $this->saveCodeableConcept($location, 'physicalType', $array['physicalType'] ?? null);

            if (!empty($array['position'])) {
                $location->position()->save($array['position'] ?? null);
            }

            $this->saveReference($location, 'managingOrganization', $array['managingOrganization'] ?? null, 'saveReference');
            $this->saveReference($location, 'partOf', $array['partOf'] ?? null, 'saveReference');

            if (!empty($array['hoursOfOperation'])) {
                $location->hoursOfOperation()->saveMany($array['hoursOfOperation'] ?? null);
            }

            $this->saveMany($location, 'endpoint', $array['endpoint'] ?? null, 'saveReference');
            $this->saveExtension($location, 'serviceClass', $array['serviceClass'] ?? null);
            return $location;
        }
    }

    public function generateLocation(string $jsonData): array
    {
        $array = $this->readJsonResource($jsonData);

        $location = new Location([
            'status' => $array['status'] ?? null,
            'name' => $array['name'] ?? null,
            'alias' => $array['alias'] ?? null,
            'description' => $array['description'] ?? null,
            'mode' => $array['mode'] ?? null,
            'availability_exceptions' => $array['availabilityExceptions'] ?? null,
        ]);

        $identifier = $this->hasMany('identifier', $array['identifier'] ?? null, 'generateIdentifier');
        $operationalStatus = $this->generateCoding('operationalStatus', $array['operationalStatus'] ?? null);
        $type = $this->hasMany('type', $array['type'] ?? null, 'generateCodeableConcept');
        $telecom = $this->hasMany('telecom', $array['telecom'] ?? null, 'generateContactPoint');
        $address = $this->generateAddress('address', $array['address'] ?? null);
        $physicalType = $this->generateCodeableConcept('physicalType', $array['physicalType'] ?? null);

        if (!empty($array['position'])) {
            $position = new LocationPosition($array['position']);
        }

        $managingOrganization = $this->generateReference('managingOrganization', $array['managingOrganization'] ?? null);
        $partOf = $this->generateReference('partOf', $array['partOf'] ?? null);

        if (!empty($array['hoursOfOperation'])) {
            $hoursOfOperation = [];
            foreach ($array['hoursOfOperation'] as $h) {
                $hoursOfOperation[] = new LocationHoursOfOperation($h);
            }
        }

        $endpoint = $this->hasMany('endpoint', $array['endpoint'] ?? null, 'generateReference');
        $serviceClass = $this->generateExtension('serviceClass', $array['extension'] ?? null);

        return [
            'location' => $location,
            'identifier' => $identifier ?? null,
            'operationalStatus' => $operationalStatus ?? null,
            'type' => $type ?? null,
            'telecom' => $telecom ?? null,
            'address' => $address ?? null,
            'physicalType' => $physicalType ?? null,
            'position' => $position ?? null,
            'managingOrganization' => $managingOrganization ?? null,
            'partOf' => $partOf ?? null,
            'hoursOfOperation' => $hoursOfOperation ?? null,
            'endpoint' => $endpoint ?? null,
            'serviceClass' => $serviceClass ?? null
        ];
    }

    public function saveOrganization(Resource $resource, $array): Organization
    {
        if (!empty($array)) {
            $organization = $resource->organization()->save($array['organization'] ?? null);
            $this->saveMany($organization, 'identifier', $array['identifier'] ?? null, 'saveIdentifier');
            $this->saveMany($organization, 'type', $array['type'] ?? null, 'saveCodeableConcept');
            $this->saveMany($organization, 'telecom', $array['telecom'] ?? null, 'saveContactPoint');
            $this->saveMany($organization, 'address', $array['address'] ?? null, 'saveAddress');
            $this->saveReference($organization, 'partOf', $array['partOf'] ?? null, 'saveReference');

            if (!empty($array['contact'])) {
                foreach ($array['contact'] as $c) {
                    $contact = $organization->contact()->save($c['contact'] ?? null);
                    $this->saveCodeableConcept($contact, 'purpose', $c['purpose'] ?? null);
                    $this->saveHumanName($contact, 'name', $c['name'] ?? null);
                    $this->saveMany($contact, 'telecom', $c['telecom'] ?? null, 'saveContactPoint');
                    $this->saveAddress($contact, 'address', $c['address'] ?? null);
                }
            }

            $this->saveMany($organization, 'endpoint', $array['endpoint'] ?? null, 'saveReference');

            return $organization;
        }
    }

    public function generateOrganization(string $jsonData): array
    {
        $array = $this->readJsonResource($jsonData);

        $organization = new Organization([
            'active' => $array['active'] ?? null,
            'name' => $array['name'] ?? null,
            'alias' => $array['alias'] ?? null
        ]);

        $identifier = $this->hasMany('identifier', $array['identifier'] ?? null, 'generateIdentifier');
        $type = $this->hasMany('type', $array['type'] ?? null, 'generateCodeableConcept');
        $telecom = $this->hasMany('telecom', $array['telecom'] ?? null, 'generateContactPoint');
        $address = $this->hasMany('address', $array['address'] ?? null, 'generateAddress');
        $partOf = $this->generateReference('partOf', $array['partOf'] ?? null);

        if (!empty($array['contact'])) {
            $contacts = [];
            foreach ($array['contact'] as $c) {
                $contacts[] = [
                    'contact' => new OrganizationContact(),
                    'purpose' => $this->generateCodeableConcept('purpose', $c['purpose'] ?? null),
                    'name' => $this->generateHumanName('name', $c['name'] ?? null),
                    'telecom' => $this->hasMany('telecom', $c['telecom'] ?? null, 'generateContactPoint'),
                    'address' => $this->generateAddress('address', $c['address'] ?? null)
                ];
            }
        }

        $endpoint = $this->hasMany('endpoint', $array['endpoint'] ?? null, 'generateReference');

        return [
            'organization' => $organization,
            'identifier' => $identifier ?? null,
            'type' => $type ?? null,
            'telecom' => $telecom ?? null,
            'address' => $address ?? null,
            'partOf' => $partOf ?? null,
            'endpoint' => $endpoint ?? null,
            'contact' => $contacts ?? null
        ];
    }

    private function hasMany($attribute, $array, $method)
    {
        if (!empty($array)) {
            $data = [];
            foreach ($array as $a) {
                $data[] = $this->$method($attribute, $a);
            }
            return $data;
        } else {
            return null;
        }
    }

    private function saveHumanName($model, $attribute, $array)
    {
        if (!empty($array)) {
            $humanName = $model->$attribute()->save($array['humanName'] ?? null);
            $this->savePeriod($humanName, 'period', $array['period'] ?? null);
        }
    }

    private function generateHumanName($attribute, $array)
    {
        if (!empty($array)) {
            $humanName = new HumanName([
                'use' => $array['use'] ?? null,
                'text' => $array['text'] ?? null,
                'family' => $array['family'] ?? null,
                'given' => $array['given'] ?? null,
                'prefix' => $array['prefix'] ?? null,
                'suffix' => $array['suffix'] ?? null,
                'attr_type' => $attribute
            ]);
            $period = $this->generatePeriod('period', $array['period'] ?? null);
            return [
                'humanName' => $humanName,
                'period' => $period ?? null
            ];
        } else {
            return null;
        }
    }

    private function saveAddress($model, $attribute, $array)
    {
        if (!empty($array)) {
            $address = $model->$attribute()->save($array['address'] ?? null);
            $this->savePeriod($address, 'period', $array['period'] ?? null);
            $this->saveComplexExtension($address, 'administrativeCode', $array['administrativeCode'] ?? null);
            $this->saveComplexExtension($address, 'geolocation', $array['geolocation'] ?? null);
        }
    }

    private function generateAddress($attribute, $array)
    {
        if (!empty($array)) {
            $address = new Address([
                'use' => $array['use'] ?? null,
                'type' => $array['type'] ?? null,
                'text' => $array['text'] ?? null,
                'line' => $array['line'] ?? null,
                'city' => $array['city'] ?? null,
                'district' => $array['district'] ?? null,
                'state' => $array['state'] ?? null,
                'postal_code' => $array['postal_code'] ?? null,
                'country' => $array['country'] ?? null,
                'attr_type' => $attribute
            ]);

            $period = $this->generatePeriod('period', $array['period'] ?? null);

            if (!empty($array['extension'])) {
                foreach ($array['extension'] as $e) {
                    if ($e['url'] == 'https://fhir.kemkes.go.id/r4/StructureDefinition/AdministrativeCode') {
                        $administrativeCode = $this->generateComplexExtension('administrativeCode', $e);
                    } elseif ($e['url'] == 'https://fhir.kemkes.go.id/r4/StructureDefinition/geolocation') {
                        $geolocation = $this->generateComplexExtension('geolocation', $e);
                    }
                }
            }

            return [
                'address' => $address,
                'period' => $period ?? null,
                'administrativeCode' => $administrativeCode ?? null,
                'geolocation' => $geolocation ?? null
            ];
        } else {
            return null;
        }
    }

    private function saveComplexExtension($model, $attribute, $array)
    {
        if (!empty($array)) {
            $complexExtension = $model->$attribute()->save($array['complexExtension'] ?? null);
            $this->saveMany($complexExtension, 'extension', $array['extension'] ?? null, 'saveExtension');
        }
    }

    private function generateComplexExtension($attribute, $array)
    {
        if (!empty($array)) {
            $complexExtension = new ComplexExtension([
                'url' => $array['url'] ?? null,
                'exts' => [],
                'attr_type' => $attribute
            ]);

            if (!empty($array['extension'])) {
                $extensions = [];
                $extension = [];
                foreach ($array['extension'] as $e) {
                    $extensions[] = $e['url'];
                    $extension[] = $this->generateExtension('extension', $e);
                }
                $complexExtension->exts = $extensions;
            }

            return [
                'complexExtension' => $complexExtension,
                'extension' => $extension ?? null
            ];
        } else {
            return null;
        }
    }

    private function saveExtension($model, $attribute, $array)
    {
        if (!empty($array)) {
            $extension = $model->$attribute($array['extension']['url'])->save($array['extension'] ?? null);
            $this->saveAddress($extension, 'valueAddress', $array['valueAddress'] ?? null);
            $this->saveAge($extension, 'valueAge', $array['valueAge'] ?? null);
            $this->saveAnnotation($extension, 'valueAnnotation', $array['valueAnnotation'] ?? null);
            $this->saveAttachment($extension, 'valueAttachment', $array['valueAttachment'] ?? null);
            $this->saveCodeableConcept($extension, 'valueCodeableConcept', $array['valueCodeableConcept'] ?? null);
            $this->saveCoding($extension, 'valueCoding', $array['valueCoding'] ?? null);
            $this->saveContactPoint($extension, 'valueContactPoint', $array['valueContactPoint'] ?? null);
            // $this->saveCount($extension, 'valueCount', $array['valueCount'] ?? null);
            // $this->saveDistance($extension, 'valueDistance', $array['valueDistance'] ?? null);
            $this->saveDuration($extension, 'valueDuration', $array['valueDuration'] ?? null);
            $this->saveHumanName($extension, 'valueHumanName', $array['valueHumanName'] ?? null);
            $this->saveIdentifier($extension, 'valueIdentifier', $array['valueIdentifier'] ?? null);
            $this->savePeriod($extension, 'valuePeriod', $array['valuePeriod'] ?? null);
            $this->saveQuantity($extension, 'valueQuantity', $array['valueQuantity'] ?? null);
            $this->saveRange($extension, 'valueRange', $array['valueRange'] ?? null);
            $this->saveRatio($extension, 'valueRatio', $array['valueRatio'] ?? null);
            $this->saveSampledData($extension, 'valueSampledData', $array['valueSampledData'] ?? null);
            // $this->saveSignature($extension, 'valueSignature', $array['valueSignature'] ?? null);
            $this->saveTiming($extension, 'valueTiming', $array['valueTiming'] ?? null);
            // $this->saveContactDetail($extension, 'valueContactDetail', $array['valueContactDetail'] ?? null);
            // $this->saveContributor($extension, 'valueContributor', $array['valueContributor'] ?? null);
            // $this->saveDataRequirement($extension, 'valueDataRequirement', $array['valueDataRequirement'] ?? null);
            // $this->saveExpression($extension, 'valueExpression', $array['valueExpression'] ?? null);
            // $this->saveParameterDefinition($extension, 'valueParameterDefinition', $array['valueParameterDefinition'] ?? null);
            // $this->saveRelatedArtifact($extension, 'valueRelatedArtifact', $array['valueRelatedArtifact'] ?? null);
            // $this->saveTriggerDefinition($extension, 'valueTriggerDefinition', $array['valueTriggerDefinition'] ?? null);
            // $this->saveUsageContext($extension, 'valueUsageContext', $array['valueUsageContext'] ?? null);
            // $this->saveDosage($extension, 'valueDosage', $array['valueDosage'] ?? null);
            // $this->saveMeta($extension, 'valueMeta', $array['valueMeta'] ?? null);
            $this->saveReference($extension, 'valueReference', $array['valueReference'] ?? null);
        }
    }

    private function generateExtension($attribute, $array)
    {
        if (!empty($array)) {
            $extension = new Extension([
                'url' => $array['url'] ?? null,
                'value_base_64_binary' => $array['valueBase64Binary'] ?? null,
                'value_boolean' => $array['valueBoolean'] ?? null,
                'value_canonical' => $array['valueCanonical'] ?? null,
                'value_code' => $array['valueCode'] ?? null,
                'value_date' => $array['valueDate'] ?? null,
                'value_date_time' => $array['valueDateTime'] ?? null,
                'value_decimal' => $array['valueDecimal'] ?? null,
                'value_id' => $array['valueId'] ?? null,
                'value_instant' => $array['valueInstant'] ?? null,
                'value_integer' => $array['valueInteger'] ?? null,
                'value_markdown' => $array['valueMarkdown'] ?? null,
                'value_oid' => $array['valueOid'] ?? null,
                'value_positive_int' => $array['valuePositiveInt'] ?? null,
                'value_string' => $array['valueString'] ?? null,
                'value_time' => $array['valueTime'] ?? null,
                'value_unsigned_int' => $array['valueUnsignedInt'] ?? null,
                'value_uri' => $array['valueUri'] ?? null,
                'value_url' => $array['valueUrl'] ?? null,
                'value_uuid' => $array['valueUuid'] ?? null,
                'attr_type' => $attribute
            ]);

            $valueAddress = $this->generateAddress('valueAddress', $array['valueAddress'] ?? null);
            $valueAge = $this->generateAge('valueAge', $array['valueAge'] ?? null);
            $valueAnnotation = $this->generateAnnotation('valueAnnotation', $array['valueAnnotation'] ?? null);
            $valueAttachment = $this->generateAttachment('valueAttachment', $array['valueAttachment'] ?? null);
            $valueCodeableConcept = $this->generateCodeableConcept('valueCodeableConcept', $array['valueCodeableConcept'] ?? null);
            $valueCoding = $this->generateCoding('valueCoding', $array['valueCoding'] ?? null);
            $valueContactPoint = $this->generateContactPoint('valueContactPoint', $array['valueContactPoint'] ?? null);
            // $this->generateCount('valueCount', $array['valueCount'] ?? null);
            // $this->generateDistance('valueDistance', $array['valueDistance'] ?? null);
            $valueDuration = $this->generateDuration('valueDuration', $array['valueDuration'] ?? null);
            $valueHumanName = $this->generateHumanName('valueHumanName', $array['valueHumanName'] ?? null);
            $valueIdentifier = $this->generateIdentifier('valueIdentifier', $array['valueIdentifier'] ?? null);
            $valuePeriod = $this->generatePeriod('valuePeriod', $array['valuePeriod'] ?? null);
            $valueQuantity = $this->generateQuantity('valueQuantity', $array['valueQuantity'] ?? null);
            $valueRange = $this->generateRange('valueRange', $array['valueRange'] ?? null);
            $valueRatio = $this->generateRatio('valueRatio', $array['valueRatio'] ?? null);
            $this->generateSampledData('valueSampledData', $array['valueSampledData'] ?? null);
            // $this->generateSignature('valueSignature', $array['valueSignature'] ?? null);
            $this->generateTiming('valueTiming', $array['valueTiming'] ?? null);
            // $this->generateContactDetail('valueContactDetail', $array['valueContactDetail'] ?? null);
            // $this->generateContributor('valueContributor', $array['valueContributor'] ?? null);
            // $this->generateDataRequirement('valueDataRequirement', $array['valueDataRequirement'] ?? null);
            // $this->generateExpression('valueExpression', $array['valueExpression'] ?? null);
            // $this->generateParameterDefinition('valueParameterDefinition', $array['valueParameterDefinition'] ?? null);
            // $this->generateRelatedArtifact('valueRelatedArtifact', $array['valueRelatedArtifact'] ?? null);
            // $this->generateTriggerDefinition('valueTriggerDefinition', $array['valueTriggerDefinition'] ?? null);
            // $this->generateUsageContext('valueUsageContext', $array['valueUsageContext'] ?? null);
            // $this->generateDosage('valueDosage', $array['valueDosage'] ?? null);
            // $this->generateMeta('valueMeta', $array['valueMeta'] ?? null);
            $valueReference = $this->generateReference('valueReference', $array['valueReference'] ?? null);

            return [
                'extension' => $extension,
                'valueAddress' => $valueAddress ?? null,
                'valueAge' => $valueAge ?? null,
                'valueAnnotation' => $valueAnnotation ?? null,
                'valueAttachment' => $valueAttachment ?? null,
                'valueCodeableConcept' => $valueCodeableConcept ?? null,
                'valueCoding' => $valueCoding ?? null,
                'valueContactPoint' => $valueContactPoint ?? null,
                // 'valueCount' => $valueCount ?? null,
                // 'valueDistance' => $valueDistance ?? null,
                'valueDuration' => $valueDuration ?? null,
                'valueHumanName' => $valueHumanName ?? null,
                'valueIdentifier' => $valueIdentifier ?? null,
                'valuePeriod' => $valuePeriod ?? null,
                'valueQuantity' => $valueQuantity ?? null,
                'valueRange' => $valueRange ?? null,
                'valueRatio' => $valueRatio ?? null,
                // 'valueSampledData' => $valueSampledData ?? null,
                // 'valueSignature' => $valueSignature ?? null,
                // 'valueTiming' => $valueTiming ?? null,
                // 'valueContactDetail' => $valueContactDetail ?? null,
                // 'valueContributor' => $valueContributor ?? null,
                // 'valueDataRequirement' => $valueDataRequirement ?? null,
                // 'valueExpression' => $valueExpression ?? null,
                // 'valueParameterDefinition' => $valueParameterDefinition ?? null,
                // 'valueRelatedArtifact' => $valueRelatedArtifact ?? null,
                // 'valueTriggerDefinition' => $valueTriggerDefinition ?? null,
                // 'valueUsageContext' => $valueUsageContext ?? null,
                // 'valueDosage' => $valueDosage ?? null,
                // 'valueMeta' => $valueMeta ?? null,
                'valueReference' => $valueReference ?? null
            ];
        } else {
            return null;
        }
    }

    private function saveAge($model, $attribute, $array)
    {
        if (!empty($array)) {
            $model->$attribute()->save($array);
        }
    }

    private function generateAge($attribute, $array)
    {
        if (!empty($array)) {
            return new Age([
                'value' => $array['value'] ?? null,
                'comparator' => $array['comparator'] ?? null,
                'unit' => $array['unit'] ?? null,
                'system' => $array['system'] ?? null,
                'code' => $array['code'] ?? null,
                'attr_type' => $attribute
            ]);
        } else {
            return null;
        }
    }

    private function saveAnnotation($parent, $attribute, $array)
    {
        if (!empty($array)) {
            $annotation = $parent->$attribute()->save($array['annotation'] ?? null);
            $this->saveReference($annotation, 'authorReference', $array['authorReference'] ?? null);
        }
    }

    private function generateAnnotation($attribute, $array)
    {
        if (!empty($array)) {
            $annotation = new Annotation([
                'author_string' => $array['authorString'] ?? null,
                'time' => $array['time'] ?? null,
                'text' => $array['text'] ?? null,
                'attr_type' => $attribute
            ]);

            $authorReference = $this->generateReference('authorReference', $array['authorReference'] ?? null);

            return [
                'annotation' => $annotation,
                'authorReference' => $authorReference ?? null
            ];
        } else {
            return null;
        }
    }

    private function saveSimpleQuantity($parent, $attribute, $array)
    {
        if (!empty($array)) {
            $parent->$attribute()->save($array);
        }
    }

    private function generateSimpleQuantity($attribute, $array)
    {
        if (!empty($array)) {
            return new SimpleQuantity([
                'value' => $array['value'] ?? null,
                'unit' => $array['unit'] ?? null,
                'system' => $array['system'] ?? null,
                'code' => $array['code'] ?? null,
                'attr_type' => $attribute
            ]);
        } else {
            return null;
        }
    }

    private function saveRange($parent, $attribute, $array)
    {
        if (!empty($array)) {
            $range = $parent->$attribute()->save($array['range'] ?? null);
            $this->saveSimpleQuantity($range, 'low', $array['low'] ?? null);
            $this->saveSimpleQuantity($range, 'high', $array['high'] ?? null);
        }
    }

    private function generateRange($attribute, $array)
    {
        if (!empty($array)) {
            return [
                'range' => new Range(['attr_type' => $attribute]),
                'low' => $this->generateSimpleQuantity('low', $array['low'] ?? null),
                'high' => $this->generateSimpleQuantity('high', $array['high'] ?? null)
            ];
        } else {
            return null;
        }
    }

    private function saveContactPoint($model, $attribute, $array)
    {
        if (!empty($array)) {
            $contactPoint = $model->$attribute()->save($array['contactPoint'] ?? null);
            $this->savePeriod($contactPoint, 'period', $array['period'] ?? null);
        }
    }

    private function generateContactPoint($attribute, $array)
    {
        if (!empty($array)) {
            $contactPoint = new ContactPoint([
                'system' => $array['system'] ?? null,
                'value' => $array['value'] ?? null,
                'use' => $array['use'] ?? null,
                'rank' => $array['rank'] ?? null,
                'attr_type' => $attribute
            ]);
            $period = $this->generatePeriod('period', $array['period'] ?? null);

            return [
                'contactPoint' => $contactPoint,
                'period' => $period ?? null
            ];
        } else {
            return null;
        }
    }

    private function saveIdentifier($parent, $attribute, $array)
    {
        if (!empty($array)) {
            $identifier = $parent->$attribute()->save($array['identifier'] ?? null);
            $this->saveCodeableConcept($identifier, 'type', $array['type'] ?? null);
            $this->savePeriod($identifier, 'period', $array['period'] ?? null);
            $this->saveReference($identifier, 'assigner', $array['assigner'] ?? null);
        }
    }

    private function generateIdentifier($attribute, $array)
    {
        if (!empty($array)) {
            $identifier = new Identifier([
                'use' => $array['use'] ?? null,
                'system' => $array['system'] ?? null,
                'value' => $array['value'] ?? null,
                'attr_type' => $attribute
            ]);

            $type = $this->generateCodeableConcept('type', $array['type'] ?? null);
            $period = $this->generatePeriod('period', $array['period'] ?? null);
            $assigner = $this->generateReference('assigner', $array['assigner'] ?? null);

            return [
                'identifier' => $identifier,
                'type' => $type ?? null,
                'period' => $period ?? null,
                'assigner' => $assigner ?? null
            ];
        } else {
            return null;
        }
    }

    private function saveReference($parent, $attribute, $array)
    {
        if (!empty($array)) {
            $reference = $parent->$attribute()->save($array['reference'] ?? null);
            $this->saveIdentifier($reference, 'identifier', $array['identifier'] ?? null);
        }
    }

    private function generateReference($attribute, $array)
    {
        if (!empty($array)) {
            $reference = new Reference([
                'reference' => $array['reference'] ?? null,
                'type' => $array['type'] ?? null,
                'display' => $array['display'] ?? null,
                'attr_type' => $attribute
            ]);

            $identifier = $this->generateIdentifier('identifier', $array['identifier'] ?? null);

            return [
                'reference' => $reference,
                'identifier' => $identifier ?? null
            ];
        } else {
            return null;
        }
    }

    private function savePeriod($parent, $attribute, $array)
    {
        if (!empty($array)) {
            $parent->$attribute()->save($array);
        }
    }

    private function generatePeriod($attribute, $array)
    {
        if (!empty($array)) {
            $period = new Period(array_merge(
                $array,
                ['attr_type' => $attribute]
            ));
            return $period;
        } else {
            return null;
        }
    }

    private function saveCodeableConcept($parent, $attribute, $array)
    {
        if (!empty($array)) {
            $codeableConcept = $parent->$attribute()->save($array['codeableConcept'] ?? null);
            $this->saveMany($codeableConcept, 'coding', $array['coding'] ?? null, 'saveCoding');
        }
    }

    private function generateCodeableConcept($attribute, $array)
    {
        if (!empty($array)) {
            return [
                'codeableConcept' => new CodeableConcept([
                    'text' => $array['text'] ?? null,
                    'attr_type' => $attribute
                ]),
                'coding' => $this->hasMany('coding', $array['coding'] ?? null, 'generateCoding')
            ];
        } else {
            return null;
        }
    }

    private function saveMany($parent, $attribute, $array, $method)
    {
        if (!empty($array)) {
            foreach ($array as $a) {
                $this->$method($parent, $attribute, $a);
            }
        }
    }

    private function generateCoding($attribute, $array)
    {
        if (!empty($array)) {
            $coding = new Coding(array_merge(
                $array,
                ['attr_type' => $attribute]
            ));
            return $coding;
        } else {
            return null;
        }
    }

    private function saveCoding($parent, $attribute, $array)
    {
        if (!empty($array)) {
            $parent->$attribute()->save($array);
        }
    }

    private function readJsonResource(string $jsonData): array
    {
        try {
            $array = json_decode($jsonData, true);
            return $array;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}
