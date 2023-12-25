<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class MedicationResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $medication = $this->getData('medication');

        $data = $this->resourceStructure($medication);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    public function resourceStructure($medication): array
    {
        return [
            'resourceType' => 'Medication',
            'id' => $this->satusehat_id,
            'identifier' => $this->createMany($medication->identifier, 'createIdentifierResource'),
            'code' => $this->createCodeableConceptResource($medication->code),
            'status' => $medication->status,
            'manufacturer' => $this->createReferenceResource($medication->manufacturer),
            'form' => $this->createCodeableConceptResource($medication->form),
            'amount' => $this->createRatioResource($medication->amount),
            'ingredient' => $this->createMany($medication->ingredient, 'createIngredientResource'),
            'batch' => $this->createBatchResource($medication->batch),
            'extension' => [
                $this->createExtensionResource($medication->serviceClass)
            ]
        ];
    }

    public function createIngredientResource($ingredient)
    {
        if (!empty($ingredient)) {
            return [
                'itemCodeableConcept' => $this->createCodeableConceptResource($ingredient->itemCodeableConcept),
                'itemReference' => $this->createReferenceResource($ingredient->itemReference),
                'isActive' => $ingredient->is_active,
                'strength' => $this->createRatioResource($ingredient->strength)
            ];
        } else {
            return null;
        }
    }

    public function createBatchResource($batch)
    {
        if (!empty($batch)) {
            return [
                'lotNumber' => $batch->lot_number,
                'expirationDate' => $this->parseDateTime($batch->expiration_date),
            ];
        } else {
            return null;
        }
    }
}
