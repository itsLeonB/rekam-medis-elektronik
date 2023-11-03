<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConditionIdentifier extends Identifier
{
    protected $table = 'condition_identifier';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }
}
