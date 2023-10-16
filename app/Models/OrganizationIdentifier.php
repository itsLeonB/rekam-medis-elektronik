<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationIdentifier extends Model
{
    protected $table = 'organization_identifier';
    public $timestamps = false;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
