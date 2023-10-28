<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FhirResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'resourceType' => $this->res_type,
            'id' => $this->satusehat_id,
            'extension' => [
                [
                    'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace',
                    'valueAddress' => [
                        'city' => $this->patient[0]->birth_place,
                    ]
                ],
            ],
        ];
    }
}
