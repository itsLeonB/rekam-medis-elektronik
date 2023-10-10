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
        return $this->belongsTo('resource', 'res_id', 'res_id');
    }

    public function identifier(): HasMany
    {
        return $this->hasMany('organization_identifier', 'organization_id', 'id');
    }

    public function type(): HasMany
    {
        return $this->hasMany('organization_type', 'organization_id', 'id');
    }

    public function telecom(): HasMany
    {
        return $this->hasMany('organization_telecom', 'organization_id', 'id');
    }

    public function address(): HasMany
    {
        return $this->hasMany('organization_address', 'organization_id', 'id');
    }

    public function contact(): HasMany
    {
        return $this->hasMany('organization_contact', 'organization_id', 'id');
    }
}
