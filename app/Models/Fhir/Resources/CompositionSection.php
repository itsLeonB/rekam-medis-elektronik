<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;

use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompositionSection extends FhirModel
{
    protected $table = 'composition_section';
    protected $casts = [
        'author' => 'array',
        'entry' => 'array',
        'section' => 'array',
    ];
    public $timestamps = false;

    public function composition(): BelongsTo
    {
        return $this->belongsTo(Composition::class);
    }

    public const CODE = [
        'binding' => [
            'valueset' => Valuesets::DocumentSectionCodes
        ],
    ];

    public const TEXT_STATUS = [
        'binding' => [
            'valueset' => Codesystems::NarrativeStatus
        ],
    ];

    public const MODE = [
        'binding' => [
            'valueset' => Codesystems::ListMode
        ],
    ];

    public const ORDERED_BY = [
        'binding' => [
            'valueset' => Codesystems::ListOrderCodes
        ],
    ];

    public const EMPTY_REASON = [
        'binding' => [
            'valueset' => Codesystems::ListEmptyReasons
        ],
    ];
}
