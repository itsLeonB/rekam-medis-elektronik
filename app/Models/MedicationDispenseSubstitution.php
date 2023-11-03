<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicationDispenseSubstitution extends Model
{
    protected $table = 'medication_dispense_substitution';
    protected $casts = ['was_substituted' => 'boolean'];
    public $timestamps = false;

    public function dispense(): BelongsTo
    {
        return $this->belongsTo(MedicationDispense::class, 'dispense_id');
    }

    public function reason(): HasMany
    {
        return $this->hasMany(MedicationDispenseSubstitutionReason::class, 'med_disp_subs_id');
    }

    public function responsibleParty(): HasMany
    {
        return $this->hasMany(MedicationDispenseSubstitutionResponsibleParty::class, 'med_disp_subs_id');
    }
}
