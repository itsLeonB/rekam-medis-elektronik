<?php

namespace App\Models\Fhir\Resources;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Models\Fhir\BackboneElements\AllergyIntoleranceReaction;
use App\Models\Fhir\Datatypes\Age;
use App\Models\Fhir\Datatypes\Annotation;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Range;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resource;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class AllergyIntolerance extends FhirModel
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($allergyIntolerance) {
            $existingIdentifier = $allergyIntolerance->identifier()
                ->where('system', config('app.identifier_systems.allergyintolerance'))
                ->first();

            if (!$existingIdentifier) {
                $identifier = new Identifier();
                $identifier->system = config('app.identifier_systems.allergyintolerance');
                $identifier->use = 'official';
                $identifier->value = Str::uuid();
                $allergyIntolerance->identifier()->save($identifier);
            }
        });
    }

    protected $table = 'allergy_intolerance';

    protected $casts = [
        'category' => 'array',
        'onset_date_time' => 'datetime',
        'recorded_date' => 'datetime',
        'last_occurence' => 'datetime'
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

    public function clinicalStatus(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'clinicalStatus');
    }

    public function verificationStatus(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'verificationStatus');
    }

    public function code(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'code');
    }

    public function patient(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'patient');
    }

    public function encounter(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'encounter');
    }

    public function onsetAge(): MorphOne
    {
        return $this->morphOne(Age::class, 'ageable');
    }

    public function onsetPeriod(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }

    public function onsetRange(): MorphOne
    {
        return $this->morphOne(Range::class, 'rangeable');
    }

    public function recorder(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'recorder');
    }

    public function asserter(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'asserter');
    }

    public function note(): MorphMany
    {
        return $this->morphMany(Annotation::class, 'annotable');
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
