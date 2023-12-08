<?php

namespace App\Models;

use App\Fhir\Codesystems;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PractitionerIdentifier extends Model
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
