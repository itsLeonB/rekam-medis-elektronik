<?php

namespace App\Models;

use App\Fhir\Codesystems;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medication extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($medication) {
            $orgId = config('app.organization_id');

            $identifier = new MedicationIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/medication/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $medication->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $medication->identifier()->save($identifier);
        });
    }

    protected $table = 'medication';
    protected $casts = [
        'amount_numerator_value' => 'decimal:2',
        'amount_denominator_value' => 'decimal:2',
        'batch_expiration_date' => 'datetime',
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(MedicationIdentifier::class);
    }

    public function ingredient(): HasMany
    {
        return $this->hasMany(MedicationIngredient::class);
    }

    public const CODE = [
        'binding' => [
            'valueset' => Codesystems::KFA
        ]
    ];

    public const STATUS = [
        'binding' => [
            'valueset' => Codesystems::MedicationStatusCodes
        ]
    ];

    public const FORM = [
        'binding' => [
            'valueset' => Codesystems::MedicationForm
        ]
    ];

    public const MEDICATION_TYPE = [
        'binding' => [
            'valueset' => Codesystems::MedicationType
        ]
    ];
}
