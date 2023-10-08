<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactTelecom extends Model
{
    use HasFactory;

    protected $table = 'contact_telecom';

    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(PatientContact::class, 'id', 'patient_id');
    }
}
