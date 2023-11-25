<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagingStudyReasonCode extends Model
{
    protected $table = 'imaging_study_reason_code';
    public $timestamps = false;

    public function imagingStudy(): BelongsTo
    {
        return $this->belongsTo(ImagingStudy::class, 'imaging_id');
    }
}
