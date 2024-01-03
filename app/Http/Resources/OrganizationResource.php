<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class OrganizationResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $observation = $this->getData('organization');

        $data = $this->resourceStructure($observation);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    public function resourceStructure($organization): array
    {
        return [
            'resourceType' => 'Organization',
            'id' => $this->satusehat_id,
            'identifier' => $this->createMany($organization->identifier, 'createIdentifierResource'),
            'active' => $organization->active,
            'type' => $this->createMany($organization->type, 'createCodeableConceptResource'),
            'name' => $organization->name,
            'alias' => $organization->alias,
            'telecom' => $this->createMany($organization->telecom, 'createContactPointResource'),
            'address' => $this->createMany($organization->address, 'createAddressResource'),
            'partOf' => $this->createReferenceResource($organization->partOf),
            'contact' => $this->createMany($organization->contact, 'createContactResource'),
            'endpoint' => $this->createMany($organization->endpoint, 'createReferenceResource')
        ];
    }

    public function createContactResource($contact): array|null
    {
        if (!empty($contact)) {
            return [
                'purpose' => $this->createCodeableConceptResource($contact->purpose),
                'name' => $this->createHumanNameResource($contact->name),
                'telecom' => $this->createMany($contact->telecom, 'createContactPointResource'),
                'address' => $this->createAddressResource($contact->address)
            ];
        } else {
            return null;
        }
    }
}
