<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationType extends Model
{
    protected $table = 'organization_type';
    public $timestamps = false;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
