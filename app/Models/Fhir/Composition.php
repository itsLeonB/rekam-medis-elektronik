<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Composition extends FhirModel
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($composition) {
            $orgId = config('app.organization_id');
            $composition->identifier_system = 'http://sys-ids.kemkes.go.id/composition/' . $orgId;
            $composition->identifier_use = 'official';
            $composition->identifier_value = $composition->max('identifier_value') + 1;
        });
    }

    protected $table = 'composition';
    protected $casts = [
        'category' => 'array',
        'date' => 'datetime',
        'author' => 'array',
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function attester(): HasMany
    {
        return $this->hasMany(CompositionAttester::class);
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
