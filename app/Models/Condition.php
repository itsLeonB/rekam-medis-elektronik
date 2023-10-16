<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Condition extends Model
{
    protected $table = 'condition';
    protected $casts = [
        'onset_datetime' => 'datetime',
        'abatement_datetime' => 'datetime',
        'recorded_date' => 'date'
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(ConditionIdentifier::class);
    }

    public function category(): HasMany
    {
        return $this->hasMany(ConditionCategory::class);
    }

    public function bodySite(): HasMany
    {
        return $this->hasMany(ConditionBodySite::class);
    }

    public function stage(): HasMany
    {
        return $this->hasMany(ConditionStage::class);
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(ConditionEvidence::class);
    }

    public function note(): HasMany
    {
        return $this->hasMany(ConditionNote::class);
    }
}
