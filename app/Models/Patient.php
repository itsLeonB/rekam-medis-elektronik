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
        'language' => 'id-ID'
    ];

    protected $casts = [
        'active' => 'boolean',
        'birth_date' => 'date',
        'deceased' => 'json',
        'multiple_birth' => 'json'
    ];

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(PatientIdentifier::class);
    }

    public function telecom(): HasMany
    {
        return $this->hasMany(PatientTelecom::class);
    }

    public function address(): HasMany
    {
        return $this->hasMany(PatientAddress::class);
    }

    public function photo(): HasMany
    {
        return $this->hasMany(PatientPhoto::class);
    }

    public function contact(): HasMany
    {
        return $this->hasMany(PatientContact::class);
    }

    public function generalPractitioner(): HasMany
    {
        return $this->hasMany(GeneralPractitioner::class);
    }
}
