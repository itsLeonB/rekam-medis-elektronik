<?php

namespace App\Models\Fhir;

use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncounterParticipant extends FhirModel
{
    protected $table = 'encounter_participant';
    protected $casts = ['type' => 'array'];
    public $timestamps = false;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }
}
