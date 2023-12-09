<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationTelecom extends Model
{
    protected $table = 'organization_telecom';
    public $timestamps = false;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public const SYSTEM = [
        'binding' => [
            'valueset' => Codesystems::ContactPointSystem
        ]
    ];

    public const USE = [
        'binding' => [
            'valueset' => Codesystems::ContactPointUse
        ]
    ];
}
