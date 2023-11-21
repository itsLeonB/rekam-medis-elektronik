<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicationRequest extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($medicationRequest) {
            $orgId = config('app.organization_id');

            $identifier = new MedicationRequestIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/prescription/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $medicationRequest->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $medicationRequest->identifier()->save($identifier);
        });
    }

    public const STATUS_SYSTEM = 'http://hl7.org/fhir/CodeSystem/medicationrequest-status';
    public const STATUS_CODE = ['active', 'on-hold', 'cancelled', 'completed', 'entered-in-error', 'stopped', 'draft', 'unknown'];
    public const STATUS_DISPLAY = ["active" => "Aktif", "on-hold" => "Tertahan", "cancelled" => "Dibatalkan", "completed" => "Komplit", "entered-in-error" => "Salah", "stopped" => "Dihentikan", "draft" => "Draft/butuh verifikasi", "unknown" => "Tidak diketahui"];

    public const STATUS_REASON_SYSTEM = 'http://terminology.hl7.org/CodeSystem/medicationrequest-status-reason';
    public const STATUS_REASON_CODE = ['altchoice', 'clarif', 'drughigh', 'hospadm', 'labint', 'non-avail', 'preg', 'salg', 'sddi', 'sdupther', 'sintol', 'surg', 'washout'];
    public const STATUS_REASON_DISPLAY = ["altchoice" => "Try another treatment first", "clarif" => "Prescription requires clarification", "drughigh" => "Drug level too high", "hospadm" => "Admission to hospital", "labint" => "Lab interference issues", "non-avail" => "Patient not available", "preg" => "Parent is pregnant/breast feeding", "salg" => "Allergy", "sddi" => "Drug interacts with another drug", "sdupther" => "Duplicate therapy", "sintol" => "Suspected intolerance", "surg" => "Patient scheduled for surgery.", "washout" => "Waiting for old drug to wash out"];

    public const INTENT_SYSTEM = 'http://hl7.org/fhir/CodeSystem/medicationrequest-intent';
    public const INTENT_CODE = ['proposal', 'plan', 'order', 'original-order', 'reflex-order', 'filler-order', 'instance-order', 'unknown'];
    public const INTENT_DISPLAY = ["proposal" => "Permintaan yang diusulkan oleh seseorang yang bertujuan untuk menjamin pengobatan dilakukan tanpa memerlukan hak untuk bertindak", "plan" => "Permintaan yang menggambarkan tujuan untuk menjamin pengobatan dilakukan tanpa memberikan hak yang lain untuk bertindak", "order" => "Permintaan yang menunjukkan kebutuhan dan hak untuk bertindak", "original-order" => "Permintaan yang menggambarkan hak asli untuk meminta pengobatan", "reflex-order" => "Permintaan yang menggambarkan hak tambahan yang dibuat untuk tindakan berdasarkan otorisasi bersama dengan hasil awal tindakan yang merujuk pada otorisasi tersebut", "filler-order" => "Permintaan tersebut mewakili pandangan otorisasi yang dibuat oleh sistem pemenuhan yang mewakili rincian niat pemenuhan untuk bertindak atas permintaan yang diajukan", "instance-order" => "Permintaan yang menggambarkan contoh tertentu, misal catatan pemberian obat", "unknown" => "Permintaan yang menggambarkan opsi untuk RequestGroup"];

    public const PRIORITY_SYSTEM = 'http://hl7.org/fhir/request-priority';
    public const PRIORITY_CODE = ['routine', 'urgent', 'asap', 'stat'];
    public const PRIORITY_DISPLAY = ["routine" => "Permintaan prioritas normal", "urgent" => "Permintaan yang harus dilakukan segera ditindaklanjuti/lebih prioritas daripada Routine", "asap" => "Permintaan yang harus dilakukan sesegera mungkin/lebih prioritas daripada Urgent", "stat" => "Permintaan yang harus dilakukan diberikan saat itu juga/lebih prioritas daripada ASAP"];

    public const COURSE_OF_THERAPY_SYSTEM = 'https://hl7.org/FHIR/codesystem-medicationrequest-course-of-therapy.html';
    public const COURSE_OF_THERAPY_CODE = ['continuous', 'acute', 'seasonal'];
    public const COURSE_OF_THERAPY_DISPLAY = ["continuous" => "Continuing long term therapy", "acute" => "Short course (acute) therapy", "seasonal" => "Seasonal"];
    public const COURSE_OF_THERAPY_DEFINITION = ["continuous" => "Pengobatan yang diharapkan berlanjut hingga permintaan selanjutnya dan pasien harus diasumsikan mengonsumsinya kecuali jika dihentikan secara eksplisit", "acute" => "Pengobatan pasien yang diharapkan dikonsumsi pada durasi pemberian tertentu dan tidak diberikan lagi", "seasonal" => "Pengobatan yang diharapkan digunakan pada waktu tertentu pada waktu yang telah dijadwalkan dalam setahun"];

    public const DISPENSE_SYSTEM = 'http://unitsofmeasure.org';
    public const DISPENSE_CODE = ['ms', 's', 'min', 'h', 'd', 'wk', 'mo', 'a'];
    public const DISPENSE_DISPLAY = ["ms" => "milliseconds", "s" => "seconds", "min" => "minutes", "h" => "hours", "d" => "days", "wk" => "weeks", "mo" => "months", "a" => "years"];

    public const SUBSTITUTION_ALLOWED_SYSTEM = 'http://terminology.hl7.org/CodeSystem/v3-substanceAdminSubstitution';
    public const SUBSTITUTION_ALLOWED_CODE = ['(_ActSubstanceAdminSubstitutionCode) Abstract', 'E', 'EC', 'BC', 'G', 'TE', 'TB', 'TG', 'F', 'N'];
    public const SUBSTITUTION_ALLOWED_DISPLAY = ["(_ActSubstanceAdminSubstitutionCode) Abstract" => "null", "E" => "equivalent", "EC" => "equivalent composition", "BC" => "brand composition", "G" => "generic composition", "TE" => "therapeutic alternative", "TB" => "therapeutic brand", "TG" => "therapeutic generic", "F" => "formulary", "N" => "none"];
    public const SUBSTITUTION_ALLOWED_DEFINITION = ["(_ActSubstanceAdminSubstitutionCode) Abstract" => "Substitusi terjadi atau diperbolehkan dengan produk lain yang kemungkinan memiliki perbedaan kandungan zat tetapi memiliki efek biologis dan terapetik yang sama", "E" => "Substitusi terjadi atau diperbolehkan dengan produk lain yang bioekivalen dan efek terapi sama.", "EC" => "Substitusi terjadi atau diperbolehkan dengan produk lain dimana a. Pharmaceutical alternative : memiliki kandungan zat aktif yang sama tetapi berbeda formulasi/bentuk garam. Contoh : Erythromycin Ethylsuccinate dengan Erythromycin Stearate b. Pharmaceutical equivalent : memiliki kandungan zat aktif, kekuatan, dan rute administrasi yang sama. Contoh Lisonpril for Zestril", "BC" => "Substitusi terjadi atau diperbolehkan antara brand yang ekuivalen tetapi bukan generik. Contoh : Zestril dengan Prinivil", "G" => "Substitusi terjadi atau diperbolehkan antara generik yang ekuivalen tetapi bukan brand. Contoh : Lisnopril (Lupin Corp) dengan Lisnopril (Wockhardt Corp)", "TE" => "Substitusi terjadi atau diperbolehkan dengan produk lain yang memiliki tujuan terapetik dan profil keamanan yang sama. Contoh : ranitidine dengan or Tagamet", "TB" => "Substitusi terjadi atau diperbolehkan antara brand dengan efek terapeutik ekuivalen, tetapi bukan generik. Contoh : Zantac for Tagamet", "TG" => "Substitusi terjadi atau diperbolehkan antara generik dengan efek terapeutik ekuivalen, tetapi bukan brand. Contoh : Ranitidine for cimetidine", "F" => "Substitusi terjadi atau diperbolehkan berdasarkan pedoman formularium", "N" => "Substitusi tidak terjadi atau diperbolehkan"];

    public const SUBSTITUTION_REASON_SYSTEM = 'https://terminology.hl7.org/3.1.0/CodeSystem-v3-ActReason.html';
    public const SUBSTITUTION_REASON_CODE = ['CT', 'FP', 'OS', 'RR'];
    public const SUBSTITUTION_REASON_DISPLAY = ["CT" => "Continuing therapy", "FP" => "Formulary policy", "OS" => "Out of stock", "RR" => "Regulatory requirement"];
    public const SUBSTITUTION_REASON_DEFINITION = ["CT" => "Mengindikasikan bahwa keputusan untuk mengganti/tidak mengganti didasari oleh keinginan untuk menjaga konsistensi terapi pre-existing. pe", "FP" => "Mengindikasikan bahwa keputusan untuk mengganti/tidak mengganti didasari oleh kebijakan dalam formularium", "OS" => "Mengindikasikan penggantian terjadi karena persediaan obat yang diminta tidak ada atau tidak diganti apabila obat yang direncanakan sebagai pengganti tidak ada stok", "RR" => "Mengindikasikan keputusan untuk mengganti/tidak mengganti didasari oleh persyaratan regulasi yuridis yang mengamanatkan atau melarang substitusi"];

    protected $table = 'medication_request';
    protected $casts = [
        'do_not_perform' => 'boolean',
        'reported' => 'boolean',
        'authored_on' => 'datetime',
        'dispense_interval_value' => 'decimal:2',
        'validity_period_start' => 'datetime',
        'validity_period_end' => 'datetime',
        'quantity_value' => 'decimal:2',
        'supply_duration_value' => 'decimal:2',
        'substitution_allowed' => 'array'
    ];
    public $timestamps = false;
    protected $with = ['identifier', 'category', 'reason', 'basedOn', 'insurance', 'note', 'dosage'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(MedicationRequestIdentifier::class, 'med_req_id');
    }

    public function category(): HasMany
    {
        return $this->hasMany(MedicationRequestCategory::class, 'med_req_id');
    }

    public function reason(): HasMany
    {
        return $this->hasMany(MedicationRequestReason::class, 'med_req_id');
    }

    public function basedOn(): HasMany
    {
        return $this->hasMany(MedicationRequestBasedOn::class, 'med_req_id');
    }

    public function insurance(): HasMany
    {
        return $this->hasMany(MedicationRequestInsurance::class, 'med_req_id');
    }

    public function note(): HasMany
    {
        return $this->hasMany(MedicationRequestNote::class, 'med_req_id');
    }

    public function dosage(): HasMany
    {
        return $this->hasMany(MedicationRequestDosage::class, 'med_req_id');
    }
}
