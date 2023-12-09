<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $table = 'organization';
    protected $casts = [
        'active' => 'boolean',
        'type' => 'array',
        'alias' => 'array',
        'endpoint' => 'array'
    ];
    protected $with = ['identifier', 'telecom', 'address', 'contact'];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(OrganizationIdentifier::class);
    }

    public function telecom(): HasMany
    {
        return $this->hasMany(OrganizationTelecom::class);
    }

    public function address(): HasMany
    {
        return $this->hasMany(OrganizationAddress::class);
    }

    public function contact(): HasMany
    {
        return $this->hasMany(OrganizationContact::class);
    }

    public const TYPE = [
        'binding' => [
            'valueset' => Codesystems::OrganizationType
        ]
    ];
}
