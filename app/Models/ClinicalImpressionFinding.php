<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalImpressionFinding extends Model
{
    protected $table = 'clinical_impression_finding';
    public $timestamps = false;

    public function clinicalImpression(): BelongsTo
    {
        return $this->belongsTo(ClinicalImpression::class, 'impression_id');
    }
}
