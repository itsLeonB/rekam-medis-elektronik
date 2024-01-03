<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ConditionResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $condition = $this->getData('condition');

        $data = $this->resourceStructure($condition);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($condition): array
    {
        return [
            'resourceType' => 'Condition',
            'id' => $this->satusehat_id,
            'identifier' => $this->createMany($condition->identifier, 'createIdentifierResource'),
            'clinicalStatus' => $this->createCodeableConceptResource($condition->clinicalStatus),
            'verificationStatus' => $this->createCodeableConceptResource($condition->verificationStatus),
            'category' => $this->createMany($condition->category, 'createCodeableConceptResource'),
            'severity' => $this->createCodeableConceptResource($condition->severity),
            'code' => $this->createCodeableConceptResource($condition->code),
            'bodySite' => $this->createMany($condition->bodySite, 'createCodeableConceptResource'),
            'subject' => $this->createReferenceResource($condition->subject),
            'encounter' => $this->createReferenceResource($condition->encounter),
            'onsetDateTime' => $this->parseDateTime($condition->onset_date_time),
            'onsetAge' => $this->createAgeResource($condition->onsetAge),
            'onsetPeriod' => $this->createPeriodResource($condition->onsetPeriod),
            'onsetRange' => $this->createRangeResource($condition->onsetRange),
            'onsetString' => $condition->onset_string,
            'abatementDateTime' => $this->parseDateTime($condition->abatement_date_time),
            'abatementAge' => $this->createAgeResource($condition->abatementAge),
            'abatementPeriod' => $this->createPeriodResource($condition->abatementPeriod),
            'abatementRange' => $this->createRangeResource($condition->abatementRange),
            'abatementString' => $condition->abatement_string,
            'recordedDate' => $this->parseDateTime($condition->recorded_date),
            'recorder' => $this->createReferenceResource($condition->recorder),
            'asserter' => $this->createReferenceResource($condition->asserter),
            'stage' => $this->createMany($condition->stage, 'createStageResource'),
            'evidence' => $this->createMany($condition->evidence, 'createEvidenceResource'),
            'note' => $this->createMany($condition->note, 'createAnnotationResource')
        ];
    }

    public function createStageResource($stage)
    {
        if (!empty($stage)) {
            return [
                'summary' => $this->createCodeableConceptResource($stage->summary),
                'assessment' => $this->createMany($stage->assessment, 'createReferenceResource'),
                'type' => $this->createReferenceResource($stage->type)
            ];
        } else {
            return null;
        }
    }

    public function createEvidenceResource($evidence)
    {
        if (!empty($evidence)) {
            return [
                'code' => $this->createMany($evidence->code, 'createCodeableConceptResource'),
                'detail' => $this->createMany($evidence->detail, 'createReferenceResource')
            ];
        } else {
            return null;
        }
    }
}
