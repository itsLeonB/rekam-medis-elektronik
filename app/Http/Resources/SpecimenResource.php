<?php

namespace App\Http\Resources;

use App\Models\Specimen;
use App\Models\SpecimenProcessing;
use App\Models\ValueSetSpecimenContainerType;
use Illuminate\Http\Request;

class SpecimenResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $serviceRequest = $this->getData('servicerequest');

        $data = $this->resourceStructure($serviceRequest);

        $data = removeEmptyValues($data);

        return $data;
    }


    private function resourceStructure(object $specimen): array
    {
        return [
            'resourceType' => 'Specimen',
            'id' => $this->satusehat_id,
            'identifier' => $this->createIdentifierArray($specimen->identifier),
            'accessionIdentifier' => [
                'system' => $specimen->accession_identifier_system,
                'use' => $specimen->accession_identifier_use,
                'value' => $specimen->accession_identifier_value,
            ],
            'status' => $specimen->status,
            'type' => [
                'coding' => [
                    [
                        'system' => $specimen->type_system,
                        'code' => $specimen->type_code,
                        'display' => $specimen->type_display,
                    ],
                ]
            ],
            'subject' => [
                'reference' => $specimen->subject,
            ],
            'receivedTime' => $specimen->received_time,
            'parent' => $this->createReferenceArray($specimen->parent),
            'request' => $this->createReferenceArray($specimen->request),
            'collection' => [
                'collector' => [
                    'reference' => $specimen->collector,
                ],
                $specimen->collection_collected,
                'duration' => [
                    'value' => $specimen->collection_duration_value,
                    'comparator' => $specimen->collection_duration_comparator,
                    'unit' => $specimen->collection_duration_unit,
                    'system' => $specimen->collection_duration_system,
                    'code' => $specimen->collection_duration_code,
                ],
                'quantity' => [
                    'value' => $specimen->collection_quantity_value,
                    'unit' => $specimen->collection_quantity_unit,
                    'system' => $specimen->collection_quantity_system,
                    'code' => $specimen->collection_quantity_code,
                ],
                'method' => [
                    'coding' => [
                        [
                            'system' => $specimen->collection_method ? Specimen::COLLECTION_METHOD_SYSTEM : null,
                            'code' => $specimen->collection_method,
                            'display' => $specimen->collection_method ? Specimen::COLLECTION_METHOD_DISPLAY[$specimen->collection_method] : null
                        ],
                    ]
                ],
                'bodySite' => [
                    'coding' => [
                        [
                            'system' => $specimen->collection_body_site_system,
                            'code' => $specimen->collection_body_site_code,
                            'display' => $specimen->collection_body_site_display,
                        ],
                    ]
                ],
                $specimen->collection_fasting_status
            ],
            'processing' => $this->createProcessingArray($specimen->processing),
            'container' => $this->createContainerArray($specimen->container),
            'condition' => $this->createCodeableConceptArray($specimen->condition),
            'note' => $this->createAnnotationArray($specimen->note)
        ];
    }


    private function createProcessingArray($processingAttribute): array
    {
        $processing = [];

        if (!empty($processingAttribute)) {
            foreach ($processingAttribute as $p) {
                $processing[] = [
                    'description' => $p->description,
                    'procedure' => [
                        'coding' => [
                            [
                                'system' => $p->procedure ? SpecimenProcessing::PROCEDURE_SYSTEM : null,
                                'code' => $p->procedure,
                                'display' => $p->procedure ? SpecimenProcessing::PROCEDURE_DISPLAY[$p->procedure] : null
                            ]
                        ]
                    ],
                    $p->additive,
                    $p->time
                ];
            }
        }

        return $processing;
    }


    private function createContainerArray($containerAttribute): array
    {
        $container = [];

        if(!empty($containerAttribute)) {
            foreach ($containerAttribute as $c) {
                $container[] = [
                    'identifier' => $this->createIdentifierArray($c->identifier),
                    'description' => $c->description,
                    'type' => [
                        'coding' => [
                            [
                                'system' => $c->type_code ? ValueSetSpecimenContainerType::SYSTEM : null,
                                'code' => $c->type_code,
                                'display' => $c->type_code ? ValueSetSpecimenContainerType::where('code', $c->type_code)->first()->display : null,
                            ],
                        ]
                    ],
                    'capacity' => [
                        'value' => $c->capacity_value,
                        'unit' => $c->capacity_unit,
                        'system' => $c->capacity_system,
                        'code' => $c->capacity_code,
                    ],
                    'specimenQuantity' => [
                        'value' => $c->specimen_quantity_value,
                        'unit' => $c->specimen_quantity_unit,
                        'system' => $c->specimen_quantity_system,
                        'code' => $c->specimen_quantity_code,
                    ],
                    $c->additive
                ];
            }
        }

        return $container;
    }
}
