<?php

namespace App\Models\Fhir;

use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllergyIntoleranceIdentifier extends FhirModel
{
    protected $table = 'allergy_intolerance_identifier';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function allergy(): BelongsTo
    {
        return $this->belongsTo(AllergyIntolerance::class, 'allergy_id');
    }
}
