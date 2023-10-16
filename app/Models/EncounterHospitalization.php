<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EncounterHospitalization extends Model
{
    protected $table = 'encounter_hospitalization';
    public $timestamps = false;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class, 'id', 'encounter_id');
    }

    public function diet(): HasMany
    {
        return $this->hasMany(EncounterHospitalizationDiet::class, 'enc_hosp_id', 'id');
    }

    public function specialArrangement(): HasMany
    {
        return $this->hasMany(EncounterHospitalizationSpecialArrangement::class, 'enc_hosp_id', 'id');
    }
}
