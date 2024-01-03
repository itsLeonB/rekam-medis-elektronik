<?php

namespace App\Models\Fhir\Datatypes;

use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CodeableConcept extends FhirModel
{
    use HasFactory;

    public function codeable(): MorphTo
    {
        return $this->morphTo('codeable');
    }

    public function coding(): MorphMany
    {
        return $this->morphMany(Coding::class, 'codeable');
    }
}
