<?php

namespace App\Models\Fhir\Datatypes;

use App\Fhir\Valuesets;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Quantity extends FhirModel
{
    use HasFactory;

    protected $casts = ['value' => 'decimal:2'];

    public function quantifiable(): MorphTo
    {
        return $this->morphTo('quantifiable');
    }

    public const COMPARATOR = [
        'binding' => [
            'valueset' => Valuesets::Comparators
        ]
    ];
}
