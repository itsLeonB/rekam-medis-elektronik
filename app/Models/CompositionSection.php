<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompositionSection extends Model
{
    public const CODE_SYSTEM = 'http://loinc.org';
    public const CODE_CODE = ['10154-3', '10157-6', '10160-0', '10164-2', '10183-2', '10184-0', '10187-3', '10210-3', '10216-0', '10218-6', '10223-6', '10222-8', '11329-0', '11348-0', '11369-6', '57852-6', '11493-4', '11535-2', '11537-8', '18776-5', '18841-7', '29299-5', '29545-1', '29549-3', '29554-3', '29762-2', '30954-2', '42344-2', '42346-7', '42348-3', '42349-1', '46240-8', '46241-6', '46264-8', '47420-5', '47519-4', '48765-2', '48768-6', '51848-0', '55109-3', '55122-6', '59768-2', '59769-0', '59770-8', '59771-6', '59772-4', '59773-2', '59775-7', '59776-5', '61149-1', '61150-9', '69730-0', '8648-8', '8653-8', '8716-3'];
    public const CODE_DISPLAY = ["10154-3" => "Chief complaint Narrative - Reported", "10157-6" => "History of family member diseases Narrative", "10160-0" => "History of medication use Narrative", "10164-2" => "History of present illness Narrative", "10183-2" => "Hospital discharge medications Narrative", "10184-0" => "Hospital discharge physical findings Narrative", "10187-3" => "Review of systems Narrative - Reported", "10210-3" => "Physical findings of General status Narrative", "10216-0" => "Surgical operation note fluids Narrative", "10218-6" => "Surgical operation note postoperative diagnosis Narrative", "10223-6" => "Surgical operation note surgical procedure Narrative", "10222-8" => "Surgical operation note surgical complications [interpretation] Narrative", "11329-0" => "History general Narrative - Reported", "11348-0" => "History of past illness Narrative", "11369-6" => "History of immunization Narrative", "57852-6" => "Problem list Narrative - Reported", "11493-4" => "Hospital discharge studies summary Narrative", "11535-2" => "Hospital discharge Dx Narrative", "11537-8" => "Surgical drains Narrative", "18776-5" => "Plan of treatment (narrative)", "18841-7" => "Hospital consultations Document", "29299-5" => "Reason for visit Narrative", "29545-1" => "Physical findings Narrative", "29549-3" => "Medication administered Narrative", "29554-3" => "Procedure Narrative", "29762-2" => "Social history Narrative", "30954-2" => "Relevant diagnostic tests/laboratory data Narrative", "42344-2" => "Discharge diet (narrative)", "42346-7" => "Medications on admission (narrative)", "42348-3" => "Advance directives (narrative)", "42349-1" => "Reason for referral (narrative)", "46240-8" => "History of hospitalizations+History of outpatient visits Narrative", "46241-6" => "Hospital admission diagnosis Narrative Reported", "46264-8" => "History of medical device use", "47420-5" => "Functional status assessment note", "47519-4" => "History of Procedures Document", "48765-2" => "Allergies and adverse reactions Document", "48768-6" => "Payment sources Document", "51848-0" => "Evaluation note", "55109-3" => "Complications Document", "55122-6" => "Surgical operation note implants Narrative", "59768-2" => "Procedure indications [interpretation] Narrative", "59769-0" => "Postprocedure diagnosis Narrative", "59770-8" => "Procedure estimated blood loss Narrative", "59771-6" => "Procedure implants Narrative", "59772-4" => "Planned procedure Narrative", "59773-2" => "Procedure specimens taken Narrative", "59775-7" => "Procedure disposition Narrative", "59776-5" => "Procedure findings Narrative", "61149-1" => "Objective Narrative", "61150-9" => "Subjective Narrative", "69730-0" => "Instructions", "8648-8" => "Hospital course Narrative", "8653-8" => "Hospital Discharge instructions", "8716-3" => "Vital signs"];

    public const TEXT_STATUS_SYSTEM = 'http://hl7.org/fhir/narrative-status';
    public const TEXT_STATUS_CODE = ['generated', 'extensions', 'additional', 'empty'];
    public const TEXT_STATUS_DISPLAY = ["generated" => "Isi keseluruhan narasi dihasilkan dari elemen inti dalam konten", "extensions" => "Isi keseluruhan narasi dihasilkan dari elemen inti dalam konten dan beberapa konten berasal dari extension. Narasi HARUS merefleksikan dampak dari seluruh modifier extension", "additional" => "Isi narasi dapat berisikan informasi tambahan yang tidak ditemukan dalam struktur data. Perhatikan bahwa tidak ada cara yang dapat dihitung untuk menentukan informasi tambahan kecuali oleh inspeksi seseorang", "empty" => "Isi narasi merupakan beberapa hal yang setara dengan “tidak ada teks yang dapat dibaca yang tersedia dalam kasus ini”"];

    public const MODE_SYSTEM = 'http://hl7.org/fhir/list-mode';
    public const MODE_CODE = ['working', 'snapshot', 'changes'];
    public const MODE_DISPLAY = ["working" => "Daftar ini merupakan daftar utama/master list dimana akan dipelihara dengan pembaruan rutin yang terjadi di dunia nyata", "snapshot" => "Daftar ini disiapkan sebagai snapshot. Tidak boleh dianggap sebagai kondisi saat ini.", "changes" => "Daftar sewaktu yang menunjukkan perubahan telah dibuat atau direkomendasikan. Misalnya. daftar obat keluar yang menunjukkan apa yang ditambahkan dan dihapus selama kunjungan."];

    public const ORDERED_BY_SYSTEM = 'http://terminology.hl7.org/CodeSystem/list-order';
    public const ORDERED_BY_CODE = ['user', 'system', 'event-date', 'entry-date', 'priority', 'alphabetic', 'category', 'patient'];
    public const ORDERED_BY_DISPLAY = ["user" => "Sorted by User", "system" => "Sorted by System", "event-date" => "Sorted by Event Date", "entry-date" => "Sorted by Item Date", "priority" => "Sorted by Priority", "alphabetic" => "Sorted Alphabetically", "category" => "Sorted by Category", "patient" => "Sorted by Patient"];
    public const ORDERED_BY_DEFINITION = ["user" => "Diurutkan berdasarkan User.", "system" => "Diurutkan berdasarkan System.", "event-date" => "Diurutkan berdasarkan Event Date.", "entry-date" => "Diurutkan berdasarkan Item Date.", "priority" => "Diurutkan berdasarkan prioritas.", "alphabetic" => "Diurutkan berdasarkan alfabet.", "category" => "Diurutkan berdasarkan kategori.", "patient" => "Diurutkan berdasarkan pasien."];

    public const EMPTY_REASON_SYSTEM = 'http://terminology.hl7.org/CodeSystem/list-empty-reason';
    public const EMPTY_REASON_CODE = ['nilknown', 'notasked', 'withheld', 'unavailable', 'notstarted', 'closed'];
    public const EMPTY_REASON_DISPLAY = ["nilknown" => "Nil Known", "notasked" => "Not Asked", "withheld" => "Information Withheld", "unavailable" => "Unavailable", "notstarted" => "Not Started", "closed" => "Closed"];
    public const EMPTY_REASON_DEFINITION = ["nilknown" => "Tidak diketahui", "notasked" => "Tidak ditanyakan", "withheld" => "Konten tidak tersedia karena masalah privasi dan kerahasiaan.", "unavailable" => "Informasi tidak tersedia karena tidak bisa didapatkan. Contoh: pasien tidak sadarkan diri", "notstarted" => "Langkah untuk melengkapi informasi belum dimulai", "closed" => "Daftar sudah ditutup atau sudah tidak relevan"];

    protected $table = 'composition_section';
    public $timestamps = false;

    public function composition(): BelongsTo
    {
        return $this->belongsTo(Composition::class);
    }

    public function author(): HasMany
    {
        return $this->hasMany(CompositionSectionAuthor::class, 'composition_section_id');
    }

    public function entry(): HasMany
    {
        return $this->hasMany(CompositionSectionEntry::class, 'composition_section_id');
    }
}
