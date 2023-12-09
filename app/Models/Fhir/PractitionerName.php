<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PractitionerName extends FhirModel
{
    protected $table = 'practitioner_name';
    protected $casts = [
        'given' => 'array',
        'prefix' => 'array',
        'suffix' => 'array',
        'period_start' => 'datetime',
        'period_end' => 'datetime'
    ];
    public $timestamps = false;

    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
    }

    public const USE = [
        'binding' => [
            'valueset' => Codesystems::NameUse
        ]
    ];
}
