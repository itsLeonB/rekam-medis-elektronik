<?php

namespace App\Models\Fhir\Resources;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Models\Fhir\BackboneElements\ConditionEvidence;
use App\Models\Fhir\BackboneElements\ConditionStage;
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
use Illuminate\Support\Str;

class Condition extends FhirModel
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($condition) {
            $identifier = new Identifier();
            $identifier->system = config('app.identifier_systems.condition');
            $identifier->use = 'official';
            $identifier->value = Str::uuid();
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
        'onset_date_time' => 'datetime',
        'abatement_date_time' => 'datetime',
        'recorded_date' => 'datetime'
    ];
    public $timestamps = false;

    // Relationships
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

    public function category(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'category');
    }

    public function severity(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'severity');
    }

    public function code(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'code');
    }

    public function bodySite(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'bodySite');
    }

    public function subject(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'subject');
    }

    public function encounter(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'encounter');
    }

    public function onsetAge(): MorphOne
    {
        return $this->morphOne(Age::class, 'ageable')
            ->where('attr_type', 'onsetAge');
    }

    public function onsetPeriod(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable')
            ->where('attr_type', 'onsetPeriod');
    }

    public function onsetRange(): MorphOne
    {
        return $this->morphOne(Range::class, 'rangeable')
            ->where('attr_type', 'onsetRange');
    }

    public function abatementAge(): MorphOne
    {
        return $this->morphOne(Age::class, 'ageable')
            ->where('attr_type', 'abatementAge');
    }

    public function abatementPeriod(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable')
            ->where('attr_type', 'abatementPeriod');
    }

    public function abatementRange(): MorphOne
    {
        return $this->morphOne(Range::class, 'rangeable')
            ->where('attr_type', 'abatementRange');
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

    public function stage(): HasMany
    {
        return $this->hasMany(ConditionStage::class);
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(ConditionEvidence::class);
    }

    public function note(): MorphMany
    {
        return $this->morphMany(Annotation::class, 'annotable');
    }
}
