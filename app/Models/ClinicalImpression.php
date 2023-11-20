<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClinicalImpression extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($clinicalImpression) {
            $orgId = config('organization_id');

            $identifier = new ClinicalImpressionIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/clinicalimpression/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $clinicalImpression->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $clinicalImpression->identifier()->save($identifier);
        });
    }

    public const STATUS_SYSTEM = 'http://hl7.org/fhir/eventstatus';
    public const STATUS_CODE = ['in-progress', 'completed', 'entered-in-error'];
    public const STATUS_DISPLAY = ["in-progress" => "Proses asesmen sedang berlangsung", "completed" => "Proses asesmen sudah selesai atau final", "entered-in-error" => "Kesalahan dalam input data"];

    protected $table = 'clinical_impression';
    protected $casts = [
        'effective' => 'array',
        'date' => 'datetime'
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(ClinicalImpressionIdentifier::class, 'impression_id');
    }

    public function problem(): HasMany
    {
        return $this->hasMany(ClinicalImpressionProblem::class, 'impression_id');
    }

    public function investigation(): HasMany
    {
        return $this->hasMany(ClinicalImpressionInvestigation::class, 'impression_id');
    }

    public function protocol(): HasMany
    {
        return $this->hasMany(ClinicalImpressionProtocol::class, 'impression_id');
    }

    public function finding(): HasMany
    {
        return $this->hasMany(ClinicalImpressionFinding::class, 'impression_id');
    }

    public function prognosis(): HasMany
    {
        return $this->hasMany(ClinicalImpressionPrognosis::class, 'impression_id');
    }

    public function supportingInfo(): HasMany
    {
        return $this->hasMany(ClinicalImpressionSupportingInfo::class, 'impression_id');
    }

    public function note(): HasMany
    {
        return $this->hasMany(ClinicalImpressionNote::class, 'impression_id');
    }
}
