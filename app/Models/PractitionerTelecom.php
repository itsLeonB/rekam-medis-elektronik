<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PractitionerTelecom extends Model
{
    use HasFactory;

    protected $table = 'practitioner_telecom';

    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
    }

    public $timestamps = false;
}
