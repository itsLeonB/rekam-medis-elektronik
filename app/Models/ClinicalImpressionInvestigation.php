<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClinicalImpressionInvestigation extends Model
{
    protected $table = 'clinical_impression_investigation';
    public $timestamps = false;

    public function clinicalImpression(): BelongsTo
    {
        return $this->belongsTo(ClinicalImpression::class, 'impression_id');
    }

    public function item(): HasMany
    {
        return $this->hasMany(ClinicalImpressionInvestigationItem::class, 'impress_investigate_id');
    }
}
