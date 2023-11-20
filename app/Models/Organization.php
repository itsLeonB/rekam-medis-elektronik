<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($organization) {
            $orgId = config('organization_id');

            $identifier = new OrganizationIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/organization/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $organization->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $organization->identifier()->save($identifier);
        });
    }

    protected $table = 'organization';

    protected $casts = ['active' => 'boolean'];

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(OrganizationIdentifier::class);
    }

    public function type(): HasMany
    {
        return $this->hasMany(OrganizationType::class);
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
}
