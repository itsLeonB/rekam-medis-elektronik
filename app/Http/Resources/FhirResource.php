<?php

namespace App\Http\Resources;

use App\Fhir\Codesystems;
use App\Fhir\Dosage;
use App\Fhir\Timing;
use DateTime;
use DateTimeZone;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class FhirResource extends JsonResource
{
    public function createPhotoArray($photos): array
    {
        $photo = [];

        if (is_array($photos) || is_object($photos)) {
            foreach ($photos as $p) {
                $photo[] = [
                    'data' => $p->data,
                    'url' => $p->url,
                    'size' => $p->size,
                    'hash' => $p->hash,
                    'title' => $p->title,
                    'creation' => $this->parseDateFhir($p->creation),
                ];
            }
        }

        return $photo;
    }


    public function createHumanNameArray($names): array
    {
        $humanName = [];

        if (is_array($names) || is_object($names)) {
            foreach ($names as $name) {
                $humanName[] = [
                    'use' => $name->use,
                    'text' => $name->text,
                    'family' => $name->family,
                    'given' => $name->given,
                    'prefix' => $name->prefix,
                    'suffix' => $name->suffix,
                    'period' => [
                        'start' => $this->parseDateFhir($name->period_start),
                        'end' => $this->parseDateFhir($name->period_end),
                    ]
                ];
            }
        }

        return $humanName;
    }


    public function searchSnomed(string $ecl, string $term, Client $client)
    {
        $headers = [
            'Accept' => 'application/json',
            'Accept-Language' => 'en-X-900000000000509007,en-X-900000000000508004,en',
        ];

        $query = [
            'term' => $term,
            'ecl' => $ecl,
            'includeLeafFlag' => 'false',
            'form' => 'inferred',
            'offset' => 0,
            'limit' => 50,
        ];

        $response = $client->request('GET', Codesystems::SNOMEDCT['url'], [
            'headers' => $headers,
            'query' => $query,
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        return $body;
    }

    public function querySnomedCode($code): string
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

        return $data['fsn']['term'];
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
                    'type' => $a->type,
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
                    'reference' => $ref,
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
                $annotation[] = $this->mergeArray(
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

        if (!empty($dosageAttribute)) {
            foreach ($dosageAttribute as $d) {
                $dosage[] = [
                    'sequence' => $d->sequence,
                    'text' => $d->text,
                    'additionalInstruction' => $this->createAdditionalInstructionArray($d->additional_instruction),
                    'patientInstruction' => $d->patient_instruction,
                    'timing' => [
                        'event' => $d->timing_event,
                        'repeat' => $d->timing_repeat,
                        'code' => [
                            'coding' => [
                                [
                                    'system' => $d->timing_code ? Timing::CODE['binding']['valueset']['system'] : null,
                                    'code' => $d->timing_code,
                                    'display' => $d->timing_code ? Timing::CODE['binding']['valueset']['display'][$d->timing_code] ?? null : null
                                ]
                            ]
                        ]
                    ],
                    'site' => [
                        'coding' => [
                            [
                                'system' => $d->site ? Dosage::SITE['binding']['valueset']['system'] : null,
                                'code' => $d->site,
                                'display' => $d->site ? DB::table(Dosage::SITE['binding']['valueset']['table'])
                                    ->select('display')
                                    ->where('code', $d->site)
                                    ->first()->display ?? null : null
                            ]
                        ]
                    ],
                    'route' => [
                        'coding' => [
                            [
                                'system' => $d->route ? Dosage::ROUTE['binding']['valueset']['system'] : null,
                                'code' => $d->route,
                                'display' => $d->route ? Dosage::ROUTE['binding']['valueset']['display'][$d->route] ?? null : null
                            ]
                        ]
                    ],
                    'method' => [
                        'coding' => [
                            [
                                'system' => $d->method ? Dosage::METHOD['binding']['valueset']['system'] : null,
                                'code' => $d->method,
                                'display' => $d->method ? Dosage::METHOD['binding']['valueset']['display'][$d->method] ?? null : null
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


    private function createAdditionalInstructionArray($additionalInstructionAttribute): array
    {
        $additionalInstruction = [];

        if (is_array($additionalInstructionAttribute) || is_object($additionalInstructionAttribute)) {
            foreach ($additionalInstructionAttribute as $ai) {
                $additionalInstruction[] = [
                    'coding' => [
                        [
                            'system' => $ai ? Dosage::ADDITIONAL_INSTRUCTION['binding']['valueset']['system'] : null,
                            'code' => $ai,
                            'display' => $ai ? Dosage::ADDITIONAL_INSTRUCTION['binding']['valueset']['display'][$ai] ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $additionalInstruction;
    }


    public function createDoseRateArray($doseRateAttribute): array
    {
        $doseRate = [];

        if (is_array($doseRateAttribute) || is_object($doseRateAttribute)) {
            foreach ($doseRateAttribute as $dr) {
                $doseRate[] = $this->mergeArray(
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


    public function mergeArray(...$arrays)
    {
        $arr = [];

        foreach ($arrays as $a) {
            if ($a != null) {
                $arr = array_merge($arr, $a);
            }
        }

        return $arr;
    }

    public function removeEmptyValues($array)
    {
        return array_filter($array, function ($value) {
            if (is_array($value)) {
                return !empty($this->removeEmptyValues($value));
            }
            return $value !== null && $value !== "" && !(is_array($value) && empty($value));
        });
    }
}
