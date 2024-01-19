<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class EncounterResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = $this->resourceStructure($this);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    public function resourceStructure($encounter): array
    {
        return [
            'resourceType' => 'Encounter',
            'id' => data_get($encounter, 'resource.satusehat_id'),
            'identifier' => $this->createMany($encounter->identifier, 'createIdentifierResource'),
            'status' => $encounter->status,
            'statusHistory' => $this->createMany($encounter->statusHistory, 'createStatusHistoryResource'),
            'class' => $this->createCodingResource($encounter->class),
            'classHistory' => $this->createMany($encounter->classHistory, 'createClassHistoryResource'),
            'type' => $this->createMany($encounter->type, 'createCodeableConceptResource'),
            'serviceType' => $this->createCodeableConceptResource($encounter->serviceType),
            'priority' => $this->createCodeableConceptResource($encounter->priority),
            'subject' => $this->createReferenceResource($encounter->subject),
            'episodeOfCare' => $this->createMany($encounter->episodeOfCare, 'createReferenceResource'),
            'basedOn' => $this->createMany($encounter->basedOn, 'createReferenceResource'),
            'participant' => $this->createMany($encounter->participant, 'createParticipantResource'),
            'appointment' => $this->createMany($encounter->appointment, 'createReferenceResource'),
            'period' => $this->createPeriodResource($encounter->period),
            'length' => $this->createDurationResource($encounter->length),
            'reasonCode' => $this->createMany($encounter->reasonCode, 'createCodeableConceptResource'),
            'reasonReference' => $this->createMany($encounter->reasonReference, 'createReferenceResource'),
            'diagnosis' => $this->createMany($encounter->diagnosis, 'createDiagnosisResource'),
            'account' => $this->createMany($encounter->account, 'createReferenceResource'),
            'hospitalization' => $this->createHospitalizationResource($encounter->hospitalization),
            'location' => $this->createMany($encounter->location, 'createLocationResource'),
            'serviceProvider' => $this->createReferenceResource($encounter->serviceProvider),
            'partOf' => $this->createReferenceResource($encounter->partOf),
        ];
    }

    public function createStatusHistoryResource($statusHistory)
    {
        if (!empty($statusHistory)) {
            return [
                'status' => $statusHistory->status,
                'period' => $this->createPeriodResource($statusHistory->period)
            ];
        } else {
            return null;
        }
    }

    public function createClassHistoryResource($classHistory)
    {
        if (!empty($classHistory)) {
            return [
                'class' => $this->createCodingResource($classHistory->class),
                'period' => $this->createPeriodResource($classHistory->period)
            ];
        } else {
            return null;
        }
    }

    public function createParticipantResource($participant)
    {
        if (!empty($participant)) {
            return [
                'type' => $this->createMany($participant->type, 'createCodeableConceptResource'),
                'period' => $this->createPeriodResource($participant->period),
                'individual' => $this->createReferenceResource($participant->individual)
            ];
        } else {
            return null;
        }
    }

    public function createDiagnosisResource($diagnosis)
    {
        if (!empty($diagnosis)) {
            return [
                'condition' => $this->createReferenceResource($diagnosis->condition),
                'use' => $this->createCodeableConceptResource($diagnosis->use),
                'rank' => $diagnosis->rank
            ];
        } else {
            return null;
        }
    }

    public function createHospitalizationResource($hospitalization)
    {
        if (!empty($hospitalization)) {
            return [
                'preAdmissionIdentifier' => $this->createIdentifierResource($hospitalization->preAdmissionIdentifier),
                'origin' => $this->createReferenceResource($hospitalization->origin),
                'admitSource' => $this->createCodeableConceptResource($hospitalization->admitSource),
                'reAdmission' => $this->createCodeableConceptResource($hospitalization->reAdmission),
                'dietPreference' => $this->createMany($hospitalization->dietPreference, 'createCodeableConceptResource'),
                'specialCourtesy' => $this->createMany($hospitalization->specialCourtesy, 'createCodeableConceptResource'),
                'specialArrangement' => $this->createMany($hospitalization->specialArrangement, 'createCodeableConceptResource'),
                'destination' => $this->createReferenceResource($hospitalization->destination),
                'dischargeDisposition' => $this->createCodeableConceptResource($hospitalization->dischargeDisposition),
            ];
        } else {
            return null;
        }
    }

    public function createLocationResource($location)
    {
        if (!empty($location)) {
            return [
                'location' => $this->createReferenceResource($location->location),
                'status' => $location->status,
                'physicalType' => $this->createCodeableConceptResource($location->physicalType),
                'period' => $this->createPeriodResource($location->period),
                'extension' => [
                    $this->createComplexExtensionResource($location->serviceClass)
                ]
            ];
        } else {
            return null;
        }
    }
}
