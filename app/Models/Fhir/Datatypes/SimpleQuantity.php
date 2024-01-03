<?php

namespace App\Models\Fhir\Datatypes;

use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SimpleQuantity extends FhirModel
{
    use HasFactory;

    protected $casts = ['value' => 'decimal:2'];

    public function simple_quantifiable(): MorphTo
    {
        return $this->morphTo('simple_quantifiable');
    }
}
