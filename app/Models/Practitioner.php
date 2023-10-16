<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Practitioner extends Model
{
    use HasFactory;

    protected $table = 'practitioner';

    protected $attributes = [
        'nik' => 9999999999999999,
        'ihs_number' => 'N10000000',
        'active' => 1
    ];

    protected $casts = [
        'birth_date' => 'date'
    ];

    public function telecom(): HasMany
    {
        return $this->hasMany(PractitionerTelecom::class);
    }

    public function address(): HasMany
    {
        return $this->HasMany(PractitionerAddress::class);
    }

    public function qualification(): HasMany
    {
        return $this->HasMany(PractitionerQualification::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function userProfile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'practitioner_id', 'id');
    }

    public $timestamps = false;
}
