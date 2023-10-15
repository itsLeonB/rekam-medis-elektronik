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
        return $this->belongsTo('resource', 'res_id', 'res_id');
    }

    public function identifier(): HasMany
    {
        return $this->hasMany('encounter_identifier', 'encounter_id', 'id');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany('encounter_status_history', 'encounter_id', 'id');
    }

    public function classHistory(): HasMany
    {
        return $this->hasMany('encounter_class_history', 'encounter_id', 'id');
    }

    public function participant(): HasMany
    {
        return $this->hasMany('encounter_participant', 'encounter_id', 'id');
    }

    public function reason(): HasMany
    {
        return $this->hasMany('encounter_reason', 'encounter_id', 'id');
    }

    public function diagnosis(): HasMany
    {
        return $this->hasMany('encounter_diagnosis', 'encounter_id', 'id');
    }

    public function hospitalization(): HasMany
    {
        return $this->hasMany('encounter_hospitalization', 'encounter_id', 'id');
    }
}
