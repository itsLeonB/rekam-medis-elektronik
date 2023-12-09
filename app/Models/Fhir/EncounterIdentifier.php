<?php

namespace App\Models\Fhir;


use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncounterIdentifier extends FhirModel
{
    protected $table = 'encounter_identifier';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }
}
