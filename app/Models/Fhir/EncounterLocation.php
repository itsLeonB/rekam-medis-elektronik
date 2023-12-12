<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncounterLocation extends FhirModel
{
    protected $table = 'encounter_location';
    public $timestamps = false;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public const SERVICE_CLASS = [
        'binding' => [
            'valueset' => Valuesets::LocationServiceClass
        ]
    ];

    public const UPGRADE_CLASS = [
        'binding' => [
            'valueset' => Codesystems::LocationUpgradeClass
        ]
    ];
}
