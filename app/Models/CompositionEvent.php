<?php

namespace App\Models;

use App\Fhir\Codesystems;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompositionEvent extends Model
{
    protected $table = 'composition_event';
    protected $casts = [
        'code' => 'array',
        'period_start' => 'datetime',
        'period_end' => 'datetime',
        'detail' => 'array',
    ];
    public $timestamps = false;

    public function composition(): BelongsTo
    {
        return $this->belongsTo(Composition::class);
    }

    public const CODE = [
        'binding' => [
            'valueset' => Codesystems::v3ActCode
        ],
    ];
}
