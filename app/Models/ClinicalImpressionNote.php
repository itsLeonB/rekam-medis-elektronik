<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalImpressionNote extends Model
{
    protected $table = 'clinical_impression_note';
    protected $casts = [
        'author' => 'json',
        'time' => 'datetime'
    ];
    public $timestamps = false;

    public function clinicalImpression(): BelongsTo
    {
        return $this->belongsTo(ClinicalImpression::class, 'impression_id');
    }
}
