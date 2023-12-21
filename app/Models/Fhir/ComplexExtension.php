<?php

namespace App\Models\Fhir;

use App\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ComplexExtension extends FhirModel
{
    use HasFactory;

    public function complexExtendable(): MorphTo
    {
        return $this->morphTo('complex_extendable');
    }

    public function extension($url): MorphMany
    {
        return $this->morphMany(Extension::class, 'extendable')->where('url', $url);
    }
}
