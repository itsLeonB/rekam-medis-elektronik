<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Valuesets;
use App\Models\Fhir\Datatypes\Coding;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resources\Encounter;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class EncounterParticipant extends FhirModel
{
    protected $table = 'encounter_participant';

    public $timestamps = false;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function type(): MorphMany
    {
        return $this->morphMany(Coding::class, 'codeable');
    }

    public function period(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }

    public function individual(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable');
    }

    public const TYPE = [
        'binding' => [
            'valueset' => Valuesets::EncounterParticipantType
        ]
    ];
}
