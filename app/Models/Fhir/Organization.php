<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Organization extends FhirModel
{
    protected $table = 'organization';
    protected $casts = [
        'active' => 'boolean',
        // 'type' => 'array',
        'alias' => 'array',
        // 'endpoint' => 'array'
    ];
    protected $with = ['identifier', 'type', 'telecom', 'address', 'partOf', 'contact', 'endpoint'];
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

    // public function identifier(): HasMany
    // {
    //     return $this->hasMany(OrganizationIdentifier::class);
    // }

    public function telecom(): MorphMany
    {
        return $this->morphMany(ContactPoint::class, 'contact_pointable');
    }

    // public function telecom(): HasMany
    // {
    //     return $this->hasMany(OrganizationTelecom::class);
    // }

    public function address(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    // public function address(): HasMany
    // {
    //     return $this->hasMany(OrganizationAddress::class);
    // }

    public function reference(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable');
    }

    public function partOf(): MorphOne
    {
        return $this->reference()->where('attr_type', 'partOf');
    }

    public function contact(): HasMany
    {
        return $this->hasMany(OrganizationContact::class);
    }

    public function endpoint(): MorphMany
    {
        return $this->reference()->where('attr_type', 'endpoint');
    }

    public const TYPE = [
        'binding' => [
            'valueset' => Codesystems::OrganizationType
        ]
    ];
}
