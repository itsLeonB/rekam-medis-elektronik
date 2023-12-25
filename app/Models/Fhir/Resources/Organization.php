<?php

namespace App\Models\Fhir\Resources;

use App\Fhir\Codesystems;
use App\Models\FhirModel;
use App\Models\Fhir\Resource;
use App\Models\Fhir\BackboneElements\OrganizationContact;
use App\Models\Fhir\Datatypes\{
    Address,
    CodeableConcept,
    ContactPoint,
    Identifier,
    Reference
};
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasMany,
    MorphMany,
    MorphOne
};

class Organization extends FhirModel
{
    protected $table = 'organization';

    protected $casts = [
        'active' => 'boolean',
        'alias' => 'array'
    ];

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): MorphMany
    {
        return $this->morphMany(Identifier::class, 'identifiable');
    }

    public function type(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable');
    }

    public function telecom(): MorphMany
    {
        return $this->morphMany(ContactPoint::class, 'contact_pointable');
    }

    public function address(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function partOf(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'partOf');
    }

    public function contact(): HasMany
    {
        return $this->hasMany(OrganizationContact::class);
    }

    public function endpoint(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'endpoint');
    }

    public const TYPE = [
        'binding' => [
            'valueset' => Codesystems::OrganizationType
        ]
    ];
}
