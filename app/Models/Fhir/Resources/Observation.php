<?php

namespace App\Models\Fhir\Resources;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Models\Fhir\BackboneElements\ObservationComponent;
use App\Models\Fhir\BackboneElements\ObservationReferenceRange;
use App\Models\Fhir\Datatypes\Annotation;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Quantity;
use App\Models\Fhir\Datatypes\Range;
use App\Models\Fhir\Datatypes\Ratio;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Datatypes\SampledData;
use App\Models\Fhir\Datatypes\Timing;
use App\Models\Fhir\Resource;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class Observation extends FhirModel
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($observation) {
            $identifier = new Identifier();
            $identifier->system = config('app.identifier_systems.observation');
            $identifier->use = 'official';
            $identifier->value = Str::uuid();
            $observation->identifier()->save($identifier);
        });
    }

    protected $table = 'observation';

    protected $casts = [
        'effective_date_time' => 'datetime',
        'effective_instant' => 'datetime',
        'issued' => 'datetime',
        'value_boolean' => 'boolean',
        'value_time' => 'datetime',
        'value_date_time' => 'datetime'
    ];

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): MorphMany
    {
        return $this->morphMany(Identifier::class, 'identifiable');
    }

    public function basedOn(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'basedOn');
    }

    public function partOf(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'partOf');
    }

    public function category(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'category');
    }

    public function code(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'code');
    }

    public function subject(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'subject');
    }

    public function focus(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'focus');
    }

    public function encounter(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'encounter');
    }

    public function effectivePeriod(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable')
            ->where('attr_type', 'effectivePeriod');
    }

    public function effectiveTiming(): MorphOne
    {
        return $this->morphOne(Timing::class, 'timeable')
            ->where('attr_type', 'effectiveTiming');
    }

    public function performer(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'performer');
    }

    public function valueQuantity(): MorphOne
    {
        return $this->morphOne(Quantity::class, 'quantifiable')
            ->where('attr_type', 'valueQuantity');
    }

    public function valueCodeableConcept(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'valueCodeableConcept');
    }

    public function valueRange(): MorphOne
    {
        return $this->morphOne(Range::class, 'rangeable')
            ->where('attr_type', 'valueRange');
    }

    public function valueRatio(): MorphOne
    {
        return $this->morphOne(Ratio::class, 'rateable')
            ->where('attr_type', 'valueRatio');
    }

    public function valueSampledData(): MorphOne
    {
        return $this->morphOne(SampledData::class, 'sampleable')
            ->where('attr_type', 'valueSampledData');
    }

    public function valuePeriod(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable')
            ->where('attr_type', 'valuePeriod');
    }

    public function dataAbsentReason(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'dataAbsentReason');
    }

    public function interpretation(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'interpretation');
    }

    public function note(): MorphMany
    {
        return $this->morphMany(Annotation::class, 'annotable');
    }

    public function bodySite(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'bodySite');
    }

    public function method(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'method');
    }

    public function specimen(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'specimen');
    }

    public function device(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'device');
    }

    public function referenceRange(): HasMany
    {
        return $this->hasMany(ObservationReferenceRange::class);
    }

    public function hasMember(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'hasMember');
    }

    public function derivedFrom(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'derivedFrom');
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
            'valueset' => Valuesets::ObservationCode
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
