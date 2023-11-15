<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalImpressionPrognosis extends Model
{
    public const SYSTEM = 'http://snomed.info/sct';
    public const CODE = ['170968001', '65872000', '67334001', '170969009'];
    public const DISPLAY = ["170968001" => "Prognosis good", "65872000" => "Fair prognosis", "67334001" => "Guarded prognosis", "170969009" => "Prognosis bad"];

    protected $table = 'clinical_impression_prognosis';
    public $timestamps = false;

    public function clinicalImpression(): BelongsTo
    {
        return $this->belongsTo(ClinicalImpression::class, 'impression_id');
    }
}
