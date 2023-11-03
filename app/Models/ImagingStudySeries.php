<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class ImagingStudySeries extends Model
{
    protected $table = 'imaging_study_series';
    protected $casts = ['started' => 'datetime'];
    public $timestamps = false;

    public function imagingStudy(): BelongsTo
    {
        return $this->belongsTo(ImagingStudy::class, 'imaging_id');
    }

    public function specimen(): HasMany
    {
        return $this->hasMany(ImagingStudySeriesSpecimen::class, 'img_series_id');
    }

    public function performer(): HasMany
    {
        return $this->hasMany(ImagingStudySeriesPerformer::class, 'img_series_id');
    }

    public function instance(): HasMany
    {
        return $this->hasMany(ImagingStudySeriesInstance::class, 'img_series_id');
    }
}
