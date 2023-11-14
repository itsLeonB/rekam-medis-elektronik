<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Condition extends Model
{
    public const CLINICAL_STATUS_SYSTEM = 'http://terminology.hl7.org/CodeSystem/condition-clinical';
    public const CLINICAL_STATUS_CODE = ['active', 'recurrence', 'relapse', 'inactive', 'remission', 'resolved'];
    public const CLINICAL_STATUS_DISPLAY = ['active' => 'Active', 'recurrence' => 'Recurrence', 'relapse' => 'Relapse', 'inactive' => 'Inactive', 'remission' => 'Remission', 'resolved' => 'Resolved'];
    public const CLINICAL_STATUS_DEFINITION = ["active" => "Pasien saat ini sedang mengalami gejala atau ada bukti dari kondisi tersebut.", "recurrence" => "Pasien mengalami rekurensi atau kembalinya kondisi setelah kondisi tersebut telah sembuh", "relapse" => "Pasien mengalami kembalinya kondisi, atau tanda dan gejala setelah periode perbaikan atau remisi", "inactive" => "Pasien tidak memiliki gejala dari suatu penyakit atau sudah tidak ada buktiadanya suatu penyakit", "remission" => "Pasien tidak memiliki gejala dari suatu penyakit, namun memiliki risiko gejala muncul kembali", "resolved" => "Pasien tidak memiliki gejala dari suatu penyakit dan tidak memiliki risiko gejala muncul kembali"];

    public const VERIFICATION_STATUS_SYSTEM = 'http://terminology.hl7.org/CodeSystem/condition-ver-status';
    public const VERIFICATION_STATUS_CODE = ['unconfirmed', 'provisional', 'differential', 'confirmed', 'refuted', 'entered-in-error'];
    public const VERIFICATION_STATUS_DISPLAY = ['unconfirmed' => 'Unconfirmed', 'provisional' => 'Provisional', 'differential' => 'Differential', 'confirmed' => 'Confirmed', 'refuted' => 'Refuted', 'entered-in-error' => 'Entered in Error'];
    public const VERIFICATION_STATUS_DEFINITION = ["unconfirmed" => "Tidak ada cukup bukti diagnostik dan/atau klinis untuk memperlakukan ini sebagai kondisi/diagnosis yang dikonfirmasi", "provisional" => "Diagnosis sementara masih merupakan kandidat yang sedang dipertimbangkan.", "differential" => "Salah satu dari daftar kemungkinan diagnosis, diikuti lebih lanjut dengan proses diagnostik dan pengobatan awal.", "confirmed" => "Ada cukup bukti diagnostik dan/atau klinis untuk mengkonfirmasi adanya suatu kondisi/diagnosis", "refuted" => "Kondisi ini telah dikesampingkan oleh bukti diagnostik dan klinis berikutnya.", "entered-in-error" => "Informasi yang dimasukkan salah dan tidak valid."];

    public const SEVERITY_SYSTEM = 'http://snomed.info/sct';
    public const SEVERITY_CODE = ['24484000', '6736007', '255604002', '442452003'];
    public const SEVERITY_DISPLAY = ['24484000' => 'Severe', '6736007' => 'Moderate', '255604002' => 'Mild', '442452003' => 'Life threatening severity'];

    public const CODE_SYSTEMS = [
        'ICD-10' => 'Diagnosis pasien saat kunjungan',
        'PATIENT_CONDITION_CODE' => 'Kondisi pasien saat meninggalkan RS',
        'VALUESET_CONDITION_CODE_KELUHANUTAMA' => 'Kode SNOMED-CT untuk keluhan utama',
        'VALUESET_CONDITION_CODE_RIWAYATPENYAKIT' => 'Kode SNOMED-CT untuk riwayat penyakit pribadi atau keluarga'
    ];
    public const PATIENT_CONDITION_SYSTEM = 'http://snomed.info/sct';
    public const PATIENT_CONDITION_CODE = ['268910001', '162668006', '359746009'];
    public const PATIENT_CONDITION_DISPLAY = ["268910001" => "Patient's condition improved", "162668006" => "Patient's condition unstable", "359746009" => "Patient's condition stable"];

    // Variable array
    public const ONSET = ['onsetDateTime', 'onsetAge', 'onsetPeriod', 'onsetRange', 'onsetString'];
    public const ABATEMENT = ['abatementDateTime', 'abatementAge', 'abatementPeriod', 'abatementRange', 'abatementString'];

    protected $table = 'condition';
    protected $casts = [
        'onset' => 'array',
        'abatement' => 'array',
        'recorded_date' => 'date'
    ];
    protected $guarded = ['id'];
    public $timestamps = false;

    // Relationships
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(ConditionIdentifier::class);
    }

    public function category(): HasMany
    {
        return $this->hasMany(ConditionCategory::class);
    }

    public function bodySite(): HasMany
    {
        return $this->hasMany(ConditionBodySite::class);
    }

    public function stage(): HasMany
    {
        return $this->hasMany(ConditionStage::class);
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(ConditionEvidence::class);
    }

    public function note(): HasMany
    {
        return $this->hasMany(ConditionNote::class);
    }
}
