<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClinicalImpression extends Model
{
    protected $table = 'clinical_impression';
    protected $casts = [
        'effective' => 'json',
        'date' => 'datetime'
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(ClinicalImpressionIdentifier::class, 'impression_id');
    }

    public function investigation(): HasMany
    {
        return $this->hasMany(ClinicalImpressionInvestigation::class, 'impression_id');
    }

    public function protocol(): HasMany
    {
        return $this->hasMany(ClinicalImpressionProtocol::class, 'impression_id');
    }

    public function finding(): HasMany
    {
        return $this->hasMany(ClinicalImpressionFinding::class, 'impression_id');
    }

    public function prognosis(): HasMany
    {
        return $this->hasMany(ClinicalImpressionPrognosis::class, 'impression_id');
    }

    public function supportingInfo(): HasMany
    {
        return $this->hasMany(ClinicalImpressionSupportingInfo::class, 'impression_id');
    }

    public function note(): HasMany
    {
        return $this->hasMany(ClinicalImpressionNote::class, 'impression_id');
    }
}
