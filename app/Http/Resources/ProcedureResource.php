<?php

namespace App\Http\Resources;

use App\Models\Procedure;
use App\Models\ValueSetProcedurePerformerType;
use App\Models\ValueSetProcedureReasonCode;
use Illuminate\Http\Request;

class ProcedureResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $procedure = $this->getData('procedure');

        $data = $this->resourceStructure($procedure);

        $data = removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($procedure): array
    {
        return merge_array(
            [
                'resourceType' => 'Procedure',
                'id' => $this->satusehat_id,
                'identifier' => $this->createIdentifierArray($procedure->identifier),
                'basedOn' => $this->createReferenceArray($procedure->basedOn),
                'partOf' => $this->createReferenceArray($procedure->partOf),
                'status' => $procedure->status,
                'statusReason' => [
                    'coding' => [
                        [
                            'system' => $procedure->status_reason ? ValueSetProcedureReasonCode::SYSTEM : null,
                            'code' => $procedure->status_reason,
                            'display' => ValueSetProcedureReasonCode::where('code', $procedure->status_reason)->first()->display ?? null
                        ]
                    ]
                ],
                'category' => [
                    'coding' => [
                        [
                            'system' => Procedure::CATEGORY_SYSTEM,
                            'code' => $procedure->category,
                            'display' => Procedure::CATEGORY_DISPLAY[$procedure->category] ?? null
                        ]
                    ]
                ],
                'code' => [
                    'code' => [
                        [
                            'system' => $procedure->code_system,
                            'code' => $procedure->code_code,
                            'display' => $procedure->code_display
                        ]
                    ]
                ],
                'subject' => [
                    'reference' => $procedure->subject
                ],
                'encounter' => [
                    'reference' => $procedure->encounter
                ],
                'recorder' => [
                    'reference' => $procedure->recorder
                ],
                'asserter' => [
                    'reference' => $procedure->asserter
                ],
                'performer' => $this->createPerformerArray($procedure->performer),
                'location' => [
                    'reference' => $procedure->location
                ],
                'reasonCode' => $this->createCodeableConceptArray($procedure->reason),
                'reasonReference' => $this->createReferenceArray($procedure->reason),
                'bodySite' => $this->createCodeableConceptArray($procedure->body_site),
                'outcome' => [
                    'coding' => [
                        [
                            'system' => $procedure->outcome ? Procedure::OUTCOME_SYSTEM : null,
                            'code' => $procedure->outcome,
                            'display' => Procedure::OUTCOME_DISPLAY[$procedure->outcome] ?? null
                        ]
                    ]
                ],
                'report' => $this->createReferenceArray($procedure->report),
                'complication' => $this->createCodeableConceptArray($procedure->complication),
                'complicationDetail' => $this->createReferenceArray($procedure->complication),
                'followUp' => $this->createCodeableConceptArray($procedure->followUp),
                'note' => $this->createAnnotationArray($procedure->note),
                'focalDevice' => $this->createFocalDeviceArray($procedure->focalDevice),
                'usedReference' => $this->createReferenceArray($procedure->itemUsed),
                'usedCode' => $this->createCodeableConceptArray($procedure->itemUsed)
            ],
            $procedure->performed
        );
    }

    private function createPerformerArray($performerAttribute): array
    {
        $performer = [];

        if (is_array($performerAttribute) || is_object($performerAttribute)) {
            foreach ($performerAttribute as $p) {
                $performer[] = [
                    'function' => [
                        'coding' => [
                            [
                                'system' => $p->function ? ValueSetProcedurePerformerType::SYSTEM : null,
                                'code' => $p->function,
                                'display' => ValueSetProcedurePerformerType::where('code', $p->function)->first()->display ?? null
                            ]
                        ]
                    ],
                    'actor' => [
                        'reference' => $p->actor
                    ],
                    'onBehalfOf' => [
                        'reference' => $p->on_behalf_of
                    ],
                ];
            }
        }

        return $performer;
    }

    private function createFocalDeviceArray($focalDeviceAttribute): array
    {
        $focalDevice = [];

        if (is_array($focalDeviceAttribute) || is_object($focalDeviceAttribute)) {
            foreach ($focalDeviceAttribute as $fd) {
                $focalDevice[] = [
                    'action' => [
                        'coding' => [
                            [
                                'system' => $fd->system,
                                'code' => $fd->code,
                                'display' => $fd->display
                            ]
                        ]
                    ],
                    'manipulated' => [
                        'reference' => $fd->manipulated
                    ]
                ];
            }
        }

        return $focalDevice;
    }
}
