<?php

namespace App\Models\Fhir\Resources;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Models\Fhir\BackboneElements\EncounterClassHistory;
use App\Models\Fhir\BackboneElements\EncounterDiagnosis;
use App\Models\Fhir\BackboneElements\EncounterHospitalization;
use App\Models\Fhir\BackboneElements\EncounterLocation;
use App\Models\Fhir\BackboneElements\EncounterParticipant;
use App\Models\Fhir\BackboneElements\EncounterStatusHistory;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Coding;
use App\Models\Fhir\Datatypes\Duration;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resource;
use App\Models\FhirModel;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Encounter extends FhirModel
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::created(function ($encounter) {
            $existingIdentifier = $encounter->identifier()
                ->where('system', config('app.identifier_systems.encounter'))
                ->first();

            if (!$existingIdentifier) {
                $identifier = new Identifier();
                $identifier->system = config('app.identifier_systems.encounter');
                $identifier->use = 'official';
                $identifier->value = Str::uuid();
                $encounter->identifier()->save($identifier);
            }
        });
    }

    protected $table = 'encounter';

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): MorphMany
    {
        return $this->morphMany(Identifier::class, 'identifiable');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(EncounterStatusHistory::class);
    }

    public function class(): MorphOne
    {
        return $this->morphOne(Coding::class, 'codeable');
    }

    public function classHistory(): HasMany
    {
        return $this->hasMany(EncounterClassHistory::class);
    }

    public function type(): MorphMany
    {
        return $this->morphMany(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'type');
    }

    public function serviceType(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'serviceType');
    }

    public function priority(): MorphOne
    {
        return $this->morphOne(CodeableConcept::class, 'codeable')
            ->where('attr_type', 'priority');
    }

    public function subject(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'subject');
    }

    public function episodeOfCare(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'episodeOfCare');
    }

    public function basedOn(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'basedOn');
    }

    public function participant(): HasMany
    {
        return $this->hasMany(EncounterParticipant::class);
    }

    public function appointment(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'appointment');
    }

    public function period(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }

    public function length(): MorphOne
    {
        return $this->morphOne(Duration::class, 'durationable');
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

    public function diagnosis(): HasMany
    {
        return $this->hasMany(EncounterDiagnosis::class);
    }

    public function account(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referenceable')
            ->where('attr_type', 'account');
    }

    public function hospitalization(): HasOne
    {
        return $this->hasOne(EncounterHospitalization::class);
    }

    public function location(): HasMany
    {
        return $this->hasMany(EncounterLocation::class);
    }

    public function serviceProvider(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'serviceProvider');
    }

    public function partOf(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable')
            ->where('attr_type', 'partOf');
    }

    public const STATUS = [
        'binding' => [
            'valueset' => Valuesets::EncounterStatus
        ]
    ];

    public const ENC_CLASS = [
        'binding' => [
            'valueset' => Valuesets::EncounterClass
        ]
    ];

    public const TYPE = [
        'binding' => [
            'valueset' => Codesystems::EncounterType
        ]
    ];

    public const SERVICE_TYPE = [
        'binding' => [
            'valueset' => Codesystems::ServiceType
        ]
    ];

    public const PRIORITY = [
        'binding' => [
            'valueset' => Valuesets::EncounterPriority
        ]
    ];

    public const REASON_CODE = [
        'binding' => [
            'valueset' => Valuesets::EncounterReasonCodes
        ]
    ];
}
