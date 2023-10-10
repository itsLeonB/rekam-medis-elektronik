<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganizationContact extends Model
{
    protected $table = 'organization_contact';
    public $timestamps = false;

    public function organization(): BelongsTo
    {
        return $this->belongsTo('organization', 'id', 'organization_id');
    }

    public function telecom(): HasMany
    {
        return $this->hasMany('organization_contact_telecom', 'organization_contact_id', 'id');
    }
}
