<?php

namespace App\Models\Fhir\Datatypes;

use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Age extends FhirModel
{
    use HasFactory;

    protected $casts = ['value' => 'decimal:2'];

    public function ageable(): MorphTo
    {
        return $this->morphTo('ageable');
    }
}
