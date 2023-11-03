<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagingStudySeriesPerformer extends Model
{
    protected $table = 'img_study_series_performer';
    public $timestamps = false;

    public function series(): BelongsTo
    {
        return $this->belongsTo(ImagingStudySeries::class, 'img_series_id');
    }
}
