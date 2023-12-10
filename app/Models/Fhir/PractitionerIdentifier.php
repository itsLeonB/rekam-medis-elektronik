<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PractitionerIdentifier extends FhirModel
{
    protected $table = 'practitioner_identifier';
    public $timestamps = false;

    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
    }

    public const USE = [
        'binding' => [
            'valueset' => Codesystems::IdentifierUse
        ]
    ];
}
