<?php

namespace App\Models\Fhir;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Encounter extends FhirModel
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($encounter) {
            $orgId = config('app.organization_id');

            $identifier = new EncounterIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/encounter/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $encounter->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $encounter->identifier()->save($identifier);
        });
    }

    protected $table = 'encounter';
    protected $casts = [
        'type' => 'array',
        'episode_of_care' => 'array',
        'based_on' => 'array',
        'period_start' => 'datetime',
        'period_end' => 'datetime',
        'length_value' => 'decimal:2',
        'reason_code' => 'array',
        'reason_reference' => 'array',
        'account' => 'array',
        'hospitalization_diet_preference' => 'array',
        'hospitalization_special_arrangement' => 'array',
    ];
    public $timestamps = false;
    protected $with = ['identifier', 'statusHistory', 'classHistory', 'participant', 'diagnosis', 'location'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(EncounterIdentifier::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(EncounterStatusHistory::class);
    }

    public function classHistory(): HasMany
    {
        return $this->hasMany(EncounterClassHistory::class);
    }

    public function participant(): HasMany
    {
        return $this->hasMany(EncounterParticipant::class);
    }

    public function diagnosis(): HasMany
    {
        return $this->hasMany(EncounterDiagnosis::class);
    }

    public function location(): HasMany
    {
        return $this->hasMany(EncounterLocation::class);
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

    public const HOSPITALIZATION_ADMIT_SOURCE = [
        'binding' => [
            'valueset' => Codesystems::AdmitSource
        ]
    ];

    public const HOSPITALIZATION_RE_ADMISSION = [
        'binding' => [
            'valueset' => Codesystems::v20092
        ]
    ];

    public const HOSPITALIZATION_DIET_PREFERENCE = [
        'binding' => [
            'valueset' => Codesystems::Diet
        ]
    ];

    public const HOSPITALIZATION_SPECIAL_ARRANGEMENT = [
        'binding' => [
            'valueset' => Codesystems::SpecialArrangements
        ]
    ];

    public const HOSPITALIZATION_DISCHARGE_DISPOSITION = [
        'binding' => [
            'valueset' => Codesystems::DischargeDisposition
        ]
    ];
}
