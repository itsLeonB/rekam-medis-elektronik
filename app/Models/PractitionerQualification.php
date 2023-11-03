<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PractitionerQualification extends Model
{
    use HasFactory;

    protected $table = 'practitioner_qualification';

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date'
    ];

    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
    }

    public $timestamps = false;
}
