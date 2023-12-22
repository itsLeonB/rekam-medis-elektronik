<?php

namespace App\Models\Fhir\BackboneElements;

use App\Fhir\Valuesets;
use App\Models\FhirModel;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resources\Patient;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    MorphOne,
};

class PatientLink extends FhirModel
{
    protected $table = 'patient_link';
    public $timestamps = false;

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function other(): MorphOne //BelongsTo
    {
        // return $this->belongsTo(Reference::class, 'other_id');
        return $this->morphOne(Reference::class, 'referenceable');
    }

    public const TYPE = [
        'binding' => [
            'valueset' => Valuesets::LinkType
        ]
    ];
}
