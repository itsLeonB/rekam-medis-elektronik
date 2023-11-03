<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncounterHospitalizationDiet extends Model
{
    protected $table = 'encounter_hospitalization_diet';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function encounterHospitalization(): BelongsTo
    {
        return $this->belongsTo(EncounterHospitalization::class, 'enc_hosp_id');
    }
}
