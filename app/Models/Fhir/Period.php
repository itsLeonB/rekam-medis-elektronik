<?php

namespace App\Models\Fhir;

use App\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Period extends FhirModel
{
    use HasFactory;

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function periodable(): MorphTo
    {
        return $this->morphTo('periodable');
    }
}
