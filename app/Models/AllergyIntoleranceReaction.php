<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AllergyIntoleranceReaction extends Model
{
    // Constants
    public const SUBSTANCE_SYSTEM = [
        'Kode KFA' => 'Kode KFA digunakan apabila alergen berupa obat dan vaksin (Kode bahan zat aktif (BZA), produk obat virtual (POV), produk obat aktual (POA)). Kode KFA dapat dilihat dalam link berikut : https://dto.kemkes.go.id/kfa-browser',
        'WHO ATC' => 'Kode WHO Anatomical Therapeutic Chemical Codes (ATC) pada level 3 dan atau 4 digunakan apabila dibutuhkan informasi alergi terhadap golongan obat',
        'SNOMED CT' => 'Kode dari SNOMED CT digunakan untuk jenis alergen makanan, lingkungan, dan kondisi tidak diketahui alergi yang dimiliki. Ruang lingkup kode SNOMED CT yang dapat dipakai Expression Constraint Language (ECL) Query : ( < 105590001 |Substance (substance)| MINUS ( << 410942007 |Drug or medicament (substance)| OR (<< 787859002 |Vaccine product (medicinal product)| . 127489000 |Has active ingredient|) ) ) OR (<< 716186003 |No known allergy (situation)| : { 408729009 |Finding context (attribute)| = 410516002 |Known absent (qualifier value)| })'
    ];
    public const SEVERITY_SYSTEM = 'http://hl7.org/fhir/reaction-event-severity';
    public const SEVERITY_CODE = ['mild', 'moderate', 'severe'];
    public const SEVERITY_DEFINITION = [
        'mild' => 'Menyebabkan efek fisiologis ringan',
        'moderate' => 'Menyebabkan efek fisiologis sedang',
        'severe' => 'Menyebabkan efek fisiologis berat'
    ];

    protected $table = 'allergy_intolerance_reaction';
    protected $casts = ['onset' => 'datetime'];
    public $timestamps = false;

    public function allergy(): BelongsTo
    {
        return $this->belongsTo(AllergyIntolerance::class, 'allergy_id');
    }

    public function manifestation(): HasMany
    {
        return $this->hasMany(AllergyIntoleranceReactionManifestation::class, 'allergy_reaction_id');
    }

    public function note(): HasMany
    {
        return $this->hasMany(AllergyIntoleranceReactionNote::class, 'allergy_reaction_id');
    }
}
