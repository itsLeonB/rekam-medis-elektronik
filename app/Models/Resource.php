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
        'res_ver' => 1,
        'fhir_ver' => 'R5'
    ];

    public function content(): HasMany
    {
        return $this->hasMany(ResourceContent::class, 'res_id', 'res_id');
    }

    public function forcedId(): HasOne
    {
        return $this->hasOne(ResourceForcedId::class, 'res_id', 'res_id');
    }
}
