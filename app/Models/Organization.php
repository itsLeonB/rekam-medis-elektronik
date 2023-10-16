<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organization';

    protected $casts = ['active' => 'boolean'];

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'res_id', 'res_id');
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(OrganizationIdentifier::class, 'organization_id', 'id');
    }

    public function type(): HasMany
    {
        return $this->hasMany(OrganizationType::class, 'organization_id', 'id');
    }

    public function telecom(): HasMany
    {
        return $this->hasMany(OrganizationTelecom::class, 'organization_id', 'id');
    }

    public function address(): HasMany
    {
        return $this->hasMany(OrganizationAddress::class, 'organization_id', 'id');
    }

    public function contact(): HasMany
    {
        return $this->hasMany(OrganizationContact::class, 'organization_id', 'id');
    }
}
