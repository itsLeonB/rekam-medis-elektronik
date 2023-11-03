<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagingStudySeriesInstance extends Model
{
    protected $table = 'img_study_series_instance';
    public $timestamps = false;

    public function series(): BelongsTo
    {
        return $this->belongsTo(ImagingStudySeries::class, 'img_series_id');
    }
}
