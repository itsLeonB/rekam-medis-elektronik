<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationIngredient extends FhirModel
{
    protected $table = 'medication_ingredient';
    protected $casts = [
        'is_active' => 'boolean',
        'strength_numerator_value' => 'decimal:2',
        'strength_denominator_value' => 'decimal:2'
    ];
    public $timestamps = false;

    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
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
