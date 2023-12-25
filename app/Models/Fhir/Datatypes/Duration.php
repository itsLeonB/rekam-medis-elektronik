<?php

namespace App\Models\Fhir\Datatypes;

use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Duration extends FhirModel
{
    use HasFactory;

    protected $casts = ['value' => 'decimal:2'];

    public function durationable()
    {
        return $this->morphTo('durationable');
    }
}