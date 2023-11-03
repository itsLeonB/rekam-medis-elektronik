<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImagingStudy extends Model
{
    protected $table = 'imaging_study';
    protected $casts = ['started' => 'datetime'];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(ImagingStudyIdentifier::class, 'imaging_id');
    }

    public function modality(): HasMany
    {
        return $this->hasMany(ImagingStudyModality::class, 'imaging_id');
    }

    public function basedOn(): HasMany
    {
        return $this->hasMany(ImagingStudyBasedOn::class, 'imaging_id');
    }

    public function interpreter(): HasMany
    {
        return $this->hasMany(ImagingStudyInterpreter::class, 'imaging_id');
    }

    public function procedure(): HasMany
    {
        return $this->hasMany(ImagingStudyProcedure::class, 'imaging_id');
    }

    public function reason(): HasMany
    {
        return $this->hasMany(ImagingStudyReason::class, 'imaging_id');
    }

    public function note(): HasMany
    {
        return $this->hasMany(ImagingStudyNote::class, 'imaging_id');
    }

    public function series(): HasMany
    {
        return $this->hasMany(ImagingStudySeries::class, 'imaging_id');
    }
}
