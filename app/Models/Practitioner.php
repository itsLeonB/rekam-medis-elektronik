<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Practitioner extends Model
{
    protected $table = 'practitioner';

    protected $casts = [
        'active' => 'boolean',
        'birth_date' => 'date',
        'communication' => 'array'
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
