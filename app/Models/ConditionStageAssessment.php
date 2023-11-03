<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConditionStageAssessment extends Model
{
    protected $table = 'condition_stage_assessment';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function conditionStage(): BelongsTo
    {
        return $this->belongsTo(ConditionStage::class, 'condition_stage_id');
    }
}
