<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocationIdentifier extends Model
{
    protected $table = 'location_identifier';
    public $timestamps = false;

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
