<?php

namespace App\Models\Fhir;


use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConditionNote extends FhirModel
{
    protected $table = 'condition_note';
    protected $casts = [
        'author' => 'array',
        'time' => 'datetime'
    ];
    protected $guarded = ['id'];
    public $timestamps = false;

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class);
    }
}
