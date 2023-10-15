<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncounterHospitalizationSpecialArrangement extends Model
{
    protected $table = 'encounter_hospitalization_special_arrangement';
    public $timestamps = false;

    public function encounterHospitalization(): BelongsTo
    {
        return $this->belongsTo('encounter_hospitalization', 'id', 'encounter_hospitalization_id');
    }
}
