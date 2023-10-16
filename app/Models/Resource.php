<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Resource extends Model
{
    use HasFactory;

    protected $table = 'resource';

    protected $attributes = [
        'res_version' => 1,
        'fhir_ver' => 'R4'
    ];

    public function content(): HasMany
    {
        return $this->hasMany(ResourceContent::class, 'res_id', 'res_id');
    }

    public function forcedId(): HasOne
    {
        return $this->hasOne(ResourceForcedId::class, 'res_id', 'res_id');
    }

    public function practitioner(): HasMany
    {
        return $this->hasMany(Practitioner::class, 'res_id', 'res_id');
    }

    public function patient(): HasMany
    {
        return $this->hasMany(Patient::class, 'res_id', 'res_id');
    }

    public function location(): HasMany
    {
        return $this->hasMany(Location::class, 'res_id', 'res_id');
    }

    public function organization(): HasMany
    {
        return $this->hasMany(Organization::class, 'res_id', 'res_id');
    }

    public function encounter(): HasMany
    {
        return $this->hasMany(Encounter::class, 'res_id', 'res_id');
    }
}
