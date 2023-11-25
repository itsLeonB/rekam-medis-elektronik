<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagingStudyNote extends Model
{
    protected $table = 'imaging_study_note';
    protected $casts = [
        'author' => 'json',
        'time' => 'datetime'
    ];
    public $timestamps = false;

    public function imagingStudy(): BelongsTo
    {
        return $this->belongsTo(ImagingStudy::class, 'imaging_id');
    }
}
