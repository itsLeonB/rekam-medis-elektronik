<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncounterParticipant extends Model
{
    public const TYPE_SYSTEM = ['ADM' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType', 'ATND' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType', 'CALLBCK' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType', 'CON' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType', 'DIS' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType', 'ESC' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType', 'REF' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType', 'SPRF' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType', 'PPRF' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType', 'PART' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType', 'translator' => 'http://terminology.hl7.org/CodeSystem/participant-type', 'emergency' => 'http://terminology.hl7.org/CodeSystem/participant-type'];
    public const TYPE_CODE = ['ADM', 'ATND', 'CALLBCK', 'CON', 'DIS', 'ESC', 'REF', 'SPRF', 'PPRF', 'PART', 'translator', 'emergency'];
    public const TYPE_DISPLAY = ['ADM' => 'admitter', 'ATND' => 'attender', 'CALLBCK' => 'callback contact', 'CON' => 'consultant', 'DIS' => 'discharger', 'ESC' => 'escort', 'REF' => 'referrer', 'SPRF' => 'secondaryperformer', 'PPRF' => 'primary performer', 'PART' => 'Participation', 'translator' => 'Translator', 'emergency' => 'Emergency'];
    public const TYPE_DEFINITION = ["ADM" => "Tenaga kesehatan yang berperan memasukkan pasien ke dalam suatu kunjungan", "ATND" => "Tenaga kesehatan yang bertanggung jawab untuk mengawasi perawatan pasien selama kunjungan", "CALLBCK" => "Seseorang atau organisasi yang dapat dikontak untuk pertanyaan tidak lanjut", "CON" => "Penasihat berpartisipasi dalam layanan dengan melakukan evaluasi dan membuat rekomendasi.", "DIS" => "Tenaga kesehatan yang berperan dalam discharge atau memulangkan seorang pasien.", "ESC" => "Hanya dengan jasa Transportasi. Orang yang mengantar pasien.", "REF" => "Seseorang yang merujuk subjek layanan kepada pelaku (dokter perujuk). Biasanya, dokter yang merujuk akan menerima laporan.", "SPRF" => "Seseorang yang membantu dalam suatu tindakan melalui kehadiran dan keterlibatannya yang substansial Ini termasuk: asisten, teknisi, rekanan, atau apa pun jabatannya.", "PPRF" => "Pelaku utama dari tindakan tersebut.", "PART" => "Menunjukkan bahwa seorang individu terlibat dalam suatu perbuatan, tetapi tidak memenuhi syarat yang jelas.", "translator" => "Seorang penerjemah yang memfasilitasi komunikasi dengan pasien selama pertemuan.", "emergency" => "Seseorang yang dapat dihubungi dalam keadaan darurat selama kunjungan terjadi"];

    protected $table = 'encounter_participant';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }
}
