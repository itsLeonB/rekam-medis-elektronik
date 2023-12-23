<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Ratio;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resources\Medication;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class MedicationIngredient extends FhirModel
{
    protected $table = 'medication_ingredient';

    protected $casts = ['is_active' => 'boolean'];

    public $timestamps = false;

    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }

    public function itemCodeableConcept(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable');
    }

    public function itemReference(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable');
    }

    public function strength(): MorphOne
    {
        return $this->morphOne(Ratio::class, 'rateable');
    }

    public const ITEM = [
        'binding' => [
            'valueset' => Codesystems::KFA
        ]
    ];

    public const STRENGTH_NUMERATOR = [
        'binding' => [
            'valueset' => Codesystems::UCUM
        ]
    ];

    public const STRENGTH_DENOMINATOR = [
        'binding' => [
            'valueset' => [Codesystems::UCUM, Valuesets::MedicationIngredientStrengthDenominator]
        ]
    ];
}
