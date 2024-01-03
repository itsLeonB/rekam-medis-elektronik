<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Valuesets;
use App\Models\Fhir\Datatypes\Coding;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Resources\Encounter;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class EncounterClassHistory extends FhirModel
{
    protected $table = 'encounter_class_history';

    public $timestamps = false;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function class(): MorphOne
    {
        return $this->morphOne(Coding::class, 'codeable');
    }

    public function period(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }

    public const ENC_CLASS = [
        'binding' => [
            'valueset' => Valuesets::EncounterClass
        ]
    ];
}
