<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Encounter extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($encounter) {
            $orgId = config('app.organization_id');

            $identifier = new EncounterIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/encounter/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $encounter->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $encounter->identifier()->save($identifier);
        });
    }

    public const STATUS_SYSTEM = 'http://terminology.hl7.org/CodeSystem/v2-0203';
    public const STATUS_CODE = ['planned', 'arrived', 'triaged', 'in-progress', 'onleave', 'finished', 'cancelled'];
    public const STATUS_DISPLAY = ['planned' => 'Sudah direncanakan', 'arrived' => 'Sudah datang', 'triaged' => 'Triase', 'in-progress' => 'Sedang berlangsung', 'onleave' => 'Sedang pergi', 'finished' => 'Sudah selesai', 'cancelled' => 'Dibatalkan'];

    public const CLASS_SYSTEM = 'http://terminology.hl7.org/CodeSystem/v3-ActCode';
    public const CLASS_CODE = ['AMB', 'EMER', 'FLD', 'HH', 'IMP', 'ACUTE', 'NONAC', 'OBSENC', 'PRENC', 'SS', 'VR'];
    public const CLASS_DISPLAY = ['AMB' => 'ambulatory', 'EMER' => 'emergency', 'FLD' => 'field', 'HH' => 'home health', 'IMP' => 'inpatientencounter', 'ACUTE' => 'inpatient acute', 'NONAC' => 'inpatientnon-acute', 'OBSENC' => 'observationencounter', 'PRENC' => 'pre-admission', 'SS' => 'short stay', 'VR' => 'virtual'];
    public const CLASS_DEFINITION = ["AMB" => "Digunakan untuk kunjungan Rawat Jalan", "EMER" => "Digunakan untuk kunjungan instalasi gawat darurat", "FLD" => "Digunakan untuk kunjungan lapangan", "HH" => "Digunakan untuk kunjungan ke rumah", "IMP" => "Digunakan untuk kunjungan rawat inap", "ACUTE" => "Digunakan untuk kunjungan rawat inap akut", "NONAC" => "Digunakan untuk kunjungan rawat inap non-akut", "OBSENC" => "Digunakan untuk kunjungan observasi", "PRENC" => "Digunakan untuk kunjungan preadmisi", "SS" => "Digunakan untuk kunjungan pendek", "VR" => "Digunakan untuk kunjungan dimana pasien dan tenaga kesehatan tidak berada dalam satu tempat, seperti telefon, email, chat, televideo konferensi"];

    public const PRIORITY_SYSTEM = 'http://terminology.hl7.org/CodeSystem/v3-ActPriority';
    public const PRIORITY_CODE = ['A', 'CR', 'CS', 'CSP', 'CSR', 'EL', 'EM', 'P', 'PRN', 'R', 'RR', 'S', 'T', 'UD', 'UR'];
    public const PRIORITY_DISPLAY = ['A' => 'ASAP', 'CR' => 'callback results', 'CS' => 'callback for scheduling', 'CSP' => 'callback placer for scheduling', 'CSR' => 'contact recipient for scheduling', 'EL' => 'elective', 'EM' => 'emergency', 'P' => 'preop', 'PRN' => 'as needed', 'R' => 'routine', 'RR' => 'rush reporting', 'S' => 'stat', 'T' => 'timing critical', 'UD' => 'use as directed', 'UR' => 'urgent'];

    public const DISCHARGE_DISPOSITION_SYSTEM = ['home' => 'http://terminology.hl7.org/CodeSystem/discharge-disposition', 'alt-home' => 'http://terminology.hl7.org/CodeSystem/discharge-disposition', 'other-hcf' => 'http://terminology.hl7.org/CodeSystem/discharge-disposition', 'hosp' => 'http://terminology.hl7.org/CodeSystem/discharge-disposition', 'long' => 'http://terminology.hl7.org/CodeSystem/discharge-disposition', 'aadvice' => 'http://terminology.hl7.org/CodeSystem/discharge-disposition', 'exp' => 'http://terminology.hl7.org/CodeSystem/discharge-disposition', 'psy' => 'http://terminology.hl7.org/CodeSystem/discharge-disposition', 'rehab' => 'http://terminology.hl7.org/CodeSystem/discharge-disposition', 'snf' => 'http://terminology.hl7.org/CodeSystem/discharge-disposition', 'oth' => 'http://terminology.hl7.org/CodeSystem/discharge-disposition', 'exp-lt48h' => 'http://terminology.kemkes.go.id/CodeSystem/discharge-disposition', 'exp-gt48h' => 'http://terminology.kemkes.go.id/CodeSystem/discharge-disposition'];
    public const DISCHARGE_DISPOSITION_CODE = ['home', 'alt-home', 'other-hcf', 'hosp', 'long', 'aadvice', 'exp', 'psy', 'rehab', 'snf', 'oth', 'exp-lt48h', 'exp-gt48h'];
    public const DISCHARGE_DISPOSITION_DISPLAY = ['home' => 'Home', 'alt-home' => 'Alternative home', 'other-hcf' => 'Other healthcarefacility', 'hosp' => 'Hospice', 'long' => 'Long-term care', 'aadvice' => 'Left against advice', 'exp' => 'Expired', 'psy' => 'Psychiatric hospital', 'rehab' => 'Rehabilitation', 'snf' => 'Skilled nursing facility', 'oth' => 'Other', 'exp-lt48h' => 'Meninggal < 48 jam', 'exp-gt48h' => 'Meninggal > 48 jam'];
    public const DISCHARGE_DISPOSITION_DEFINITION = ["home" => "Pasien dipulangkan dan terindikasi akan pulang ke rumahsendiri setelahnya", "alt-home" => "Pasien dipulangkan dan terindikasi ke rumah tetapi bukan rumahnya sendiri", "other-hcf" => "Pasien dirujuk ke fasilitas pelayanan kesehatan lainnya", "hosp" => "Pasien dipulangkan ke layanan paliatif", "long" => "Pasien dipulangkan ke long-term care dimana akan di monitor secara terus menerus dalam suatu episode perawatan", "aadvice" => "Pasien pulang atas permintaan sendiri atau tidak sesuai dengan saran medis", "exp" => "Pasien meninggal saat kunjungan terjadi", "psy" => "Pasien dipindahkan kefasilitas psikiatri", "rehab" => "Pasien dipulangkan dan mendapatkanlayanan rehabilitasi", "snf" => "Pasien dipulangkan ke fasilitas keperawatan untuk mendapatkan layanan tambahan", "oth" => "Kepulangan belum terdefinisi di tempat lain", "exp-lt48h" => "null", "exp-gt48h" => "null"];

    public const SERVICE_CLASS_SYSTEM = ['1' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient', '2' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient', '3' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient', 'vip' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient', 'vvip' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient', 'reguler' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Outpatient', 'eksekutif' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Outpatient'];
    public const SERVICE_CLASS_CODE = ['1', '2', '3', 'vip', 'vvip', 'reguler', 'eksekutif'];
    public const SERVICE_CLASS_DISPLAY = ['1' => 'Kelas 1', '2' => 'Kelas 2', '3' => 'Kelas 3', 'vip' => 'Kelas VIP', 'vvip' => 'Kelas VVIP', 'reguler' => 'Kelas Reguler', 'eksekutif' => 'Kelas Eksekutif'];
    public const SERVICE_CLASS_DEFINITION = ["1" => "Perawatan Kelas 1", "2" => "Perawatan Kelas 2", "3" => "Perawatan Kelas 3", "vip" => "Perawatan Kelas VIP", "vvip" => "Perawatan Kelas VVIP", "reguler" => "Perawatan Kelas Reguler", "eksekutif" => "Perawatan Kelas Eksekutif"];

    public const UPGRADE_CLASS_SYSTEM = 'http://terminology.kemkes.go.id/CodeSystem/locationUpgradeClass';
    public const UPGRADE_CLASS_CODE = ['kelas-tetap', 'naik-kelas', 'turun-kelas', 'titip-rawat'];
    public const UPGRADE_CLASS_DISPLAY = ['kelas-tetap' => 'Kelas Tetap Perawatan', 'naik-kelas' => 'Kenaikan Kelas Perawatan', 'turun-kelas' => 'Penurunan Kelas Perawatan', 'titip-rawat' => 'Titip Kelas Perawatan'];
    public const UPGRADE_CLASS_DEFINITION = ["kelas-tetap" => "Pasien memiliki Kelas Perawatan yang sama dengan Hak Kelas Perawatan yang dimiliki", "naik-kelas" => "Pasien memiliki Kelas Perawatan yang lebih Tinggi dari Hak Kelas Perawatan yang dimiliki berdasarkan pengajuan dari pasien", "turun-kelas" => "Pasien memiliki Kelas Perawatan yang lebihRendah dari Hak Kelas Perawatan yang dimiliki berdasarkan pengajuan dari pasien", "titip-rawat" => "Pasien memiliki Kelas Perawatan yang berbeda dengan Hak Kelas Perawatan yang dimiliki karena ketidaktersediaan ruangan yang sesuai dengan Hak Kelasnya"];

    protected $table = 'encounter';
    protected $casts = [
        'period_start' => 'datetime',
        'period_end' => 'datetime'
    ];
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $with = ['identifier', 'statusHistory', 'classHistory', 'participant', 'reason', 'diagnosis', 'hospitalization'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(EncounterIdentifier::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(EncounterStatusHistory::class);
    }

    public function classHistory(): HasMany
    {
        return $this->hasMany(EncounterClassHistory::class);
    }

    public function participant(): HasMany
    {
        return $this->hasMany(EncounterParticipant::class);
    }

    public function reason(): HasMany
    {
        return $this->hasMany(EncounterReason::class);
    }

    public function diagnosis(): HasMany
    {
        return $this->hasMany(EncounterDiagnosis::class);
    }

    public function hospitalization(): HasMany
    {
        return $this->hasMany(EncounterHospitalization::class);
    }
}
