<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;

use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompositionAttester extends FhirModel
{
    protected $table = 'composition_attester';
    protected $casts = ['time' => 'datetime'];
    public $timestamps = false;

    public function composition(): BelongsTo
    {
        return $this->belongsTo(Composition::class);
    }

    public const MODE = [
        'binding' => [
            'valueset' => Codesystems::CompositionAttestationMode
        ],
    ];
}
