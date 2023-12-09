<?php

namespace App\Models\Fhir;


use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncounterStatusHistory extends FhirModel
{
    protected $table = 'encounter_status_history';
    protected $casts = [
        'period_start' => 'datetime',
        'period_end' => 'datetime'
    ];
    protected $guarded = ['id'];
    public $timestamps = false;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }
}
