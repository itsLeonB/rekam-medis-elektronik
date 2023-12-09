<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Observation extends FhirModel
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($observation) {
            $orgId = config('app.organization_id');

            $identifier = new ObservationIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/observation/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $observation->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $observation->identifier()->save($identifier);
        });
    }

    protected $table = 'observation';
    protected $casts = [
        'based_on' => 'array',
        'part_of' => 'array',
        'category' => 'array',
        'focus' => 'array',
        'effective' => 'array',
        'issued' => 'datetime',
        'performer' => 'array',
        'value' => 'array',
        'interpretation' => 'array',
        'has_member' => 'array',
        'derived_from' => 'array',
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(ObservationIdentifier::class);
    }

    public function note(): HasMany
    {
        return $this->hasMany(ObservationNote::class);
    }

    public function referenceRange(): HasMany
    {
        return $this->hasMany(ObservationReferenceRange::class);
    }

    public function component(): HasMany
    {
        return $this->hasMany(ObservationComponent::class);
    }

    public const STATUS = [
        'binding' => [
            'valueset' => Codesystems::ObservationStatus
        ]
    ];

    public const CATEGORY = [
        'binding' => [
            'valueset' => Codesystems::ObservationCategoryCodes
        ]
    ];

    public const CODE = [
        'binding' => [
            'valueset' => Codesystems::LOINC
        ]
    ];

    public const EFFECTIVE = [
        'variableTypes' => ['effectiveDateTime', 'effectivePeriod', 'effectiveTiming', 'effectiveInstant']
    ];

    public const VALUE = [
        'variableTypes' => ['valueQuantity', 'valueCodeableConcept', 'valueString', 'valueBoolean', 'valueInteger', 'valueRange', 'valueRatio', 'valueSampledData', 'valueTime', 'valueDateTime', 'valuePeriod']
    ];

    public const VALUE_QUANTITY = [
        'binding' => [
            'valueset' => Codesystems::UCUM
        ]
    ];

    public const VALUE_CODEABLE_CONCEPT = [
        'binding' => [
            'valueset' => [Codesystems::SNOMEDCT, Codesystems::LOINC]
        ]
    ];

    public const DATA_ABSENT_REASON = [
        'binding' => [
            'valueset' => Codesystems::DataAbsentReason
        ]
    ];

    public const INTERPRETATION = [
        'binding' => [
            'valueset' => Valuesets::ObservationInterpretationCodes
        ]
    ];

    public const BODY_SITE = [
        'binding' => [
            'valueset' => Valuesets::SNOMEDCTBodySite
        ]
    ];

    public const METHOD = [
        'binding' => [
            'valueset' => Valuesets::ObservationMethods
        ]
    ];
}
