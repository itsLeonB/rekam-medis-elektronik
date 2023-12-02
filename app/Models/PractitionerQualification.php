<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PractitionerQualification extends Model
{
    protected $table = 'practitioner_qualification';

    protected $casts = [
        'identifier' => 'array',
        'code' => 'array',
        'period_start' => 'date',
        'period_end' => 'date'
    ];

    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
    }

    public $timestamps = false;
}
