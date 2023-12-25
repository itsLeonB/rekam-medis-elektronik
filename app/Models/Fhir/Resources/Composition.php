<?php

namespace App\Models\Fhir\Resources;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Models\Fhir\BackboneElements\CompositionAttester;
use App\Models\Fhir\BackboneElements\CompositionEvent;
use App\Models\Fhir\BackboneElements\CompositionRelatesTo;
use App\Models\Fhir\BackboneElements\CompositionSection;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\ComplexExtension;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resource;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class Composition extends FhirModel
{
    use HasFactory;

    protected $table = 'composition';

    protected $casts = ['date' => 'datetime'];

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): MorphOne
    {
        return $this->morphOne(Identifier::class, 'identifiable');
    }

    public function type(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'type');
    }

    public function category(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'category');
    }

    public function subject(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'subject');
    }

    public function encounter(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'encounter');
    }

    public function author(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'author');
    }

    public function attester(): HasMany
    {
        return $this->hasMany(CompositionAttester::class);
    }

    public function custodian(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'custodian');
    }

    public function relatesTo(): HasMany
    {
        return $this->hasMany(CompositionRelatesTo::class);
    }

    public function event(): HasMany
    {
        return $this->hasMany(CompositionEvent::class);
    }

    public function section(): HasMany
    {
        return $this->hasMany(CompositionSection::class);
    }

    public function documentStatus(): MorphOne
    {
        return $this->morphOne(ComplexExtension::class, 'complex_extendable')
            ->where('url', 'http://hl7.org/fhir/StructureDefinition/composition-status');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($composition) {
            $orgId = config('app.organization_id');
            $identifier = new Identifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/composition/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = Str::uuid();
            $composition->identifier()->save($identifier);
        });
    }

    public const STATUS = [
        'binding' => [
            'valueset' => Codesystems::CompositionStatus
        ]
    ];

    public const TYPE = [
        'binding' => [
            'valueset' => Valuesets::FHIRDocumentTypeCodes
        ]
    ];

    public const CATEGORY = [
        'binding' => [
            'valueset' => Valuesets::DocumentClassValueSet
        ]
    ];

    public const CONFIDENTIALITY = [
        'binding' => [
            'valueset' => Valuesets::v3ConfidentialityClassification
        ]
    ];
}
