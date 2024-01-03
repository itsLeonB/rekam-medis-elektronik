<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class PractitionerResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $practitioner = $this->getData('practitioner');

        $data = $this->resourceStructure($practitioner);

        $data = $this->removeEmptyValues($data);

        return $data;
    }


    public function resourceStructure($practitioner): array
    {
        return [
            'resourceType' => 'Practitioner',
            'id' => $this->satusehat_id,
            'identifier' => $this->createMany($practitioner->identifier, 'createIdentifierResource'),
            'active' => $practitioner->active,
            'name' => $this->createMany($practitioner->name, 'createHumanNameResource'),
            'telecom' => $this->createMany($practitioner->telecom, 'createContactPointResource'),
            'address' => $this->createMany($practitioner->address, 'createAddressResource'),
            'gender' => $practitioner->gender,
            'birthDate' => $this->parseDate($practitioner->birth_date),
            'photo' => $this->createMany($practitioner->photo, 'createAttachmentResource'),
            'qualification' => $this->createMany($practitioner->qualification, 'createQualificationResource'),
            'communication' => $this->createMany($practitioner->communication, 'createCodeableConceptResource')
        ];
    }

    public function createQualificationResource($qualification)
    {
        if (!empty($qualification)) {
            return [
                'identifier' => $this->createMany($qualification->identifier, 'createIdentifierResource'),
                'code' => $this->createCodeableConceptResource($qualification->code),
                'period' => $this->createPeriodResource($qualification->period),
                'issuer' => $this->createReferenceResource($qualification->issuer)
            ];
        } else {
            return null;
        }
    }
}
