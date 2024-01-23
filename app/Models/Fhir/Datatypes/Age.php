<?php

namespace App\Models\Fhir\Datatypes;

use App\Fhir\Valuesets;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Age extends FhirModel
{
    use HasFactory;

    protected $casts = ['value' => 'float'];

    public function ageable(): MorphTo
    {
        return $this->morphTo('ageable');
    }

    public const COMPARATOR = [
        'binding' => [
            'valueset' => Valuesets::Comparators
        ]
    ];
}
