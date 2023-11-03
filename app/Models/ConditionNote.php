<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConditionNote extends Model
{
    protected $table = 'condition_note';
    protected $casts = ['time' => 'datetime'];
    protected $guarded = ['id'];
    public $timestamps = false;

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }
}
