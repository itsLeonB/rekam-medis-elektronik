<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationContactTelecom extends Model
{
    protected $table = 'organization_contact_telecom';
    public $timestamps = false;

    public function organizationContact(): BelongsTo
    {
        return $this->belongsTo(OrganizationContact::class, 'organization_contact_id');
    }
}
