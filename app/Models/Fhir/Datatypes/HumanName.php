<?php

namespace App\Models\Fhir\Datatypes;

use App\Fhir\Codesystems;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class HumanName extends FhirModel
{
    use HasFactory;

    protected $casts = [
        'given' => 'array',
        'prefix' => 'array',
        'suffix' => 'array'
    ];

    public function humanNameable(): MorphTo
    {
        return $this->morphTo('human_nameable');
    }

    public function period(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }

    public const USE = [
        'binding' => [
            'valueset' => Codesystems::NameUse
        ]
    ];
}
