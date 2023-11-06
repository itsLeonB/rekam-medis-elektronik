<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EncounterHospitalization extends Model
{
    public const ADMIT_SOURCE_SYSTEM = 'http://terminology.hl7.org/CodeSystem/admit-source';
    public const ADMIT_SOURCE_CODE = ['hosp-trans', 'emd', 'outp', 'born', 'gp', 'mp', 'nursing', 'psych', 'rehab', 'other'];
    public const ADMIT_SOURCE_DISPLAY = ['hosp-trans' => 'Transferred from other hospital', 'emd' => 'From accident/emergency department', 'outp' => 'From outpatient department', 'born' => 'Born in hospital', 'gp' => 'General Practitioner referral', 'mp' => 'MedicalPractitioner/physicia n referral', 'nursing' => 'From nursing home', 'psych' => 'From psychiatric hospital', 'rehab' => 'From rehabilitation facility', 'other' => 'Other'];
    public const ADMIT_SOURCE_DEFINITION = ["hosp-trans" => "Pasien dirujuk ke rumah sakit lain", "emd" => "Pasien dipindahkan dari departemen gawat darurat dalam rumah sakit tersebut. Biasanya terjadi ketika transisi ke kunjungan rawat inap.", "outp" => "Pasien dipindahkan dari departemen rawat jalandalam rumah sakit tersebut", "born" => "Pasien adalah bayi baru lahir dan kunjungan ini digunakan untuk melacak kondisi bayi", "gp" => "Pasien masuk/admisi akibat rujukan dari fasyankes tingkat satu", "mp" => "Pasien diadmisi karena rujukan dari spesialis", "nursing" => "Pasien dipindahkan dari panti jompo", "psych" => "Pasien dipindahkan darifasilitas psikiatri", "rehab" => "Pasien dipindahkan dari fasilitas atau klinik rehabilitasi", "other" => "Pasien masuk dari sumber yang tidakdiketahui"];

    public const READMISSION_SYSTEM = 'http://terminology.hl7.org/CodeSystem/v2-0092';
    public const READMISSION_CODE = ['R'];
    public const READMISSION_DISPLAY = 'Re-admission';

    protected $table = 'encounter_hospitalization';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function diet(): HasMany
    {
        return $this->hasMany(EncounterHospitalizationDiet::class, 'enc_hosp_id', 'id');
    }

    public function specialArrangement(): HasMany
    {
        return $this->hasMany(EncounterHospitalizationSpecialArrangement::class, 'enc_hosp_id', 'id');
    }
}
