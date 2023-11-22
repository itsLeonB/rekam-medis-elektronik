<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specimen extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($specimen) {
            $orgId = config('app.organization_id');

            $identifier = new SpecimenIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/specimen/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $specimen->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $specimen->identifier()->save($identifier);
        });
    }

    public const STATUS_SYSTEM = 'http://hl7.org/fhir/specimen-status';
    public const STATUS_CODE = ['available', 'unavailable', 'unsatisfactory', 'entered-in-error'];
    public const STATUS_DISPLAY = ["available" => "Spesimen tersedia dan dalam kondisi baik", "unavailable" => "Spesimen tidak tersedia karena hilang atau rusak", "unsatisfactory" => "Spesimen tidak dapat digunakan karena isu kualitas seperti wadah yang rusak, kontaminasi, atau terlalu lama.", "entered-in-error" => "Data spesimen yang dimasukkan error sehingga tidak valid"];

    public const COLLECTION_METHOD_SYSTEM = 'http://snomed.info/sct';
    public const COLLECTION_METHOD_CODE = ['129316008', '129314006', '129300006', '129304002', '129323009', '82078001', '225113003', '386089008', '713143008', '1048003', '70777001', '73416001', '243776001', '278450005', '285570007'];
    public const COLLECTION_METHOD_DISPLAY = ["129316008" => "Aspiration - action", "129314006" => "Biopsy - action", "129300006" => "Puncture - action", "129304002" => "Excision - action", "129323009" => "Scraping - action", "82078001" => "Collection of blood specimen for laboratory", "225113003" => "Timed urine collection", "386089008" => "Collection of coughed sputum", "713143008" => "Collection of arterial blood specimen", "1048003" => "Capillary specimen collection", "70777001" => "Urine specimen collection, catheterized", "73416001" => "Urine specimen collection, clean catch", "243776001" => "Blood sampling from extracorporeal blood circuit", "278450005" => "Finger-prick sampling", "285570007" => "Taking of swab"];

    public const COLLECTION_FASTING_STATUS_SYSTEM = 'http://terminology.hl7.org/CodeSystem/v2-0916';
    public const COLLECTION_FASTING_STATUS_CODE = ['F', 'NF', 'NG'];
    public const COLLECTION_FASTING_STATUS_DISPLAY = ["F" => "Patient was fasting prior to the procedure.", "NF" => "The patient indicated they did not fast prior to the procedure.", "NG" => "Not Given - Patient was not asked at the time of the procedure."];
    public const COLLECTION_FASTING_STATUS_DEFINITION = ["F" => "Pasien puasa sebelum prosedur", "NF" => "Pasien tidak puasa sebelum prosedur", "NG" => "Pasien tidak ditanyakan status puasa saat prosedur"];

    protected $table = 'specimen';
    protected $casts = [
        'received_time' => 'datetime',
        'collection_collected' => 'array',
        'collection_duration_value' => 'decimal:2',
        'collection_quantity_value' => 'decimal:2',
        'collection_fasting_status' => 'array'
    ];
    public $timestamps = false;
    protected $with = ['identifier', 'parent', 'request', 'processing', 'container', 'condition', 'note'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(SpecimenIdentifier::class);
    }

    public function parent(): HasMany
    {
        return $this->hasMany(SpecimenParent::class);
    }

    public function request(): HasMany
    {
        return $this->hasMany(SpecimenRequest::class);
    }

    public function processing(): HasMany
    {
        return $this->hasMany(SpecimenProcessing::class);
    }

    public function container(): HasMany
    {
        return $this->hasMany(SpecimenContainer::class);
    }

    public function condition(): HasMany
    {
        return $this->hasMany(SpecimenCondition::class);
    }

    public function note(): HasMany
    {
        return $this->hasMany(SpecimenNote::class);
    }
}
