<?php

namespace App\Models\Fhir\Resources;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Models\Fhir\BackboneElements\ClinicalImpressionFinding;
use App\Models\Fhir\BackboneElements\ClinicalImpressionInvestigation;
use App\Models\Fhir\Datatypes\Annotation;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resource;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class ClinicalImpression extends FhirModel
{
    use HasFactory;

    protected $table = 'clinical_impression';

    protected $casts = [
        'effective_date_time' => 'datetime',
        'date' => 'datetime',
        'protocol' => 'array'
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

    public function statusReason(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'statusReason');
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

    public function encounter(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'encounter');
    }

    public function effectivePeriod(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }

    public function assessor(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'assessor');
    }

    public function previous(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'previous');
    }

    public function problem(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'problem');
    }

    public function investigation(): HasMany
    {
        return $this->hasMany(ClinicalImpressionInvestigation::class, 'impression_id');
    }

    public function finding(): HasMany
    {
        return $this->hasMany(ClinicalImpressionFinding::class, 'impression_id');
    }

    public function prognosisCodeableConcept(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'prognosisCodeableConcept');
    }

    public function prognosisReference(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'prognosisReference');
    }

    public function supportingInfo(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'supportingInfo');
    }

    public function note(): MorphMany
    {
        return $this->morphMany(Annotation::class, 'annotable');
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
