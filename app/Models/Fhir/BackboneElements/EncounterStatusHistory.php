<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Valuesets;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Resources\Encounter;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class EncounterStatusHistory extends FhirModel
{
    use HasFactory;

    protected $table = 'encounter_status_history';

    public $timestamps = false;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function period(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }

    public const STATUS = [
        'binding' => [
            'valueset' => Valuesets::EncounterStatus
        ]
    ];
}
