<?php

namespace App\Models;

use App\Fhir\Codesystems;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PractitionerName extends Model
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
