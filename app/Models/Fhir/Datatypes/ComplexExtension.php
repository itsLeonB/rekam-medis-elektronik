<?php

namespace App\Models\Fhir\Datatypes;

use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ComplexExtension extends FhirModel
{
    use HasFactory;

    protected $casts = ['exts' => 'array'];

    public function complexExtendable(): MorphTo
    {
        return $this->morphTo('complex_extendable');
    }

    public function extension($url): MorphOne
    {
        return $this->morphOne(Extension::class, 'extendable')
            ->where('url', $url);
    }
}
