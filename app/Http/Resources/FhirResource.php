<?php

namespace App\Http\Resources;

use DateTime;
use DateTimeZone;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\JsonResource;

class FhirResource extends JsonResource
{
    public function querySnomedCode($code): array
    {
        $client = new Client();

        $response = $client->request('GET', 'https://browser.ihtsdotools.org/snowstorm/snomed-ct/MAIN/concepts/' . $code, [
            'headers' => [
                'accept' => 'application/json',
                'Accept-Language' => 'en-X-900000000000509007,en-X-900000000000508004,en',
            ],
        ]);

        $body = $response->getBody();
        $data = json_decode($body, true);

        return [
            'system' => 'http://snomed.info/sct',
            'code' => $code,
            'display' => $data['fsn']['term'],
        ];
    }


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
                    'line' => $a->line,
                    'country' => $a->country,
                    'postalCode' => $a->postal_code,
                    'extension' => [
                        [
                            'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/AdministrativeCode',
                            'extension' => [
                                [
                                    'url' => $a->province ? 'province' : null,
                                    'valueCode' => $a->province == 0 ? null : $a->province,
                                ],
                                [
                                    'url' => $a->city ? 'city' : null,
                                    'valueCode' => $a->city == 0 ? null : $a->city,
                                ],
                                [
                                    'url' => $a->district ? 'district' : null,
                                    'valueCode' => $a->district == 0 ? null : $a->district,
                                ],
                                [
                                    'url' => $a->village ? 'village' : null,
                                    'valueCode' => $a->village == 0 ? null : $a->village,
                                ],
                                [
                                    'url' => $a->rt ? 'rt' : null,
                                    'valueCode' => $a->rt == 0 ? null : $a->rt,
                                ],
                                [
                                    'url' => $a->rw ? 'rw' : null,
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
                    $a->author,
                    [
                        'time' => $this->parseDateFhir($a->time),
                        'text' => $a->text
                    ]
                );
            }
        }

        return $annotation;
    }

    public function createDosageArray($dosageAttribute): array
    {
        $dosage = [];

        if (is_array($dosageAttribute) || is_object($dosageAttribute)) {
            foreach ($dosageAttribute as $d) {
                $dosage[] = [
                    'sequence' => $d->sequence,
                    'text' => $d->text,
                    'additionalInstruction' => $this->createCodeableConceptArray($d->additionalInstruction),
                    'patientInstruction' => $d->patient_instruction,
                    'timing' => [
                        'event' => $d->timing_event,
                        'repeat' => $d->timing_repeat,
                        'code' => [
                            'coding' => [
                                [
                                    'system' => $d->timing_system,
                                    'code' => $d->timing_code,
                                    'display' => $d->timing_display
                                ]
                            ]
                        ]
                    ],
                    'site' => [
                        'coding' => [
                            [
                                'system' => $d->site_system,
                                'code' => $d->site_code,
                                'display' => $d->site_display
                            ]
                        ]
                    ],
                    'route' => [
                        'coding' => [
                            [
                                'system' => $d->route_system,
                                'code' => $d->route_code,
                                'display' => $d->route_display
                            ]
                        ]
                    ],
                    'method' => [
                        'coding' => [
                            [
                                'system' => $d->method_system,
                                'code' => $d->method_code,
                                'display' => $d->method_display
                            ]
                        ]
                    ],
                    'doseAndRate' => $this->createDoseRateArray($d->doseRate),
                    'maxDosePerPeriod' => [
                        'numerator' => [
                            'value' => $d->max_dose_per_period_numerator_value,
                            'comparator' => $d->max_dose_per_period_numerator_comparator,
                            'unit' => $d->max_dose_per_period_numerator_unit,
                            'system' => $d->max_dose_per_period_numerator_system,
                            'code' => $d->max_dose_per_period_numerator_code,
                        ],
                        'denominator' => [
                            'value' => $d->max_dose_per_period_denominator_value,
                            'comparator' => $d->max_dose_per_period_denominator_comparator,
                            'unit' => $d->max_dose_per_period_denominator_unit,
                            'system' => $d->max_dose_per_period_denominator_system,
                            'code' => $d->max_dose_per_period_denominator_code,
                        ]
                    ],
                    'maxDosePerAdministration' => [
                        'value' => $d->max_dose_per_administration_value,
                        'unit' => $d->max_dose_per_administration_unit,
                        'system' => $d->max_dose_per_administration_system,
                        'code' => $d->max_dose_per_administration_code,
                    ],
                    'maxDosePerLifetime' => [
                        'value' => $d->max_dose_per_lifetime_value,
                        'unit' => $d->max_dose_per_lifetime_unit,
                        'system' => $d->max_dose_per_lifetime_system,
                        'code' => $d->max_dose_per_lifetime_code,
                    ]
                ];
            }
        }

        return $dosage;
    }

    public function createDoseRateArray($doseRateAttribute): array
    {
        $doseRate = [];

        if (is_array($doseRateAttribute) || is_object($doseRateAttribute)) {
            foreach ($doseRateAttribute as $dr) {
                $doseRate[] = merge_array(
                    [
                        'type' => [
                            'coding' => [
                                [
                                    'system' => $dr->system,
                                    'code' => $dr->code,
                                    'display' => $dr->display
                                ]
                            ]
                        ],
                    ],
                    $dr->dose,
                    $dr->rate
                );
            }
        }

        return $doseRate;
    }
}
