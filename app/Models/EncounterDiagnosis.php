<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncounterDiagnosis extends Model
{
    public const USE_SYSTEM = 'http://terminology.hl7.org/CodeSystem/diagnosis-role';
    public const USE_CODE = ['AD', 'DD', 'CC', 'CM', 'pre-op', 'post-op', 'billing'];
    public const USE_DISPLAY = ['AD' => 'Admission diagnosis', 'DD' => 'Discharge diagnosis', 'CC' => 'Chief complaint', 'CM' => 'Comorbidity diagnosis', 'pre-op' => 'pre-op diagnosis', 'post-op' => 'post-op diagnosis', 'billing' => 'Billing'];

    protected $table = 'encounter_diagnosis';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }
}
