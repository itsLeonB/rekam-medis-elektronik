<?php

namespace App\Models\Fhir;

use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcedurePerformer extends FhirModel
{
    protected $table = 'procedure_performer';
    public $timestamps = false;

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }

    public const FUNCTION = [
        'binding' => [
            'valueset' => Valuesets::ProcedurePerformerRoleCodes
        ]
    ];
}
