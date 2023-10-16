<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Encounter extends Model
{
    protected $table = 'encounter';
    protected $casts = [
        'period_start' => 'datetime',
        'period_end' => 'datetime'
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'res_id', 'res_id');
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(EncounterIdentifier::class, 'encounter_id', 'id');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(EncounterStatusHistory::class, 'encounter_id', 'id');
    }

    public function classHistory(): HasMany
    {
        return $this->hasMany(EncounterClassHistory::class, 'encounter_id', 'id');
    }

    public function participant(): HasMany
    {
        return $this->hasMany(EncounterParticipant::class, 'encounter_id', 'id');
    }

    public function reason(): HasMany
    {
        return $this->hasMany(EncounterReason::class, 'encounter_id', 'id');
    }

    public function diagnosis(): HasMany
    {
        return $this->hasMany(EncounterDiagnosis::class, 'encounter_id', 'id');
    }

    public function hospitalization(): HasMany
    {
        return $this->hasMany(EncounterHospitalization::class, 'encounter_id', 'id');
    }
}
