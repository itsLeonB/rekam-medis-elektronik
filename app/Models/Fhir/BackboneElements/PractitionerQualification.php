<?php

namespace App\Models\Fhir\BackboneElements;

use App\Models\FhirModel;
use App\Models\Fhir\Datatypes\{
    CodeableConcept,
    Identifier,
    Period,
    Reference,
};
use App\Models\Fhir\Resources\Practitioner;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    MorphMany,
    MorphOne,
};

class PractitionerQualification extends FhirModel
{
    protected $table = 'practitioner_qualification';

    // protected $casts = [
    //     'identifier' => 'array',
    //     'code' => 'array',
    //     'period_start' => 'date',
    //     'period_end' => 'date'
    // ];

    protected $with = ['identifier', 'code', 'period', 'issuer'];

    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class);
    }

    public function identifier(): MorphMany
    {
        return $this->morphMany(Identifier::class, 'identifiable');
    }

    public function code(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable');
    }

    public function period(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }

    public function issuer(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable');
    }

    public $timestamps = false;
}
