<?php

namespace App\Models\Fhir\Datatypes;

use App\Fhir\Codesystems;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Narrative extends FhirModel
{
    use HasFactory;

    public function narrateable()
    {
        return $this->morphTo('narrateable');
    }

    public const STATUS = [
        'binding' => [
            'valueset' => Codesystems::NarrativeStatus
        ],
    ];
}
