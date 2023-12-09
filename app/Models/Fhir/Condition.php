<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Condition extends FhirModel
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($condition) {
            $orgId = config('app.organization_id');

            $identifier = new ConditionIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/condition/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $condition->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $condition->identifier()->save($identifier);
        });
    }

    public const CLINICAL_STATUS = [
        'binding' => [
            'valueset' => Codesystems::ConditionClinicalStatusCodes
        ]
    ];

    public const VERIFICATION_STATUS = [
        'binding' => [
            'valueset' => Codesystems::ConditionVerificationStatus
        ]
    ];

    public const CATEGORY = [
        'binding' => [
            'valueset' => Codesystems::ConditionCategoryCodes
        ]
    ];

    public const SEVERITY = [
        'binding' => [
            'valueset' => Valuesets::ConditionDiagnosisSeverity
        ]
    ];

    public const CODE = [
        'binding' => [
            'valueset' => Valuesets::ConditionProblemDiagnosisCodes
        ]
    ];

    public const BODY_SITE = [
        'binding' => [
            'valueset' => Valuesets::SNOMEDCTBodySite
        ]
    ];

    // Variable array
    public const ONSET = ['onsetDateTime', 'onsetAge', 'onsetPeriod', 'onsetRange', 'onsetString'];
    public const ABATEMENT = ['abatementDateTime', 'abatementAge', 'abatementPeriod', 'abatementRange', 'abatementString'];

    protected $table = 'condition';
    protected $casts = [
        'category' => 'array',
        'body_site' => 'array',
        'onset' => 'array',
        'abatement' => 'array',
        'recorded_date' => 'datetime'
    ];
    public $timestamps = false;

    // Relationships
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(ConditionIdentifier::class);
    }

    public function stage(): HasMany
    {
        return $this->hasMany(ConditionStage::class);
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(ConditionEvidence::class);
    }

    public function note(): HasMany
    {
        return $this->hasMany(ConditionNote::class);
    }
}
