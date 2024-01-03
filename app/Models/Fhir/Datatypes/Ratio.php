<?php

namespace App\Models\Fhir\Datatypes;

use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Ratio extends FhirModel
{
    use HasFactory;

    public function rateable(): MorphTo
    {
        return $this->morphTo('rateable');
    }

    public function numerator(): MorphOne
    {
        return $this->morphOne(Quantity::class, 'quantifiable');
    }

    public function denominator(): MorphOne
    {
        return $this->morphOne(Quantity::class, 'quantifiable');
    }
}
