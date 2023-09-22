<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PractitionerAddress extends Model
{
    use HasFactory;

    protected $table = 'practitioner_address';

    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class, 'id', 'practitioner_id');
    }

    public $timestamps = false;
}
