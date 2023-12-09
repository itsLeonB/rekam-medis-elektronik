<?php

namespace App\Http\Resources;

use App\Models\Practitioner;
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

        $data = removeEmptyValues($data);

        return $data;
    }


    private function resourceStructure($practitioner): array
    {
        return [
            'resourceType' => 'Practitioner',
            'id' => $this->satusehat_id,
            'identifier' => $this->createIdentifierArray($practitioner->identifier),
            'active' => $practitioner->active,
            'name' => $this->createHumanNameArray($practitioner->name),
            'telecom' => $this->createTelecomArray($practitioner->telecom),
            'address' => $this->createAddressArray($practitioner->address),
            'gender' => $practitioner->gender,
            'birthDate' => $this->parseDateFhir($practitioner->birth_date),
            'photo' => $this->createPhotoArray($practitioner->photo),
            'qualification' => $this->createQualificationArray($practitioner->qualification),
            'communication' => $this->createCommunicationArray($practitioner->communication),
        ];
    }


    private function createCommunicationArray($communications): array
    {
        $data = [];

        if (!empty($communications)) {
            foreach ($communications as $c) {
                $data[] = [
                    'coding' => [
                        [
                            'system' => $c ? Practitioner::COMMUNICATION['binding']['valueset']['system'] : null,
                            'code' => $c,
                            'display' => $c ? Practitioner::COMMUNICATION['binding']['valueset']['display'][$c] ?? null : null,
                        ]
                    ]
                ];
            }
        }

        return $data;
    }


    private function createQualificationArray($qualifications): array
    {
        $data = [];

        if (!empty($qualifications)) {
            foreach ($qualifications as $qualification) {
                $data[] = [
                    'identifier' => $this->createIdentifierArray($qualification->identifier),
                    'code' => [
                        'coding' => [
                            [
                                'system' => $qualification->code_system,
                                'code' => $qualification->code_code,
                                'display' => $qualification->code_display,
                            ]
                        ],
                    ],
                    'period' => [
                        'start' => $this->parseDateFhir($qualification->period_start),
                        'end' => $this->parseDateFhir($qualification->period_end),
                    ],
                    'issuer' => [
                        'reference' => $qualification->issuer,
                    ]
                ];
            }
        }

        return $data;
    }
}
