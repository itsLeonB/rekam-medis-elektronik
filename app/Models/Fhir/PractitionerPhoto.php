<?php

namespace App\Models\Fhir;

use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PractitionerPhoto extends FhirModel
{
    protected $table = 'practitioner_photo';
    protected $casts = ['creation' => 'datetime'];
    public $timestamps = false;

    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
    }
}
