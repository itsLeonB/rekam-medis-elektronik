<?php

namespace App\Models\Fhir;


use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationIdentifier extends FhirModel
{
    protected $table = 'observation_identifier';
    public $timestamps = false;

    public function observation(): BelongsTo
    {
        return $this->belongsTo(Observation::class);
    }
}
