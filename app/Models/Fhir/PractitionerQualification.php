<?php

namespace App\Models\Fhir;

use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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
