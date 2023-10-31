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
    protected $guarded = ['id'];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(EncounterIdentifier::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(EncounterStatusHistory::class);
    }

    public function classHistory(): HasMany
    {
        return $this->hasMany(EncounterClassHistory::class);
    }

    public function participant(): HasMany
    {
        return $this->hasMany(EncounterParticipant::class);
    }

    public function reason(): HasMany
    {
        return $this->hasMany(EncounterReason::class);
    }

    public function diagnosis(): HasMany
    {
        return $this->hasMany(EncounterDiagnosis::class);
    }

    public function hospitalization(): HasMany
    {
        return $this->hasMany(EncounterHospitalization::class);
    }
}
