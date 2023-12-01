<?php

namespace App\Http\Resources;

use App\Fhir\Dosage;
use App\Fhir\Timing;
use App\Models\MedicationStatement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $data = removeEmptyValues($data);

        return $data;
    }


    private function resourceStructure($medicationStatement): array
    {
        return merge_array(
            [
                'resourceType' => 'MedicationStatement',
                'id' => $this->satusehat_id,
                'identifier' => $this->createIdentifierArray($medicationStatement->identifier),
                'basedOn' => $this->referenceArray($medicationStatement->based_on),
                'partOf' => $this->referenceArray($medicationStatement->part_of),
                'status' => $medicationStatement->status,
                'statusReason' => $this->createSnomedConceptArray($medicationStatement->status_reason),
                'category' => [
                    'coding' => [
                        [
                            'system' => $medicationStatement->category ? MedicationStatement::CATEGORY['binding']['valueset']['system'] ?? null : null,
                            'code' => $medicationStatement->category,
                            'display' => $medicationStatement->category ? MedicationStatement::CATEGORY['binding']['valueset']['display'][$medicationStatement->category] ?? null : null
                        ]
                    ]
                ],
                'subject' => [
                    'reference' => $medicationStatement->subject
                ],
                'context' => [
                    'reference' => $medicationStatement->context
                ],
                'dateAsserted' => $this->parseDateFhir($medicationStatement->date_asserted),
                'informationSource' => [
                    'reference' => $medicationStatement->information_source
                ],
                'derivedFrom' => $this->referenceArray($medicationStatement->derived_from),
                'reasonCode' => $this->createSnomedConceptArray($medicationStatement->reason_code),
                'reasonReference' => $this->referenceArray($medicationStatement->reason_reference),
                'note' => $this->createAnnotationArray($medicationStatement->note),
                'dosage' => $this->dosageArray($medicationStatement->dosage),
            ],
            $medicationStatement->medication,
            $medicationStatement->effective,
        );
    }


    private function createDoseAndRateArray($doseRates): array
    {
        $doseRate = [];

        if (!empty($doseRates)) {
            foreach ($doseRates as $dr) {
                $doseRate[] = merge_array(
                    [
                        'type' => [
                            'coding' => [
                                [
                                    'system' => $dr->type ? Dosage::DOSE_AND_RATE_TYPE['binding']['valueset']['system'] ?? null : null,
                                    'code' => $dr->type,
                                    'display' => $dr->type ? Dosage::DOSE_AND_RATE_TYPE['binding']['valueset']['display'][$dr->type] ?? null : null
                                ]
                            ]
                        ]
                    ],
                    $dr->dose,
                    $dr->rate,
                );
            }
        }

        return $doseRate;
    }


    private function dosageArray($dosages): array
    {
        $dosage = [];

        if (!empty($dosages)) {
            foreach ($dosages as $d) {
                $dosage[] = merge_array(
                    [
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
                                        'system' => $d->timing_code ? Timing::CODE['binding']['valueset']['system'] ?? null : null,
                                        'code' => $d->timing_code,
                                        'display' => $d->timing_code ? Timing::CODE['binding']['valueset']['display'][$d->timing_code] ?? null : null
                                    ]
                                ]
                            ]
                        ],
                        'site' => [
                            'coding' => [
                                [
                                    'system' => $d->site ? Dosage::SITE['binding']['valueset']['system'] ?? null : null,
                                    'code' => $d->site,
                                    'display' => $d->site ? DB::table(Dosage::SITE['binding']['valueset']['table'])
                                        ->select('display')
                                        ->where('code', '=', $d->site)
                                        ->first() ?? null : null
                                ]
                            ]
                        ],
                        'route' => [
                            'coding' => [
                                [
                                    'system' => $d->route ? Dosage::ROUTE['binding']['valueset']['system'] ?? null : null,
                                    'code' => $d->route,
                                    'display' => $d->route ? Dosage::ROUTE['binding']['valueset']['display'][$d->route] ?? null : null
                                ]
                            ]
                        ],
                        'method' => [
                            'coding' => [
                                [
                                    'system' => $d->method ? Dosage::METHOD['binding']['valueset']['system'] ?? null : null,
                                    'code' => $d->method,
                                    'display' => $d->method ? Dosage::METHOD['binding']['valueset']['display'][$d->method] ?? null : null
                                ]
                            ]
                        ],
                        'doseAndRate' => $this->createDoseAndRateArray($d->doseRate),
                        'maxDosePerPeriod' => [
                            'numerator' => [
                                'value' => $d->max_dose_per_period_numerator_value,
                                'unit' => $d->max_dose_per_period_numerator_unit,
                                'system' => $d->max_dose_per_period_numerator_system,
                                'code' => $d->max_dose_per_period_numerator_code,
                            ],
                            'denominator' => [
                                'value' => $d->max_dose_per_period_denominator_value,
                                'unit' => $d->max_dose_per_period_denominator_unit,
                                'system' => $d->max_dose_per_period_denominator_system,
                                'code' => $d->max_dose_per_period_denominator_code,
                            ],
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
                        ],
                    ],
                    $d->as_needed,
                );
            }
        }

        return $dosage;
    }


    private function createAdditionalInstructionArray($additionalInstructions): array
    {
        $additionalInstruction = [];

        if (!empty($additionalInstructions)) {
            foreach ($additionalInstructions as $ai) {
                $additionalInstruction[] = [
                    'coding' => [
                        [
                            'system' => $ai ? Dosage::ADDITIONAL_INSTRUCTION['binding']['valueset']['system'] ?? null : null,
                            'code' => $ai,
                            'display' => $ai ? Dosage::ADDITIONAL_INSTRUCTION['binding']['valueset']['display'][$ai] ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $additionalInstruction;
    }


    private function createSnomedConceptArray($snomedCodes): array
    {
        $snomedArray = [];

        if (!empty($snomedCodes)) {
            foreach ($snomedCodes as $sc) {
                $arr = $this->querySnomedCode($sc);
                $snomedArray[] = [
                    'coding' => [$arr]
                ];
            }
        }

        return $snomedArray;
    }


    private function referenceArray($references): array
    {
        $reference = [];

        if (!empty($references)) {
            foreach ($references as $r) {
                $reference[] = [
                    'reference' => $r
                ];
            }
        }

        return $reference;
    }
}
