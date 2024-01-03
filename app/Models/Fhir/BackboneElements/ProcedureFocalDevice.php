<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Valuesets;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resources\Procedure;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ProcedureFocalDevice extends FhirModel
{
    protected $table = 'procedure_focal_device';
    public $timestamps = false;

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }

    public function action(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable');
    }

    public function manipulated(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable');
    }

    public const ACTION = [
        'binding' => [
            'valueset' => Valuesets::ProcedureDeviceActionCodes
        ]
    ];
}
