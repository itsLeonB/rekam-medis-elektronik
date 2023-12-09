<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClinicalImpression extends FhirModel
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($clinicalImpression) {
            $orgId = config('app.organization_id');

            $identifier = new ClinicalImpressionIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/clinicalimpression/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $clinicalImpression->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $clinicalImpression->identifier()->save($identifier);
        });
    }

    protected $table = 'clinical_impression';
    protected $casts = [
        'effective' => 'array',
        'date' => 'datetime',
        'problem' => 'array',
        'protocol' => 'array',
        'prognosis_codeable_concept' => 'array',
        'prognosis_reference' => 'array',
        'supporting_info' => 'array'
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

    public function investigation(): HasMany
    {
        return $this->hasMany(ClinicalImpressionInvestigation::class, 'impression_id');
    }

    public function finding(): HasMany
    {
        return $this->hasMany(ClinicalImpressionFinding::class, 'impression_id');
    }

    public function note(): HasMany
    {
        return $this->hasMany(ClinicalImpressionNote::class, 'impression_id');
    }

    public const STATUS = [
        'binding' => [
            'valueset' => Valuesets::ClinicalImpressionStatus
        ]
    ];

    public const STATUS_REASON_CODE = [
        'binding' => [
            'valueset' => Codesystems::ICD10
        ]
    ];

    public const EFFECTIVE = [
        'variableTypes' => ['effectiveDateTime', 'effectivePeriod']
    ];

    public const PROGNOSIS_CODEABLE_CONCEPT = [
        'binding' => [
            'valueset' => Valuesets::ClinicalImpressionPrognosis
        ]
    ];
}
