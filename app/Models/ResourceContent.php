<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceContent extends Model
{
    use HasFactory;

    protected $table = 'resource_content';

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'res_id', 'res_id');
    }

    public $timestamps = false;
}
