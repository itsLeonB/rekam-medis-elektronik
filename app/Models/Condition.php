<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Condition extends Model
{
    // Enum values
    public const CLINICAL_STATUS = ['active', 'recurrence', 'relapse', 'inactive', 'remission', 'resolved'];
    public const VERIFICATION_STATUS = ['unconfirmed', 'provisional', 'differential', 'confirmed', 'refuted', 'entered-in-error'];
    public const SEVERITY = ['24484000', '6736007', '255604002'];
    public const SEVERITY_DISPLAY = [
        '24484000' => 'Severe',
        '6736007' => 'Moderate',
        '255604002' => 'Mild'
    ];

    // Variable array
    public const ONSET = ['onsetDateTime', 'onsetAge', 'onsetPeriod', 'onsetRange', 'onsetString'];
    public const ABATEMENT = ['abatementDateTime', 'abatementAge', 'abatementPeriod', 'abatementRange', 'abatementString'];

    protected $table = 'condition';
    protected $casts = [
        'onset' => 'json',
        'abatement' => 'json',
        'recorded_date' => 'date'
    ];
    protected $guarded = ['id'];
    public $timestamps = false;

    // Relationships
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
