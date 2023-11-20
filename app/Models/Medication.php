<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medication extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($medication) {
            $orgId = config('organization_id');

            $identifier = new MedicationIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/medication/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $medication->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $medication->identifier()->save($identifier);
        });
    }

    public const STATUS_SYSTEM = 'http://hl7.org/fhir/CodeSystem/medication-status';
    public const STATUS_CODE = ['active', 'inactive', 'entered-in-error'];
    public const STATUS_DISPLAY = ["active" => "Obat tersedia untuk digunakan", "inactive" => "Obat tidak tersedia", "entered-in-error" => "Obat yang dimasukkan salah"];

    public const FORM_SYSTEM = 'http://terminology.kemkes.go.id/CodeSystem/medication-form';
    public const FORM_CODE = ['BS001', 'BS002', 'BS003', 'BS004', 'BS005', 'BS006', 'BS007', 'BS008', 'BS009', 'BS010', 'BS011', 'BS012', 'BS013', 'BS014', 'BS015', 'BS016', 'BS017', 'BS018', 'BS019', 'BS020', 'BS021', 'BS022', 'BS023', 'BS024', 'BS025', 'BS026', 'BS027', 'BS028', 'BS029', 'BS030', 'BS031', 'BS032', 'BS033', 'BS034', 'BS035', 'BS036', 'BS037', 'BS038', 'BS039', 'BS040', 'BS041', 'BS042', 'BS043', 'BS044', 'BS045', 'BS046', 'BS047', 'BS048', 'BS049', 'BS050', 'BS051', 'BS052', 'BS053', 'BS054', 'BS055', 'BS056', 'BS057', 'BS058', 'BS059', 'BS060', 'BS061', 'BS062', 'BS063', 'BS064', 'BS065', 'BS066', 'BS067', 'BS068', 'BS069', 'BS070', 'BS071', 'BS072', 'BS073', 'BS074', 'BS075', 'BS076', 'BS077', 'BS078', 'BS079', 'BS080', 'BS081', 'BS082', 'BS083', 'BS084', 'BS085', 'BS086', 'BS087', 'BS088', 'BS089', 'BS090', 'BS091', 'BS092', 'BS093', 'BS094', 'BS095', 'BS096', 'BS097'];
    public const FORM_DISPLAY = ["BS001" => "Aerosol Foam", "BS002" => "Aerosol Metered Dose", "BS003" => "Aerosol Spray", "BS004" => "Oral Spray", "BS005" => "Buscal Spray", "BS006" => "Transdermal Spray", "BS007" => "Topical Spray", "BS008" => "Serbuk Spray", "BS009" => "Eliksir", "BS010" => "Emulsi", "BS011" => "Enema", "BS012" => "Gas", "BS013" => "Gel", "BS014" => "Gel Mata", "BS015" => "Granul Effervescent", "BS016" => "Granula", "BS017" => "Intra Uterine Device (IUD)", "BS018" => "Implant", "BS019" => "Kapsul", "BS020" => "Kapsul Lunak", "BS021" => "Kapsul Pelepasan Lambat", "BS022" => "Kaplet", "BS023" => "Kaplet Salut Selaput", "BS024" => "Kaplet Salut Enterik", "BS025" => "Kaplet Salut Gula", "BS026" => "Kaplet Pelepasan Lambat", "BS027" => "Kaplet Pelepasan Cepat", "BS028" => "Kaplet Kunyah", "BS029" => "Kaplet Kunyah Salut Selaput", "BS030" => "Krim", "BS031" => "Krim Lemak", "BS032" => "Larutan", "BS033" => "Larutan Inhalasi", "BS034" => "Larutan Injeksi", "BS035" => "Infus", "BS036" => "Obat Kumur", "BS037" => "Ovula", "BS038" => "Pasta", "BS039" => "Pil", "BS040" => "Patch", "BS041" => "Pessary", "BS042" => "Salep", "BS043" => "Salep Mata", "BS044" => "Sampo", "BS045" => "Semprot Hidung", "BS046" => "Serbuk Aerosol", "BS047" => "Serbuk Oral", "BS048" => "Serbuk Inhaler", "BS049" => "Serbuk Injeksi", "BS050" => "Serbuk Injeksi Liofilisasi", "BS051" => "Serbuk Infus", "BS052" => "Serbuk Obat Luar / Serbuk Tabur", "BS053" => "Serbuk Steril", "BS054" => "Serbuk Effervescent", "BS055" => "Sirup", "BS056" => "Sirup Kering", "BS057" => "Sirup Kering Pelepasan Lambat", "BS058" => "Subdermal Implants", "BS059" => "Supositoria", "BS060" => "Suspensi", "BS061" => "Suspensi Injeksi", "BS062" => "Suspensi / Cairan Obat Luar", "BS063" => "Cairan Steril", "BS064" => "Cairan Mata", "BS065" => "Cairan Diagnostik", "BS066" => "Tablet", "BS067" => "Tablet Effervescent", "BS068" => "Tablet Hisap", "BS069" => "Tablet Kunyah", "BS070" => "Tablet Pelepasan Cepat", "BS071" => "Tablet Pelepasan Lambat", "BS072" => "Tablet Disintegrasi Oral", "BS073" => "Tablet Dispersibel", "BS074" => "Tablet Cepat Larut", "BS075" => "Tablet Salut Gula", "BS076" => "Tablet Salut Enterik", "BS077" => "Tablet Salut Selaput", "BS078" => "Tablet Sublingual", "BS079" => "Tablet Sublingual Pelepasan Lambat", "BS080" => "Tablet Vaginal", "BS081" => "Tablet Lapis", "BS082" => "Tablet Lapis Lepas Lambat", "BS083" => "Chewing Gum", "BS084" => "Tetes Mata", "BS085" => "Tetes Hidung", "BS086" => "Tetes Telinga", "BS087" => "Tetes Oral (Oral Drops)", "BS088" => "Tetes Mata Dan Telinga", "BS089" => "Transdermal", "BS090" => "Transdermal Urethral", "BS091" => "Tulle/Plester Obat", "BS092" => "Vaginal Cream", "BS093" => "Vaginal Gel", "BS094" => "Vaginal Douche", "BS095" => "Vaginal Ring", "BS096" => "Vaginal Tissue", "BS097" => "Suspensi Inhalasi"];

    public const TYPE_SYSTEM = 'http://terminology.kemkes.go.id/CodeSystem/medication-type';
    public const TYPE_CODE = ['NC', 'SD', 'EP'];
    public const TYPE_DISPLAY = ["NC" => "Non-compound", "SD" => "Gives of such doses", "EP" => "Divide into equal parts"];
    public const TYPE_DEFINITION = ["NC" => "Obat non-racikan", "SD" => "Obat racikan dengan instruksi berikan dalam dosis demikian/d.t.d", "EP" => "Obat racikan non-d.t.d"];

    protected $table = 'medication';
    protected $casts = [
        'amount_numerator_value' => 'decimal:2',
        'amount_denominator_value' => 'decimal:2',
        'batch_expiration_date' => 'datetime',
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(MedicationIdentifier::class);
    }

    public function ingredient(): HasMany
    {
        return $this->hasMany(MedicationIngredient::class);
    }
}
