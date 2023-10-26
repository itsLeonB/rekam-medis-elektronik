<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationDispenseSubstitutionResponsibleParty extends Model
{
    protected $table = 'med_disp_subs_responsible_party';
    public $timestamps = false;

    public function substitution(): BelongsTo
    {
        return $this->belongsTo(MedicationDispenseSubstitution::class, 'med_disp_subs_id');
    }
}
