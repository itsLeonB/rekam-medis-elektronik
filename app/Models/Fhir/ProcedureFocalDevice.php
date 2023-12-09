<?php

namespace App\Models\Fhir;

use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcedureFocalDevice extends FhirModel
{
    protected $table = 'procedure_focal_device';
    public $timestamps = false;

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }

    public const ACTION = [
        'binding' => [
            'valueset' => Valuesets::ProcedureDeviceActionCodes
        ]
    ];
}
