<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiagnosticReport extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($diagnostic) {
            $orgId = config('app.organization_id');

            $identifier = new DiagnosticReportIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/diagnostic/' . $orgId . '/lab';
            $identifier->use = 'official';
            $identifier->value = $diagnostic->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $diagnostic->identifier()->save($identifier);
        });
    }

    public const STATUS_SYSTEM = 'http://hl7.org/fhir/diagnostic-report-status';
    public const STATUS_CODE = ['registered', 'partial', 'preliminary', 'final', 'amended', 'corrected', 'appended', 'cancelled', 'entered-in-error', 'unknown'];
    public const STATUS_DISPLAY = ['registered' => 'Data laporan sudah di registrasi, namun belum ada hasil observasi yang tersedia.', 'partial' => 'Laporan masih dalam status parsial (initial, interim, atau preliminary). Data dalam laporan bisa saja inkomplit atau belum terverifikasi.', 'preliminary' => 'Hasil awal yang sudah terverifikasi tersedia, namun belum semua hasil final.', 'final' => 'Laporan sudah selesai dan sudah diverifikasi oleh orang yang berwenang.', 'amended' => 'Setelah status “final”, hasil laporan diubah untuk memperbarui, menambahkan informasi, dan koreksi hasil pemeriksaan.', 'corrected' => 'Setelah status “final”, hasil laporan dimodifikasi untuk membenarkan error/kesalahan dari hasil pemeriksaan.', 'appended' => 'Setelah status “final”, hasil laporan dimodifikasi untuk menambahkan konten baru. Konten sebelumnya tidak ada perubahan.', 'cancelled' => 'Hasil laporan tidak tersedia karena pemeriksaan dibatalkan.', 'entered-in-error' => 'Hasil laporan ditarik setelah dirilis “final” sebelumnya. Status ini seharusnya tidak boleh ada. Dalam kasus nyata, bila hasil observasi ditarik, status sebaiknya diisi dengan “cancelled” dibandingkan “entered-in error”.', 'unknown' => 'Sistem tidak mengetahui status dari data observasi.'];

    public const CATEGORY_SYSTEM = 'http://terminology.hl7.org/CodeSystem/v2-0074';
    public const CATEGORY_CODE = ['AU', 'BG', 'BLB', 'CG', 'CH', 'CP', 'CT', 'CTH', 'CUS', 'EC', 'EN', 'GE', 'HM', 'ICU', 'IMM', 'LAB', 'MB', 'MCB', 'MYC', 'NMR', 'NMS', 'NRS', 'OSL', 'OT', 'OTH', 'OUS', 'PF', 'PHR', 'PHY', 'PT', 'RAD', 'RC', 'RT', 'RUS', 'RX', 'SP', 'SR', 'TX', 'VR', 'VUS', 'XRC'];
    public const CATEGORY_DISPLAY = ['AU' => 'Audiology', 'BG' => 'Blood Gases', 'BLB' => 'Blood Bank', 'CG' => 'Cytogenetics', 'CH' => 'Chemistry', 'CP' => 'Cytopathology', 'CT' => 'CAT Scan', 'CTH' => 'Cardiac Catheterization', 'CUS' => 'Cardiac Ultrasound', 'EC' => 'Electrocardiac (e.g., EKG, EEC, Holter)', 'EN' => 'Electroneuro (EEG, EMG,EP,PSG)', 'GE' => 'Genetics', 'HM' => 'Hematology', 'ICU' => 'Bedside ICU Monitoring', 'IMM' => 'Immunology', 'LAB' => 'Laboratory', 'MB' => 'Microbiology', 'MCB' => 'Mycobacteriology', 'MYC' => 'Mycology', 'NMR' => 'Nuclear Magnetic Resonance', 'NMS' => 'Nuclear Medicine Scan', 'NRS' => 'Nursing Service Measures', 'OSL' => 'Outside Lab', 'OT' => 'Occupational Therapy', 'OTH' => 'Other', 'OUS' => 'OB Ultrasound', 'PF' => 'Pulmonary Function', 'PHR' => 'Pharmacy', 'PHY' => 'Physician (Hx. Dx, admission note, etc.)', 'PT' => 'Physical Therapy', 'RAD' => 'Radiology', 'RC' => 'Respiratory Care (therapy)', 'RT' => 'Radiation Therapy', 'RUS' => 'Radiology Ultrasound', 'RX' => 'Radiograph', 'SP' => 'Surgical Pathology', 'SR' => 'Serology', 'TX' => 'Toxicology', 'VR' => 'Virology', 'VUS' => 'Vascular Ultrasound', 'XRC' => 'Cineradiograph'];

    protected $table = 'diagnostic_report';
    protected $casts = [
        'based_on' => 'array',
        'category' => 'array',
        'effective' => 'array',
        'issued' => 'datetime',
        'performer' => 'array',
        'results_interpreter' => 'array',
        'specimen' => 'array',
        'result' => 'array',
        'imaging_study' => 'array'
    ];
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $with = ['identifier', 'media', 'conclusionCode'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(DiagnosticReportIdentifier::class, 'diagnostic_id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(DiagnosticReportMedia::class, 'diagnostic_id');
    }

    public function conclusionCode(): HasMany
    {
        return $this->hasMany(DiagnosticReportConclusionCode::class, 'diagnostic_id');
    }
}
