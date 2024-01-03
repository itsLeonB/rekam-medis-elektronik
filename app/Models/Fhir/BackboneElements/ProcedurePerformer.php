<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Valuesets;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resources\Procedure;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ProcedurePerformer extends FhirModel
{
    protected $table = 'procedure_performer';
    public $timestamps = false;

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }

    public function function(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable');
    }

    public function actor(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'actor');
    }

    public function onBehalfOf(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'onBehalfOf');
    }

    public const FUNCTION = [
        'binding' => [
            'valueset' => Valuesets::ProcedurePerformerRoleCodes
        ]
    ];
}
