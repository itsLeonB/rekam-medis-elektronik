<?php

namespace App\Models\Fhir\Resources;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Models\Fhir\BackboneElements\ProcedureFocalDevice;
use App\Models\Fhir\BackboneElements\ProcedurePerformer;
use App\Models\Fhir\Datatypes\Annotation;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resource;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class Procedure extends FhirModel
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($procedure) {
            $orgId = config('app.organization_id');

            $identifier = new Identifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/procedure/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = Str::uuid();

            // Save the identifier through the relationship
            $procedure->identifier()->save($identifier);
        });
    }

    protected $table = 'procedure';
    protected $casts = [
        'instantiates_canonical' => 'array',
        'instantiates_uri' => 'array',
        'performed_date_time' => 'datetime',
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

    public function basedOn(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'basedOn');
    }

    public function partOf(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'partOf');
    }

    public function statusReason(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'statusReason');
    }

    public function category(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'category');
    }

    public function code(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'code');
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

    public function performedPeriod(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable')
            ->where('attr_type', 'performedPeriod');
    }

    public function performedAge(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable')
            ->where('attr_type', 'performedAge');
    }

    public function performedRange(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable')
            ->where('attr_type', 'performedRange');
    }

    public function recorder(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'recorder');
    }

    public function asserter(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'asserter');
    }

    public function performer(): HasMany
    {
        return $this->hasMany(ProcedurePerformer::class);
    }

    public function location(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'location');
    }

    public function reasonCode(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'reasonCode');
    }

    public function reasonReference(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'reasonReference');
    }

    public function bodySite(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'bodySite');
    }

    public function outcome(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'outcome');
    }

    public function report(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'report');
    }

    public function complication(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'complication');
    }

    public function complicationDetail(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'complicationDetail');
    }

    public function followUp(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'followUp');
    }

    public function note(): MorphMany
    {
        return $this->morphMany(Annotation::class, 'annotable');
    }

    public function focalDevice(): HasMany
    {
        return $this->hasMany(ProcedureFocalDevice::class);
    }

    public function usedReference(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'usedReference');
    }

    public function usedCode(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'usedCode');
    }

    public const STATUS = [
        'binding' => [
            'valueset' => Codesystems::EventStatus
        ]
    ];

    public const STATUS_REASON = [
        'binding' => [
            'valueset' => Valuesets::ProcedureNotPerformedReason
        ]
    ];

    public const CATEGORY = [
        'binding' => [
            'valueset' => Valuesets::ProcedureCategoryCodes
        ]
    ];

    public const CODE = [
        'binding' => [
            'valueset' => Valuesets::ProcedureCodes
        ]
    ];

    public const PERFORMED = [
        'variableTypes' => ['performedDateTime', 'performedPeriod', 'performedString', 'performedAge', 'performedRange']
    ];

    public const REASON_CODE = [
        'binding' => [
            'valueset' => Codesystems::ICD10
        ]
    ];

    public const BODY_SITE = [
        'binding' => [
            'valueset' => Valuesets::SNOMEDCTBodySite
        ]
    ];

    public const OUTCOME = [
        'binding' => [
            'valueset' => Valuesets::ProcedureOutcomeCodes
        ]
    ];

    public const COMPLICATION = [
        'binding' => [
            'valueset' => Codesystems::ICD10
        ]
    ];

    public const FOLLOW_UP = [
        'binding' => [
            'valueset' => Valuesets::ProcedureFollowUpCodes
        ]
    ];

    public const USED_CODE = [
        'binding' => [
            'valueset' => Valuesets::FHIRDeviceTypes
        ]
    ];
}
