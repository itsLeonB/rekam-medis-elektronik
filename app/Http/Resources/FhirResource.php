<?php

namespace App\Http\Resources;

use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\JsonResource;

class FhirResource extends JsonResource
{
    /**
     * Get the data of a specific resource type.
     *
     * @param string $resourceType The type of resource to retrieve data from.
     *
     * @return mixed The data of the specified resource type.
     *
     * @throws ModelNotFoundException If the data is not found.
     */
    public function getData($resourceType)
    {
        $data = $this->resource ? $this->resource->$resourceType->first() : null;

        if ($data == null) {
            throw new ModelNotFoundException('Data tidak ditemukan');
        } else {
            return $data;
        }
    }

    public function parseDate(&$array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $this->parseDate($value); // Recursively process nested arrays
            } elseif ($value instanceof DateTime) {
                $value = $this->parseDateFhir($value);
            }
        }
        return $array;
    }

    public function parseDateFhir($date)
    {
        if ($date != null) {
            // Create a DateTime object with the input date
            $dateTime = new DateTime($date);

            // Set the desired time zone for Jakarta (+07:00)
            $dateTime->setTimezone(new DateTimeZone('Asia/Jakarta'));

            // Format the date in the desired format
            $formattedDate = $dateTime->format('Y-m-d\TH:i:sP');

            return $formattedDate;
        } else {
            return null;
        }
    }

    public function createIdentifierArray($identifierAttribute)
    {
        $identifier = [];

        if (is_array($identifierAttribute) || is_object($identifierAttribute)) {
            foreach ($identifierAttribute as $i) {
                $identifier[] = [
                    'system' => $i->system,
                    'use' => $i->use,
                    'value' => $i->value,
                ];
            }
        }

        return $identifier;
    }

    public function createTelecomArray($telecomAttribute)
    {
        $telecom = [];

        if (is_array($telecomAttribute) || is_object($telecomAttribute)) {
            foreach ($telecomAttribute as $t) {
                $telecom[] = [
                    'system' => $t->system,
                    'use' => $t->use,
                    'value' => $t->value,
                ];
            }
        }

        return $telecom;
    }

    public function createAddressArray($addressAttribute)
    {
        $addressData = [];

        if (is_array($addressAttribute) || is_object($addressAttribute)) {
            foreach ($addressAttribute as $a) {
                $addressData[] = [
                    'use' => $a->use,
                    'line' => [$a->line],
                    'country' => $a->country,
                    'postalCode' => $a->postal_code,
                    'extension' => [
                        [
                            'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/AdministrativeCode',
                            'extension' => [
                                [
                                    'url' => 'province',
                                    'valueCode' => $a->province == 0 ? null : $a->province,
                                ],
                                [
                                    'url' => 'city',
                                    'valueCode' => $a->city == 0 ? null : $a->city,
                                ],
                                [
                                    'url' => 'district',
                                    'valueCode' => $a->district == 0 ? null : $a->district,
                                ],
                                [
                                    'url' => 'village',
                                    'valueCode' => $a->village == 0 ? null : $a->village,
                                ],
                                [
                                    'url' => 'rt',
                                    'valueCode' => $a->rt == 0 ? null : $a->rt,
                                ],
                                [
                                    'url' => 'rw',
                                    'valueCode' => $a->rw == 0 ? null : $a->rw,
                                ],
                            ]
                        ]
                    ]
                ];
            }
        }

        return $addressData;
    }

    public function createReferenceArray($referenceAttribute)
    {
        $reference = [];

        if (is_array($referenceAttribute) || is_object($referenceAttribute)) {
            foreach ($referenceAttribute as $ref) {
                $reference[] = [
                    'reference' => $ref->reference,
                ];
            }
        }

        return $reference;
    }

    public function createCodeableConceptArray($codeableConceptAttribute): array
    {
        $codeableConcept = [];

        if (is_array($codeableConceptAttribute) || is_object($codeableConceptAttribute)) {
            foreach ($codeableConceptAttribute as $cc) {
                $codeableConcept[] = [
                    'coding' => [
                        [
                            'system' => $cc->system,
                            'code' => $cc->code,
                            'display' => $cc->display,
                        ]
                    ]
                ];
            }
        }

        return $codeableConcept;
    }

    public function createAnnotationArray($annotationAttribute): array
    {
        $annotation = [];

        if (is_array($annotationAttribute) || is_object($annotationAttribute)) {
            foreach ($annotationAttribute as $a) {
                $annotation[] = merge_array(
                    json_decode($a->author, true),
                    [
                        'time' => $this->parseDateFhir($a->time),
                        'text' => $a->text
                    ]
                );
            }
        }

        return $annotation;
    }
}
