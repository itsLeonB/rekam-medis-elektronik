<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImagingStudy extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($imagingStudy) {
            $orgId = config('app.organization_id');

            $identifier = new ImagingStudyIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/acsn/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $imagingStudy->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $imagingStudy->identifier()->save($identifier);
        });
    }

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
