<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicationDispenseSubstitution extends Model
{
    public const TYPE_SYSTEM = 'http://terminology.hl7.org/CodeSystem/v3-substanceAdminSubstitution';
    public const TYPE_CODE = ['(_ActSubstanceAdminSubstitutionCode) Abstract', 'E', 'EC', 'BC', 'G', 'TE', 'TB', 'TG', 'F', 'N'];
    public const TYPE_DISPLAY = ["(_ActSubstanceAdminSubstitutionCode) Abstract" => "null", "E" => "equivalent", "EC" => "equivalent composition", "BC" => "brand composition", "G" => "generic composition", "TE" => "therapeutic alternative", "TB" => "therapeutic brand", "TG" => "therapeutic generic", "F" => "formulary", "N" => "none"];
    public const TYPE_DEFINITION = ["(_ActSubstanceAdminSubstitutionCode) Abstract" => "Substitusi terjadi atau diperbolehkan dengan produk lain yang kemungkinan memiliki perbedaan kandungan zat tetapi memiliki efek biologis dan terapetik yang sama", "E" => "Substitusi terjadi atau diperbolehkan dengan produk lain yang bioekivalen dan efek terapi sama.", "EC" => "Substitusi terjadi atau diperbolehkan dengan produk lain dimana a. Pharmaceutical alternative : memiliki kandungan zat aktif yang sama tetapi berbeda formulasi/bentuk garam. Contoh : Erythromycin Ethylsuccinate dengan Erythromycin Stearate b. Pharmaceutical equivalent : memiliki kandungan zat aktif, kekuatan, dan rute administrasi yang sama. Contoh Lisonpril for Zestril", "BC" => "Substitusi terjadi atau diperbolehkan antara brand yang ekuivalen tetapi bukan generik. Contoh : Zestril dengan Prinivil", "G" => "Substitusi terjadi atau diperbolehkan antara generik yang ekuivalen tetapi bukan brand. Contoh : Lisnopril (Lupin Corp) dengan Lisnopril (Wockhardt Corp)", "TE" => "Substitusi terjadi atau diperbolehkan dengan produk lain yang memiliki tujuan terapetik dan profil keamanan yang sama. Contoh : ranitidine dengan or Tagamet", "TB" => "Substitusi terjadi atau diperbolehkan antara brand dengan efek terapeutik ekuivalen, tetapi bukan generik. Contoh : Zantac for Tagamet", "TG" => "Substitusi terjadi atau diperbolehkan antara generik dengan efek terapeutik ekuivalen, tetapi bukan brand. Contoh : Ranitidine for cimetidine", "F" => "Substitusi terjadi atau diperbolehkan berdasarkan pedoman formularium", "N" => "Substitusi tidak terjadi atau diperbolehkan"];

    public const REASON_SYSTEM = 'https://terminology.hl7.org/3.1.0/CodeSystem-v3-ActReason.html';
    public const REASON_CODE = ['CT', 'FP', 'OS', 'RR'];
    public const REASON_DISPLAY = ["CT" => "Continuing therapy", "FP" => "Formulary policy", "OS" => "Out of stock", "RR" => "Regulatory requirement"];
    public const REASON_DEFINITION = ["CT" => "Mengindikasikan bahwa keputusan untuk mengganti/tidak mengganti didasari oleh keinginan untuk menjaga konsistensi terapi pre-existing. pe", "FP" => "Mengindikasikan bahwa keputusan untuk mengganti/tidak mengganti didasari oleh kebijakan dalam formularium", "OS" => "Mengindikasikan penggantian terjadi karena persediaan obat yang diminta tidak ada atau tidak diganti apabila obat yang direncanakan sebagai pengganti tidak ada stok", "RR" => "Mengindikasikan keputusan untuk mengganti/tidak mengganti didasari oleh persyaratan regulasi yuridis yang mengamanatkan atau melarang substitusi"];

    protected $table = 'medication_dispense_substitution';
    protected $casts = ['was_substituted' => 'boolean'];
    public $timestamps = false;

    public function dispense(): BelongsTo
    {
        return $this->belongsTo(MedicationDispense::class, 'dispense_id');
    }

    public function reason(): HasMany
    {
        return $this->hasMany(MedicationDispenseSubstitutionReason::class, 'med_disp_subs_id');
    }

    public function responsibleParty(): HasMany
    {
        return $this->hasMany(MedicationDispenseSubstitutionResponsibleParty::class, 'med_disp_subs_id');
    }
}
