<?php

namespace App\Models\Fhir;

use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllergyIntoleranceNote extends FhirModel
{
    protected $table = 'allergy_intolerance_note';
    protected $casts = [
        'author' => 'array',
        'time' => 'datetime'
    ];
    protected $guarded = ['id'];
    public $timestamps = false;

    public function allergy(): BelongsTo
    {
        return $this->belongsTo(AllergyIntolerance::class, 'allergy_id');
    }
}
