<?php

namespace App\Http\Resources;

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
        $procedure = $this->resource->procedure ? $this->resource->procedure->first() : null;

        $data = merge_array(
            [
                'resourceType' => 'Procedure',
                'id' => $this->satusehat_id,
                'identifier' => $this->createIdentifierArray($procedure->identifier),
                'basedOn' => $this->createReferenceArrayArray($procedure->basedOn),
                'partOf' => $this->createReferenceArrayArray($procedure->partOf),
                'status' => $procedure->status,
                'statusReason' => [
                    'coding' => [
                        [
                            'system' => ValueSetProcedureReasonCode::SYSTEM,
                            'code' => $procedure->status_reason,
                            'display' => ValueSetProcedureReasonCode::where('code', $procedure->status_reason)->first()->display ?? null
                        ]
                    ]
                ],
                'category' => [
                    'coding' => [
                        [

                        ]
                    ]
                ],
            ]
        );

        $data = removeEmptyValues($data);

        return $data;
    }
}
