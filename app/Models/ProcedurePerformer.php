<?php

namespace App\Models;

use App\Fhir\Valuesets;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcedurePerformer extends Model
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
