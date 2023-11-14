<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClinicalImpressionInvestigation extends Model
{
    public const SYSTEM = 'http://snomed.info/sct';
    public const CODE = ['271336007', '160237006'];
    public const DISPLAY = ["271336007" => "Examination / signs", "160237006" => "History/symptoms"];

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
