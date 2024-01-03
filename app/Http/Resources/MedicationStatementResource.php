<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class MedicationStatementResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $medicationStatement = $this->getData('medicationstatement');

        $data = $this->resourceStructure($medicationStatement);

        $data = $this->removeEmptyValues($data);

        return $data;
    }


    public function resourceStructure($medicationStatement): array
    {
        return [
            'resourceType' => 'MedicationStatement',
            'id' => $this->satusehat_id,
            'identifier' => $this->createMany($medicationStatement->identifier, 'createIdentifierResource'),
            'basedOn' => $this->createMany($medicationStatement->basedOn, 'createReferenceResource'),
            'partOf' => $this->createMany($medicationStatement->partOf, 'createReferenceResource'),
            'status' => $medicationStatement->status,
            'statusReason' => $this->createMany($medicationStatement->statusReason, 'createCodeableConceptResource'),
            'category' => $this->createCodeableConceptResource($medicationStatement->category),
            'medicationCodeableConcept' => $this->createCodeableConceptResource($medicationStatement->medicationCodeableConcept),
            'medicationReference' => $this->createReferenceResource($medicationStatement->medicationReference),
            'subject' => $this->createReferenceResource($medicationStatement->subject),
            'context' => $this->createReferenceResource($medicationStatement->context),
            'effectiveDateTime' => $this->parseDateTime($medicationStatement->effective_date_time),
            'effectivePeriod' => $this->createPeriodResource($medicationStatement->effectivePeriod),
            'dateAsserted' => $this->parseDateTime($medicationStatement->date_asserted),
            'informationSource' => $this->createReferenceResource($medicationStatement->informationSource),
            'derivedFrom' => $this->createMany($medicationStatement->derivedFrom, 'createReferenceResource'),
            'reasonCode' => $this->createMany($medicationStatement->reasonCode, 'createCodeableConceptResource'),
            'reasonReference' => $this->createMany($medicationStatement->reasonReference, 'createReferenceResource'),
            'note' => $this->createMany($medicationStatement->note, 'createAnnotationResource'),
            'dosage' => $this->createMany($medicationStatement->dosage, 'createDosageResource'),
        ];
    }
}
