<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConditionStageAssessment extends Model
{
    protected $table = 'condition_stage_assessment';
    public $timestamps = false;

    public function conditionStage(): BelongsTo
    {
        return $this->belongsTo(ConditionStage::class, 'condition_stage_id');
    }
}
