<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Procedure extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($procedure) {
            $orgId = config('app.organization_id');

            $identifier = new ProcedureIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/procedure/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $procedure->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $procedure->identifier()->save($identifier);
        });
    }

    protected $table = 'procedure';
    protected $casts = [
        'based_on' => 'array',
        'part_of' => 'array',
        'performed' => 'array',
        'reason_code' => 'array',
        'reason_reference' => 'array',
        'body_site' => 'array',
        'report' => 'array',
        'complication' => 'array',
        'complication_detail' => 'array',
        'follow_up' => 'array',
        'used_reference' => 'array',
        'used_code' => 'array',
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(ProcedureIdentifier::class);
    }

    public function performer(): HasMany
    {
        return $this->hasMany(ProcedurePerformer::class);
    }

    public function note(): HasMany
    {
        return $this->hasMany(ProcedureNote::class);
    }

    public function focalDevice(): HasMany
    {
        return $this->hasMany(ProcedureFocalDevice::class);
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
            'valueset' => Valuesets::ConditionProblemDiagnosisCodes
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
