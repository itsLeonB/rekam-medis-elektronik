<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class AllergyIntoleranceResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $allergy = $this->getData('allergyIntolerance');

        $data = $this->resourceStructure($allergy);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    public function resourceStructure($allergy): array
    {
        return [
            'resourceType' => 'AllergyIntolerance',
            'id' => $this->satusehat_id,
            'identifier' => $this->createMany($allergy->identifier, 'createIdentifierResource'),
            'clinicalStatus' => $this->createCodeableConceptResource($allergy->clinicalStatus),
            'verificationStatus' => $this->createCodeableConceptResource($allergy->verificationStatus),
            'type' => $allergy->type,
            'category' => $allergy->category,
            'criticality' => $allergy->criticality,
            'code' => $this->createCodeableConceptResource($allergy->code),
            'patient' => $this->createReferenceResource($allergy->patient),
            'encounter' => $this->createReferenceResource($allergy->encounter),
            'onsetDateTime' => $this->parseDateTime($allergy->onset_date_time),
            'onsetAge' => $this->createAgeResource($allergy->onsetAge),
            'onsetPeriod' => $this->createPeriodResource($allergy->onsetPeriod),
            'onsetRange' => $this->createRangeResource($allergy->onsetRange),
            'onsetString' => $allergy->onset_string,
            'recordedDate' => $this->parseDateTime($allergy->recorded_date),
            'recorder' => $this->createReferenceResource($allergy->recorder),
            'asserter' => $this->createReferenceResource($allergy->asserter),
            'lastOccurrence' => $this->parseDateTime($allergy->last_occurrence),
            'note' => $this->createMany($allergy->note, 'createAnnotationResource'),
            'reaction' => $this->createMany($allergy->reaction, 'createReactionResource'),
        ];
    }

    public function createReactionResource($reaction)
    {
        if (!empty($reaction)) {
            return [
                'substance' => $this->createCodeableConceptResource($reaction->substance),
                'manifestation' => $this->createMany($reaction->manifestation, 'createCodeableConceptResource'),
                'description' => $reaction->description,
                'onset' => $this->parseDateTime($reaction->onset),
                'severity' => $reaction->severity,
                'exposureRoute' => $this->createCodeableConceptResource($reaction->exposureRoute),
                'note' => $this->createMany($reaction->note, 'createAnnotationResource'),
            ];
        } else {
            return null;
        }
    }
}
