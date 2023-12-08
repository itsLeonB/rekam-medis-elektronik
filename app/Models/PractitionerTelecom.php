<?php

namespace App\Models;

use App\Fhir\Codesystems;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PractitionerTelecom extends Model
{
    use HasFactory;

    protected $table = 'practitioner_telecom';
    public $timestamps = false;

    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
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
