<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocationOperationHours extends Model
{
    protected $table = 'location_operation_hours';

    protected $casts = [
        'mon' => 'boolean',
        'tue' => 'boolean',
        'wed' => 'boolean',
        'thu' => 'boolean',
        'fri' => 'boolean',
        'sat' => 'boolean',
        'sun' => 'boolean',
        'opening_time' => 'time',
        'closing_time' => 'time',
    ];

    public $timestamps = false;

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
