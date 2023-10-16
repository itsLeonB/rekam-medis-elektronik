<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConditionNote extends Model
{
    protected $table = 'condition_note';
    protected $casts = ['time' => 'datetime'];
    public $timestamps = false;

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }
}
