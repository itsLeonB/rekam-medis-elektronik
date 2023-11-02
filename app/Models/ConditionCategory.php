<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConditionCategory extends Model
{
    // Enum values
    public const CODE = ['problem-list-item', 'encounter-diagnosis'];
    public const CATEGORY_DISPLAY = [
        'problem-list-item' => [
            'display' => 'Problem List Item',
            'definition' => 'An item on a problem list that can be managed over time and can be expressed by a practitioner (e.g. physician, nurse), patient, or related person.'
        ],
        'encounter-diagnosis' => [
            'display' => 'Encounter Diagnosis',
            'definition' => 'A point in time diagnosis (e.g. from a physician or nurse) in context of an encounter.'
        ]
    ];

    protected $table = 'condition_category';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }
}
