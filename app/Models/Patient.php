<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patient';

    protected $attributes = [
        'active' => true,
        'deceased' => null,
        'multiple_birth' => false,
        'language' => 'id-ID'
    ];

    protected $casts = [
        'active' => 'boolean',
        'birth_date' => 'date',
        'deceased' => 'datetime',
        'multiple_birth' => 'boolean'
    ];

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'res_id', 'res_id');
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(PatientIdentifier::class, 'patient_id', 'id');
    }

    public function telecom(): HasMany
    {
        return $this->hasMany(PatientTelecom::class, 'patient_id', 'id');
    }

    public function address(): HasMany
    {
        return $this->hasMany(PatientAddress::class, 'patient_id', 'id');
    }

    public function photo(): HasMany
    {
        return $this->hasMany(PatientPhoto::class, 'patient_id', 'id');
    }

    public function contact(): HasMany
    {
        return $this->hasMany(PatientContact::class, 'patient_id', 'id');
    }

    public function generalPractitioner(): HasMany
    {
        return $this->hasMany(GeneralPractitioner::class, 'patient_id', 'id');
    }
}
