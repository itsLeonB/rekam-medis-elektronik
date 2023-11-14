<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Procedure extends Model
{
    public const STATUS_SYSTEM = 'http://hl7.org/fhir/eventstatus';
    public const STATUS_CODE = ['preparation', 'in-progress', 'not-done', 'on-hold', 'stopped', 'completed', 'entered-in-error', 'unknown'];
    public const STATUS_DISPLAY = ['preparation' => 'Persiapan', 'in-progress' => 'Berlangsung', 'not-done' => 'Tidak dilakukan', 'on-hold' => 'Tertahan', 'stopped' => 'Berhenti', 'completed' => 'Selesai', 'entered-in-error' => 'Salah masuk', 'unknown' => 'Tidak diketahui'];

    public const CATEGORY_SYSTEM = 'http://snomed.info/sct';
    public const CATEGORY_CODE = ['24642003', '409063005', '409073007', '387713003', '103693007', '46947000', '410606002', '277132007'];
    public const CATEGORY_DISPLAY = ['24642003' => 'Psychiatry procedure or service', '409063005' => 'Counselling', '409073007' => 'Education', '387713003' => 'Surgical procedure', '103693007' => 'Diagnostic procedure', '46947000' => 'Chiropractic manipulation', '410606002' => 'Social service procedure', '277132007' => 'Therapeutic procedure'];

    public const CODE_SYSTEMS = [
        'ICD-9 CM' => 'Kode untuk tindakan atau prosedur medis untuk keperluan klaim',
        'SNOMED-CT' => 'Kode terkait prosedur medis seperti edukasi, perawatan terhadap bayi baru lahir dan lainnya',
        'KEMKES' => 'Kode prosedur lainnya'
    ];
    public const CODE_KEMKES_SYSTEM = 'http://terminology.kemkes.go.id/CodeSystem/clinical-term';
    public const CODE_KEMKES_CODE = ['ED000008', 'ED000009', 'ED000010', 'ED000011', 'ED000012', 'PC000001', 'PC000002', 'PC000003'];
    public const CODE_KEMKES_DISPLAY = ['ED000008' => 'Edukasi Tanda Bahaya Kehamilan, Bersalin dan Nifas', 'ED000009' => 'Edukasi IMD dan ASI Eksklusif', 'ED000010' => 'Edukasi PHBS', 'ED000011' => 'Edukasi KB pasca salin', 'ED000012' => 'Edukasi lainnya', 'PC000001' => 'Perawatan tali pusat', 'PC000002' => 'Pemberian salep antibiotik mata', 'PC000003' => 'Manajemen Terpadu Bayi Muda (MTBM)'];

    public const OUTCOME_SYSTEM = 'http://snomed.info/sct';
    public const OUTCOME_CODE = ['385669000', '385671000', '385670004'];
    public const OUTCOME_DISPLAY = ['385669000' => 'Successful', '385671000' => 'Unsuccessful', '385670004' => 'Partially successful'];

    protected $table = 'procedure';
    protected $casts = ['performed' => 'array'];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(ProcedureIdentifier::class);
    }

    public function basedOn(): HasMany
    {
        return $this->hasMany(ProcedureBasedOn::class);
    }

    public function partOf(): HasMany
    {
        return $this->hasMany(ProcedurePartOf::class);
    }

    public function performer(): HasMany
    {
        return $this->hasMany(ProcedurePerformer::class);
    }

    public function reason(): HasMany
    {
        return $this->hasMany(ProcedureReason::class);
    }

    public function bodySite(): HasMany
    {
        return $this->hasMany(ProcedureBodySite::class);
    }

    public function report(): HasMany
    {
        return $this->hasMany(ProcedureReport::class);
    }

    public function complication(): HasMany
    {
        return $this->hasMany(ProcedureComplication::class);
    }

    public function followUp(): HasMany
    {
        return $this->hasMany(ProcedureFollowUp::class);
    }

    public function note(): HasMany
    {
        return $this->hasMany(ProcedureNote::class);
    }

    public function focalDevice(): HasMany
    {
        return $this->hasMany(ProcedureFocalDevice::class);
    }

    public function itemUsed(): HasMany
    {
        return $this->hasMany(ProcedureItemUsed::class);
    }
}
