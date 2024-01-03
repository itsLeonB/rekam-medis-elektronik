<?php

namespace App\Models\Fhir\Datatypes;

use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Range extends FhirModel
{
    use HasFactory;

    public function rangeable(): MorphTo
    {
        return $this->morphTo('rangeable');
    }

    public function low(): MorphOne
    {
        return $this->morphOne(SimpleQuantity::class, 'simple_quantifiable')
            ->where('attr_type', 'low');
    }

    public function high(): MorphOne
    {
        return $this->morphOne(SimpleQuantity::class, 'simple_quantifiable')
            ->where('attr_type', 'high');
    }
}
