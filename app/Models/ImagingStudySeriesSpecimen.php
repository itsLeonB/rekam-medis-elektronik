<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagingStudySeriesSpecimen extends Model
{
    protected $table = 'img_study_series_specimen';
    public $timestamps = false;

    public function series(): BelongsTo
    {
        return $this->belongsTo(ImagingStudySeries::class, 'img_series_id');
    }
}
