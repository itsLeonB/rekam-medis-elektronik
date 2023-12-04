<?php

namespace App\Models;

use App\Fhir\Valuesets;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcedureFocalDevice extends Model
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
