<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MedicationDispense extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($medicationDispense) {
            $orgId = config('app.organization_id');

            $identifier = new MedicationDispenseIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/medicationdispense/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $medicationDispense->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $medicationDispense->identifier()->save($identifier);
        });
    }

    protected $table = 'medication_dispense';
    protected $casts = [
        'part_of' => 'array',
        'authorizing_prescription' => 'array',
        'quantity_value' => 'decimal:2',
        'days_supply_value' => 'decimal:2',
        'when_prepared' => 'datetime',
        'when_handed_over' => 'datetime',
        'substitution_was_substituted' => 'boolean',
        'substitution_reason' => 'array',
        'substitution_responsible_party' => 'array'
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(MedicationDispenseIdentifier::class, 'dispense_id');
    }

    public function performer(): HasMany
    {
        return $this->hasMany(MedicationDispensePerformer::class, 'dispense_id');
    }

    public function dosageInstruction(): HasMany
    {
        return $this->hasMany(MedicationDispenseDosageInstruction::class, 'dispense_id');
    }

    public const STATUS = [
        'binding' => [
            'valueset' => Codesystems::MedicationDispenseStatusCodes
        ]
    ];

    public const CATEGORY = [
        'binding' => [
            'valueset' => Codesystems::MedicationDispenseCategoryCodes
        ]
    ];

    public const QUANTITY = [
        'binding' => [
            'valueset' => Valuesets::MedicationDispenseQuantity
        ]
    ];

    public const DAYS_SUPPLY = [
        'binding' => [
            'valueset' => Valuesets::UnitsOfTime
        ]
    ];

    public const SUBSTITUTION_TYPE = [
        'binding' => [
            'valueset' => Valuesets::v3ActSubstanceAdminSubstitutionCode
        ]
    ];

    public const SUBSTITUTION_REASON = [
        'binding' => [
            'valueset' => Valuesets::v3SubstanceAdminSubstitutionReason
        ]
    ];
}
