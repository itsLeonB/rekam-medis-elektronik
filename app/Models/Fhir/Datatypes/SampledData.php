<?php

namespace App\Models\Fhir\Datatypes;

use App\Models\Fhir\Datatypes\SimpleQuantity;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SampledData extends FhirModel
{
    use HasFactory;

    protected $casts = [
        'period' => 'decimal:2',
        'factor' => 'decimal:2',
        'lower_limit' => 'decimal:2',
        'upper_limit' => 'decimal:2',
    ];

    public function sampleable(): MorphTo
    {
        return $this->morphTo('sampleable');
    }

    public function origin(): MorphOne
    {
        return $this->morphOne(SimpleQuantity::class, 'simple_quantifiable');
    }
}
