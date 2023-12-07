<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AllergyIntoleranceReaction extends Model
{
    protected $table = 'allergy_intolerance_reaction';
    protected $casts = [
        'manifestation' => 'array',
        'onset' => 'datetime'
    ];
    public $timestamps = false;

    public function allergy(): BelongsTo
    {
        return $this->belongsTo(AllergyIntolerance::class, 'allergy_id');
    }

    public function note(): HasMany
    {
        return $this->hasMany(AllergyIntoleranceReactionNote::class, 'allergy_react_id');
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
