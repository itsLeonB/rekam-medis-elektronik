<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConditionCategory extends Model
{
    protected $table = 'condition_category';
    public $timestamps = false;

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }
}
