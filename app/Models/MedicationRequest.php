<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicationRequest extends Model
{
    protected $table = 'medication_request';
    protected $casts = [
        'do_not_perform' => 'boolean',
        'reported' => 'boolean',
        'authored_on' => 'datetime',
        'dispense_interval_value' => 'decimal:2',
        'validitiy_start' => 'datetime',
        'validity_end' => 'datetime',
        'quantity_value' => 'decimal:2',
        'supply_duration_value' => 'decimal:2',
        'substitution_allowed' => 'json'
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(MedicationRequestIdentifier::class, 'med_req_id');
    }

    public function category(): HasMany
    {
        return $this->hasMany(MedicationRequestCategory::class, 'med_req_id');
    }

    public function reason(): HasMany
    {
        return $this->hasMany(MedicationRequestReason::class, 'med_req_id');
    }

    public function basedOn(): HasMany
    {
        return $this->hasMany(MedicationRequestBasedOn::class, 'med_req_id');
    }

    public function insurance(): HasMany
    {
        return $this->hasMany(MedicationRequestInsurance::class, 'med_req_id');
    }

    public function note(): HasMany
    {
        return $this->hasMany(MedicationRequestNote::class, 'med_req_id');
    }

    public function dosage(): HasMany
    {
        return $this->hasMany(MedicationRequestDosage::class, 'med_req_id');
    }
}
