<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Composition extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($composition) {
            $orgId = config('organization_id');
            $composition->identifier_system = 'http://sys-ids.kemkes.go.id/composition/' . $orgId;
            $composition->identifier_use = 'official';
            $composition->identifier_value = $composition->max('identifier_value') + 1;
        });
    }

    public const STATUS_SYSTEM = 'http://hl7.org/fhir/composition-status';
    public const STATUS_CODE = ['preliminary', 'final', 'amended', 'entered-in-error'];
    public const STATUS_DISPLAY = ["preliminary" => "Dokumen initial atau interim. Konten masih belum lengkap atau belum terverifikasi", "final" => "Versi dokumen sudah komplit dan diverifikasi", "amended" => "Konten dimodifikasi setelah status “final”", "entered-in-error" => "Konten error, bisa dianggap tidak valid"];

    public const CONFIDENTIALITY_SYSTEM = 'http://terminology.hl7.org/CodeSystem/v3-Confidentiality';
    public const CONFIDENTIALITY_CODE = ['U', 'L', 'M', 'N', 'R', 'V'];
    public const CONFIDENTIALITY_DISPLAY = ["U" => "unrestricted", "L" => "low", "M" => "moderate", "N" => "normal", "R" => "restricted", "V" => "very restricted"];
    public const CONFIDENTIALITY_DEFINITION = ["U" => "Informasi tidak diklasifikasikan sebagai sensitif.", "L" => "Informasi telah dide-identifikasi dan sudah ada langkah mitigasi untuk mencegah reidentifikasi. Informasi memerlukan proteksi dengan tingkat sensitivitas rendah.", "M" => "Informasi dengan tingkat sensitivitas menengah.", "N" => "Informasi tipikal, informasi kesehatan yang tidak menimbulkan stigma.", "R" => "Informasi dengan tingkat sensitivitas tinggi, berpotensi menimbulkan stigma.", "V" => "Informasi dengan tingkat sensitivitas sangat tinggi dan menimbulkan stigma."];

    protected $table = 'composition';
    protected $casts = ['date' => 'datetime'];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function category(): HasMany
    {
        return $this->hasMany(CompositionCategory::class);
    }

    public function author(): HasMany
    {
        return $this->hasMany(CompositionAuthor::class);
    }

    public function attester(): HasMany
    {
        return $this->hasMany(CompositionAttester::class);
    }

    public function relatesTo(): HasMany
    {
        return $this->hasMany(CompositionRelatesTo::class);
    }

    public function event(): HasMany
    {
        return $this->hasMany(CompositionEvent::class);
    }

    public function section(): HasMany
    {
        return $this->hasMany(CompositionSection::class);
    }
}
