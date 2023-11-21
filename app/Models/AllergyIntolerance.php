<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Config;

class AllergyIntolerance extends Model
{
    // Constants
    public const CLINICAL_STATUS_SYSTEM = 'http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical';
    public const CLINICAL_STATUS_CODE = ['active', 'inactive', 'resolved'];
    public const CLINICAL_STATUS_DISPLAY_DEFINITION = [
        'active' => [
            'display' => 'Active',
            'definition' => 'Subjek saat ini mengalami atau dalam risiko reaksi terhadap suatu zat'
        ],
        'inactive' => [
            'display' => 'Inactive',
            'definition' => 'Subjek saat ini tidak berisiko reaksi terhadap suatu zat'
        ],
        'resolved' => [
            'display' => 'Resolved',
            'definition' => 'Reaksi pada zat telah dikaji ulang secara klinis melalui pengujian atau paparan ulang dan dianggap sudah tidak ada lagi. Paparan ulang dapat bersifat tidak sengaja, tidak terencana, atau di luar dari tatanan klinis'
        ],
    ];
    public const VERIFICATION_STATUS_SYSTEM = 'http://terminology.hl7.org/CodeSystem/allergyintolerance-verification';
    public const VERIFICATION_STATUS_CODE = ['unconfirmed', 'confirmed', 'refuted', 'entered-in-error'];
    public const VERIFICATION_STATUS_DISPLAY_DEFINITION = [
        'unconfirmed' => [
            'display' => 'Unconfirmed',
            'definition' => 'Belum terkonfirmasi secara klinis. Tingkat kepastian rendah tentang kecenderungan reaksi terhadap suatu zat.'
        ],
        'confirmed' => [
            'display' => 'Confirmed',
            'definition' => 'Terkonfirmasi secara klinis. Tingkat kepastian yang tinggi tentang kecenderungan reaksi pada suatu zat yang dapat dibuktikan secara klinis melalui tes atau rechallenge'
        ],
        'refuted' => [
            'display' => 'Refuted',
            'definition' => 'Disangkal atau tidak terbukti. Reaksi terhadap suatu zat disangkal atau tidak terbukti berdasarkan bukti klinis. Hal ini dapat termasuk/tidak termasuk pengujian'
        ],
        'entered-in-error' => [
            'display' => 'Entered In Error',
            'definition' => 'Pernyataan yang dimasukkan sebagai error atau tidak valid'
        ]
    ];
    public const TYPE_SYSTEM = 'http://hl7.org/fhir/allergy-intolerance-type';
    public const TYPE_CODE = ['allergy', 'intolerance'];
    public const TYPE_DEFINITION = [
        'allergy' => 'Kecenderungan reaksi hipersensitif pada zat tertentu yang seringnya disebabkan oleh hipersensitivitas tipe I ditambah reaksi seperti alergi lain, termasuk pseudoallergy',
        'intolerance' => 'Kecenderungan reaksi tidak diinginkan terhadap suatu zat yang tidak diidentifikasi sebagai alergi atau reaksi seperti alergi. Reaksi ini terkait non-imun dan terdapat beberapa derajat idiosinkratik dan/atau spesifik pada pasien'
    ];
    public const CATEGORY_SYSTEM = 'http://hl7.org/fhir/allergy-intolerance-category';
    public const CATEGORY_CODE = ['food', 'medication', 'environment', 'biologic'];
    public const CATEGORY_DEFINITION = [
        'food' => 'Segala zat atau substansi yang dikonsumsi untuk nutrisi bagi tubuh',
        'medication' => 'Substansi yang diberikan untuk mencapai efek fisiologis (Obat)',
        'environment' => 'Setiap substansi yang berasal atau ditemukan dari lingkungan, termasuk substansi yang tidak dikategorikan sebagai makanan, medikasi/obat, dan biologis',
        'biologic' => 'Sediaan yang disintesis dari organisme hidup atau produknya, terutama manusia atau protein hewan, seperti hormon atau antitoksin, yang digunakan sebagai agen diagnostik, preventif, atau terapeutik. Contoh obat biologis meliputi: vaksin; ekstrak alergi, yang digunakan untuk diagnosis dan pengobatan (misalnya, suntikan alergi); terapi gen; terapi seluler. Ada produk biologis lain, seperti jaringan, yang biasanya tidak terkait dengan alergi.'
    ];
    public const CRITICALITY_SYSTEM = 'http://hl7.org/fhir/allergy-intolerance-criticality';
    public const CRITICALITY_CODE = ['low', 'high', 'unable-to-assess'];
    public const CRITICALITY_DEFINITION = [
        'low' => 'Tidak mengancam jiwa atau berpotensi tinggi untuk kegagalan sistem organ',
        'high' => 'Mengancam jiwa atau berpotensi menyebabkan kegagalan sistem organ',
        'unable-to-assess' => 'Tidak dapat dikaji potensi bahaya klinis pada paparan mendatang'
    ];
    public const CODE_SYSTEM = [
        'Kode KFA' => 'Kode KFA digunakan apabila alergen berupa obat dan vaksin (Kode bahan zat aktif (BZA), produk obat virtual (POV), produk obat aktual (POA)). Kode KFA dapat dilihat dalam link berikut : https://dto.kemkes.go.id/kfa-browser',
        'WHO ATC' => 'Kode WHO Anatomical Therapeutic Chemical Codes (ATC) pada level 3 dan atau 4 digunakan apabila dibutuhkan informasi alergi terhadap golongan obat',
        'SNOMED CT' => 'Kode dari SNOMED CT digunakan untuk jenis alergen makanan, lingkungan, dan kondisi tidak diketahui alergi yang dimiliki. Ruang lingkup kode SNOMED CT yang dapat dipakai : Expression Constraint Language (ECL) Query : ( < 105590001 |Substance (substance)| MINUS ( << 410942007 |Drug or medicament (substance)| OR (<< 787859002 |Vaccine product (medicinal product)| . 127489000 |Has active ingredient|) ) ) OR (<< 716186003 |No known allergy (situation)| : { 408729009 |Finding context (attribute)| = 410516002 |Known absent (qualifier value)| })'
    ];

    protected $table = 'allergy_intolerance';
    protected $casts = [
        'onset' => 'array',
        'recorded_date' => 'datetime',
        'last_occurence' => 'datetime',
        'category_food' => 'boolean',
        'category_medication' => 'boolean',
        'category_environment'  => 'boolean',
        'category_biologic' => 'boolean'
    ];
    protected $guarded = ['id'];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(AllergyIntoleranceIdentifier::class, 'allergy_id');
    }

    public function note(): HasMany
    {
        return $this->hasMany(AllergyIntoleranceNote::class, 'allergy_id');
    }

    public function reaction(): HasMany
    {
        return $this->hasMany(AllergyIntoleranceReaction::class, 'allergy_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::created(function ($allergyIntolerance) {
            $orgId = Config::get('app.organization_id');

            $identifier = new AllergyIntoleranceIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/allergy/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $allergyIntolerance->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $allergyIntolerance->identifier()->save($identifier);
        });
    }
}
