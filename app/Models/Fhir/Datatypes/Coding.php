<?php

namespace App\Models\Fhir\Datatypes;

use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Coding extends FhirModel
{
    use HasFactory;

    protected $casts = ['userSelected' => 'boolean'];

    public function codeable(): MorphTo
    {
        return $this->morphTo('codeable');
    }
}
