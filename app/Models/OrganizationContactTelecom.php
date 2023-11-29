<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationContactTelecom extends Model
{
    protected $table = 'organization_contact_telecom';
    public $timestamps = false;

    public function contact(): BelongsTo
    {
        return $this->belongsTo(OrganizationContact::class, 'organization_contact_id');
    }
}
