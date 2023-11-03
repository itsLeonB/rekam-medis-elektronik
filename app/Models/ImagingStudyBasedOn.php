<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagingStudyBasedOn extends Model
{
    protected $table = 'imaging_study_based_on';
    public $timestamps = false;

    public function imagingStudy(): BelongsTo
    {
        return $this->belongsTo(ImagingStudy::class, 'imaging_id');
    }
}
