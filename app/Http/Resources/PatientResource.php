<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class PatientResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $patient = $this->getData('patient');

        $data = $this->resourceStructure($patient);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    public function resourceStructure($patient): array
    {
        return [
            'resourceType' => 'Patient',
            'id' => $this->satusehat_id,
            'identifier' => $this->createMany($patient->identifier, 'createIdentifierResource'),
            'active' => $patient->active,
            'name' => $this->createMany($patient->name, 'createHumanNameResource'),
            'telecom' => $this->createMany($patient->telecom, 'createContactPointResource'),
            'gender' => $patient->gender,
            'birthDate' => $this->parseDate($patient->birth_date),
            'deceasedBoolean' => $patient->deceased_boolean,
            'deceasedDateTime' => $this->parseDateTime($patient->deceased_date_time),
            'address' => $this->createMany($patient->address, 'createAddressResource'),
            'maritalStatus' => $this->createCodeableConceptResource($patient->marital_status),
            'multipleBirthBoolean' => $patient->multiple_birth_boolean,
            'multipleBirthInteger' => $patient->multiple_birth_integer,
            'photo' => $this->createMany($patient->photo, 'createAttachmentResource'),
            'contact' => $this->createMany($patient->contact, 'createContactResource'),
            'communication' => $this->createMany($patient->communication, 'createCommunicationResource'),
            'generalPractitioner' => $this->createMany($patient->general_practitioner, 'createReferenceResource'),
            'managingOrganization' => $this->createReferenceResource($patient->managing_organization),
            'link' => $this->createMany($patient->link, 'createLinkResource'),
            'extension' => [
                $this->createExtensionResource($patient->birthPlace),
                $this->createComplexExtensionResource($patient->citizenship),
                $this->createExtensionResource($patient->religion),
                $this->createExtensionResource($patient->citizenshipStatus)
            ]
        ];
    }

    public function createLinkResource($link)
    {
        if (!empty($link)) {
            return [
                'other' => $this->createReferenceResource($link->other),
                'type' => $link->type
            ];
        } else {
            return null;
        }
    }

    public function createCommunicationResource($communication)
    {
        if (!empty($communication)) {
            return [
                'language' => $this->createCodeableConceptResource($communication->language),
                'preferred' => $communication->preferred
            ];
        } else {
            return null;
        }
    }

    public function createContactResource($contact)
    {
        if (!empty($contact)) {
            return [
                'relationship' => $this->createMany($contact->relationship, 'createCodeableConceptResource'),
                'name' => $this->createHumanNameResource($contact->name),
                'telecom' => $this->createMany($contact->telecom, 'createContactPointResource'),
                'address' => $this->createAddressResource($contact->address),
                'gender' => $contact->gender,
                'organization' => $this->createReferenceResource($contact->organization),
                'period' => $this->createPeriodResource($contact->period)
            ];
        } else {
            return null;
        }
    }
}
