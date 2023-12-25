<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ClinicalImpressionResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $clinicalImpression = $this->getData('clinicalImpression');

        $data = $this->resourceStructure($clinicalImpression);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    public function resourceStructure($clinicalImpression): array
    {
        return [
            'resourceType' => 'ClinicalImpression',
            'id' => $this->satusehat_id,
            'identifier' => $this->createMany($clinicalImpression->identifier, 'createIdentifierResource'),
            'status' => $clinicalImpression->status,
            'statusReason' => $this->createCodeableConceptResource($clinicalImpression->statusReason),
            'code' => $this->createCodeableConceptResource($clinicalImpression->code),
            'description' => $clinicalImpression->description,
            'subject' => $this->createReferenceResource($clinicalImpression->subject),
            'encounter' => $this->createReferenceResource($clinicalImpression->encounter),
            'effectiveDateTime' => $this->parseDateTime($clinicalImpression->effective_date_time),
            'effectivePeriod' => $this->createPeriodResource($clinicalImpression->effectivePeriod),
            'date' => $this->parseDateTime($clinicalImpression->date),
            'assessor' => $this->createReferenceResource($clinicalImpression->assessor),
            'previous' => $this->createReferenceResource($clinicalImpression->previous),
            'problem' => $this->createMany($clinicalImpression->problem, 'createReferenceResource'),
            'investigation' => $this->createMany($clinicalImpression->investigation, 'createInvestigationResource'),
            'protocol' => $clinicalImpression->protocol,
            'summary' => $clinicalImpression->summary,
            'finding' => $this->createMany($clinicalImpression->finding, 'createFindingResource'),
            'prognosisCodeableConcept' => $this->createMany($clinicalImpression->prognosisCodeableConcept, 'createCodeableConceptResource'),
            'prognosisReference' => $this->createMany($clinicalImpression->prognosisReference, 'createReferenceResource'),
            'supportingInfo' => $this->createMany($clinicalImpression->supportingInfo, 'createReferenceResource'),
            'note' => $this->createMany($clinicalImpression->note, 'createAnnotationResource'),
        ];
    }

    public function createInvestigationResource($investigation)
    {
        if (!empty($investigation)) {
            return [
                'code' => $this->createCodeableConceptResource($investigation->code),
                'item' => $this->createMany($investigation->item, 'createReferenceResource'),
            ];
        } else {
            return null;
        }
    }

    public function createFindingResource($finding)
    {
        if (!empty($finding)) {
            return [
                'itemCodeableConcept' => $this->createCodeableConceptResource($finding->itemCodeableConcept),
                'itemReference' => $this->createReferenceResource($finding->itemReference),
                'basis' => $finding->basis,
            ];
        } else {
            return null;
        }
    }
}
