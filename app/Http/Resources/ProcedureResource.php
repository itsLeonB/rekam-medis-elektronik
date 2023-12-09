<?php

namespace App\Http\Resources;

use App\Models\Fhir\{
    Procedure,
    ProcedureFocalDevice,
    ProcedurePerformer
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($procedure): array
    {
        return $this->mergeArray(
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
                            'system' => $procedure->status_reason ? Procedure::STATUS_REASON['binding']['valueset']['system'] : null,
                            'code' => $procedure->status_reason,
                            'display' => $procedure->status_reason ? DB::table(Procedure::STATUS_REASON['binding']['valueset']['table'])
                                ->where('code', $procedure->status_reason)
                                ->value('display') ?? null : null
                        ]
                    ]
                ],
                'category' => [
                    'coding' => [
                        [
                            'system' => $procedure->category ? Procedure::CATEGORY['binding']['valueset']['system'] : null,
                            'code' => $procedure->category,
                            'display' =>  $procedure->category ? Procedure::CATEGORY['binding']['valueset']['display'][$procedure->category] ?? null : null
                        ]
                    ]
                ],
                'code' => [
                    'coding' => [
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
                'reasonCode' => $this->createReasonCodeArray($procedure->reason_code),
                'reasonReference' => $this->createReferenceArray($procedure->reason_reference),
                'bodySite' => $this->createBodySiteArray($procedure->body_site),
                'outcome' => [
                    'coding' => [
                        [
                            'system' => $procedure->outcome ? Procedure::OUTCOME['binding']['valueset']['system'] : null,
                            'code' => $procedure->outcome,
                            'display' => $procedure->outcome ? Procedure::OUTCOME['binding']['valueset']['display'][$procedure->outcome] ?? null : null
                        ]
                    ]
                ],
                'report' => $this->createReferenceArray($procedure->report),
                'complication' => $this->createComplicationArray($procedure->complication),
                'complicationDetail' => $this->createReferenceArray($procedure->complication),
                'followUp' => $this->createFollowUpArray($procedure->followUp),
                'note' => $this->createAnnotationArray($procedure->note),
                'focalDevice' => $this->createFocalDeviceArray($procedure->focalDevice),
                'usedReference' => $this->createReferenceArray($procedure->itemUsed),
                'usedCode' => $this->createUsedCodeArray($procedure->itemUsed)
            ],
            $procedure->performed
        );
    }


    private function createUsedCodeArray($usedCodes): array
    {
        $usedCode = [];

        if (!empty($usedCodes)) {
            foreach ($usedCodes as $uc) {
                $usedCode[] = [
                    'coding' => [
                        [
                            'system' => $uc ? Procedure::USED_CODE['binding']['valueset']['system'] : null,
                            'code' => $uc,
                            'display' => $uc ? $this->querySnomedCode($uc) ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $usedCode;
    }


    private function createFollowUpArray($followUps): array
    {
        $followUp = [];

        if (!empty($followUps)) {
            foreach ($followUps as $fu) {
                $followUp[] = [
                    'coding' => [
                        [
                            'system' => $fu ? Procedure::FOLLOW_UP['binding']['valueset']['system'] : null,
                            'code' => $fu,
                            'display' => $fu ? Procedure::FOLLOW_UP['binding']['valueset']['display'][$fu] ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $followUp;
    }


    private function createComplicationArray($complications): array
    {
        $complication = [];

        if (!empty($complications)) {
            foreach ($complications as $c) {
                $complication[] = [
                    'coding' => [
                        [
                            'system' => $c ? Procedure::COMPLICATION['binding']['valueset']['system'] : null,
                            'code' => $c,
                            'display' => $c ? DB::table(Procedure::COMPLICATION['binding']['valueset']['table'])
                                ->where('code', $c)
                                ->value('display_en') ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $complication;
    }


    private function createBodySiteArray($bodySites): array
    {
        $bodySite = [];

        if (!empty($bodySites)) {
            foreach ($bodySites as $bs) {
                $bodySite[] = [
                    'coding' => [
                        [
                            'system' => $bs ? Procedure::BODY_SITE['binding']['valueset']['system'] : null,
                            'code' => $bs,
                            'display' => $bs ? DB::table(Procedure::BODY_SITE['binding']['valueset']['table'])
                                ->where('code', $bs)
                                ->value('display') ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $bodySite;
    }


    private function createReasonCodeArray($reasonCodes): array
    {
        $reasonCode = [];

        if (!empty($reasonCodes)) {
            foreach ($reasonCodes as $rc) {
                $reasonCode[] = [
                    'coding' => [
                        [
                            'system' => $rc ? Procedure::REASON_CODE['binding']['valueset']['system'] : null,
                            'code' => $rc,
                            'display' => $rc ? DB::table(Procedure::REASON_CODE['binding']['valueset']['table'])
                                ->where('code', $rc)
                                ->value('display_en') ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $reasonCode;
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
                                'system' => $p->function ? ProcedurePerformer::FUNCTION['binding']['valueset']['system'] : null,
                                'code' => $p->function,
                                'display' => $p->function ? DB::table(ProcedurePerformer::FUNCTION['binding']['valueset']['table'])
                                    ->where('code', $p->function)
                                    ->value('display') ?? null : null
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
                                'system' => $fd->action ? ProcedureFocalDevice::ACTION['binding']['valueset']['system'] : null,
                                'code' => $fd->action,
                                'display' => $fd->action ? DB::table(ProcedureFocalDevice::ACTION['binding']['valueset']['table'])
                                    ->where('code', $fd->action)
                                    ->value('display') ?? null : null
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
