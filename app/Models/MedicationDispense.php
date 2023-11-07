<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MedicationDispense extends Model
{
    protected $table = 'medication_dispense';
    protected $casts = [
        'quantity_value' => 'decimal:2',
        'days_supply_value' => 'decimal:2',
        'when_prepared' => 'datetime',
        'when_handed_over' => 'datetime'
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

    public function partOf(): HasMany
    {
        return $this->hasMany(MedicationDispensePartOf::class, 'dispense_id');
    }

    public function performer(): HasMany
    {
        return $this->hasMany(MedicationDispensePerformer::class, 'dispense_id');
    }

    public function authorizingPrescription(): HasMany
    {
        return $this->hasMany(MedicationDispenseAuthorizingPrescription::class, 'dispense_id');
    }

    public function dosageInstruction(): HasMany
    {
        return $this->hasMany(MedicationDispenseDosageInstruction::class, 'dispense_id');
    }

    public function substitution(): HasOne
    {
        return $this->hasOne(MedicationDispenseSubstitution::class, 'dispense_id');
    }
}
