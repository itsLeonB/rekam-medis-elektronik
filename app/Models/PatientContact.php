<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PatientContact extends Model
{
    use HasFactory;

    protected $table = 'patient_contact';

    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'id', 'patient_id');
    }

    public function telecom(): HasMany
    {
        return $this->hasMany(ContactTelecom::class, 'contact_id', 'id');
    }

    public function address(): HasMany
    {
        return $this->hasMany(ContactAddress::class, 'contact_id', 'id');
    }
}
