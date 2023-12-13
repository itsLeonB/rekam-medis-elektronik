<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Config;

class AllergyIntolerance extends FhirModel
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($allergyIntolerance) {
            $orgId = Config::get('app.organization_id');

            $identifier = new AllergyIntoleranceIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/allergy/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $allergyIntolerance->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $allergyIntolerance->identifier()->save($identifier);
        });
    }

    protected $table = 'allergy_intolerance';
    protected $casts = [
        'category' => 'array',
        'onset' => 'array',
        'recorded_date' => 'datetime',
        'last_occurence' => 'datetime'
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(AllergyIntoleranceIdentifier::class, 'allergy_id');
    }

    public function note(): HasMany
    {
        return $this->hasMany(AllergyIntoleranceNote::class, 'allergy_id');
    }

    public function reaction(): HasMany
    {
        return $this->hasMany(AllergyIntoleranceReaction::class, 'allergy_id');
    }


    public const CLINICAL_STATUS = [
        'binding' => [
            'valueset' => Codesystems::AllergyIntoleranceClinicalStatusCodes
        ]
    ];

    public const VERIFICATION_STATUS = [
        'binding' => [
            'valueset' => Codesystems::AllergyIntoleranceVerificationStatusCodes
        ]
    ];

    public const TYPE = [
        'binding' => [
            'valueset' => Codesystems::AllergyIntoleranceType
        ]
    ];

    public const CATEGORY = [
        'binding' => [
            'valueset' => Codesystems::AllergyIntoleranceCategory
        ]
    ];

    public const CRITICALITY = [
        'binding' => [
            'valueset' => Codesystems::AllergyIntoleranceCriticality
        ]
    ];

    public const CODE = [
        'binding' => [
            'valueset' => Valuesets::AllergyIntoleranceSubstanceProductConditionAndNegationCodes
        ]
    ];

    public const ONSET = [
        'variableTypes' => ['onsetDateTime', 'onsetAge', 'onsetPeriod', 'onsetRange', 'onsetString']
    ];
}
