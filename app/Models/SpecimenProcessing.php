<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecimenProcessing extends Model
{
    public const PROCEDURE_SYSTEM = 'http://terminology.hl7.org/CodeSystem/v2-0373';
    public const PROCEDURE_CODE = ['ACID', 'ALK', 'DEFB', 'FILT', 'LDLP', 'NEUT', 'RECA', 'UFIL'];
    public const PROCEDURE_DISPLAY = ["ACID" => "Acidification", "ALK" => "Alkalization", "DEFB" => "Defibrination", "FILT" => "Filtration", "LDLP" => "LDL Precipitation", "NEUT" => "Neutralization", "RECA" => "Recalification", "UFIL" => "Ultrafiltration"];

    protected $table = 'specimen_processing';
    protected $casts = [
        'additive' => 'array',
        'time' => 'array'
    ];
    public $timestamps = false;

    public function specimen(): BelongsTo
    {
        return $this->belongsTo(Specimen::class);
    }
}
