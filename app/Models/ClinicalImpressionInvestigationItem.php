<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalImpressionInvestigationItem extends Model
{
    protected $table = 'clinic_impress_investigate_item';
    public $timestamps = false;

    public function investigation(): BelongsTo
    {
        return $this->belongsTo(ClinicalImpressionInvestigation::class, 'impress_investigate_id');
    }
}
