<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncounterHospitalizationSpecialArrangement extends Model
{
    public const SYSTEM = 'http://terminology.hl7.org/CodeSystem/encounter-special-arrangements';
    public const CODE = ['wheel', 'add-bed', 'int', 'att', 'dog'];
    public const DISPLAY = ['wheel' => 'Wheelchair', 'add-bed' => 'Additional bedding', 'int' => 'Interpreter', 'att' => 'Attendant', 'dog' => 'Guide dog'];
    public const DEFINITION = ["wheel" => "Kursi roda", "add-bed" => "Tambahan kasur", "int" => "Penerjemah", "att" => "Asisten yang membantu pasien melakukan kegiatan.", "dog" => "Anjing Pemandu"];

    protected $table = 'encounter_hospitalization_spc_arr';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function encounterHospitalization(): BelongsTo
    {
        return $this->belongsTo(EncounterHospitalization::class, 'enc_hosp_id');
    }
}
