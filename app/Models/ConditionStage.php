<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConditionStage extends Model
{
    protected $table = 'condition_stage';
    public $timestamps = false;

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }

    public function assessment(): HasMany
    {
        return $this->hasMany(ConditionStageAssessment::class, 'condition_stage_id');
    }
}
