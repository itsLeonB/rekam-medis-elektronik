<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Models\Fhir\Datatypes\Annotation;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Resources\AllergyIntolerance;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class AllergyIntoleranceReaction extends FhirModel
{
    use HasFactory;

    protected $table = 'allergy_intolerance_reaction';

    protected $casts = ['onset' => 'datetime'];

    public $timestamps = false;

    public function allergyIntolerance(): BelongsTo
    {
        return $this->belongsTo(AllergyIntolerance::class, 'allergy_id');
    }

    public function substance(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'substance');
    }

    public function manifestation(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'manifestation');
    }

    public function exposureRoute(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'exposureRoute');
    }

    public function note(): MorphMany
    {
        return $this->morphMany(Annotation::class, 'annotable');
    }

    public const SUBSTANCE = [
        'binding' => [
            'valueset' => Valuesets::AllergyIntoleranceSubstanceProductConditionAndNegationCodes
        ],
    ];

    public const MANIFESTATION = [
        'binding' => [
            'valueset' => Valuesets::SNOMEDCTClinicalFindings
        ],
    ];

    public const SEVERITY = [
        'binding' => [
            'valueset' => Codesystems::AllergyIntoleranceSeverity
        ],
    ];

    public const EXPOSURE_ROUTE = [
        'binding' => [
            'valueset' => Valuesets::SNOMEDCTRouteCodes
        ],
    ];
}
