<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Observation extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($observation) {
            $orgId = config('organization_id');

            $identifier = new ObservationIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/observation/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $observation->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $observation->identifier()->save($identifier);
        });
    }

    public const STATUS_SYSTEM = 'http://hl7.org/fhir/observation-status';
    public const STATUS_CODE = ['registered', 'preliminary', 'final', 'amended', 'corrected', 'cancelled', 'entered-in-error', 'unknown'];
    public const STATUS_DEFINITION = [
        'registered' => 'Data observasi sudah di registrasi, namun belum ada hasil observasi yang tersedia',
        'preliminary' => 'Hasil observasi awal atau sementara; data mungkin tidak lengkap atau belum diverifikasi',
        'final' => 'Hasil observasi sudah selesai dan tidak memerlukan tindakan lebih lanjut.',
        'amended' => 'Setelah status "final", hasil observasi diubah untuk memperbarui, menambahkan informasi, dan koreksi hasil pemeriksaan',
        'corrected' => 'Setelah status "final", hasil observasi dimodifikasi untuk membenarkan error/kesalahan dari hasil pemeriksaan',
        'cancelled' => 'Hasil observasi tidak tersedia karena pemeriksaan dibatalkan',
        'entered-in-error' => 'Hasil observasi ditarik setelah dirilis "final" sebelumnya. Status ini seharusnya tidak boleh ada. Dalam kasus nyata, bila hasil observasi ditarik, status sebaiknya diisi dengan “cancelled” dibandingkan “entered-in error”',
        'unknown' => 'Sistem tidak mengetahui status dari data observasi'
    ];

    public const DATA_ABSENT_REASON_SYSTEM = 'http://terminology.hl7.org/CodeSystem/data-absent-reason';
    public const DATA_ABSENT_REASON_CODE = ['unknown', 'asked-unknown', 'temp-unknown', 'not-asked', 'asked-declined', 'masked', 'not-applicable', 'unsupported', 'as-text', 'error', 'not-a-number', 'negative-infinity', 'positive-infinity', 'not-performed', 'not-permitted'];
    public const DATA_ABSENT_REASON_DISPLAY = ['unknown' => 'Unknown', 'asked-unknown' => 'Asked But Unknown', 'temp-unknown' => 'Temporarily Unknown', 'not-asked' => 'Not Asked', 'asked-declined' => 'Asked But Declined', 'masked' => 'Masked', 'not-applicable' => 'Not Applicable', 'unsupported' => 'Unsupported', 'as-text' => 'As Text', 'error' => 'Error', 'not-a-number' => 'Not a Number (NaN)', 'negative-infinity' => 'Negative Infinity (NINF)', 'positive-infinity' => 'Positive Infinity (PINF)', 'not-performed' => 'Not Performed', 'not-permitted' => 'Not Permitted'];
    public const DATA_ABSENT_REASON_DEFINITION = ['unknown' => 'Nilainya diharapkan ada tetapi tidak diketahui.', 'asked-unknown' => 'Sudah ditanyakan tapi tidak diketahui nilainya.', 'temp-unknown' => 'Ada alasan untuk mengharapkan (dari alur kerja) bahwa nilainya dapat diketahui.', 'not-asked' => 'Hasil observasi tidak ditanyakan', 'asked-declined' => 'Sumber data ditanya tetapi menolak untuk menjawab.', 'masked' => 'Informasi tidak tersedia karena alasan keamanan, privasi, atau alasan lain terkait.', 'not-applicable' => 'Tidak ada nilai yang tepat untuk elemen ini (misalnya periode menstruasi terakhir untuk pria).', 'unsupported' => 'Sistem tidak mampu mendukung pencatatan elemen ini.', 'as-text' => 'Hasil observasi direpresentasikan dalam naratif', 'error' => 'Ketidaktersediaan data akibat kesalahan dalam sistem ataupun alur kerja', 'not-a-number' => 'Nilai numerik tidak ditentukan atau tidak dapat direpresentasikan karena kesalahan pemrosesan floating point.', 'negative-infinity' => 'Nilai numerik terlalu rendah dan tidak dapat direpresentasikan karena kesalahan pemrosesan floating point.', 'positive-infinity' => 'Nilai numerik terlalu tinggi dan tidak dapat direpresentasikan karena kesalahan pemrosesan floating point.', 'not-performed' => 'Hasil observasi tidak tersedia karena prosedur observasi tidak dilakukan', 'not-permitted' => 'Hasil observasi tidak diizinkan dalam konteks ini (contoh : akibat profile FHIR atau tipe data)'];

    protected $table = 'observation';
    protected $casts = [
        'effective' => 'array',
        'issued' => 'datetime',
        'value' => 'array',
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(ObservationIdentifier::class);
    }

    public function basedOn(): HasMany
    {
        return $this->hasMany(ObservationBasedOn::class);
    }

    public function partOf(): HasMany
    {
        return $this->hasMany(ObservationPartOf::class);
    }

    public function category(): HasMany
    {
        return $this->hasMany(ObservationCategory::class);
    }

    public function focus(): HasMany
    {
        return $this->hasMany(ObservationFocus::class);
    }

    public function performer(): HasMany
    {
        return $this->hasMany(ObservationPerformer::class);
    }

    public function interpretation(): HasMany
    {
        return $this->hasMany(ObservationInterpretation::class);
    }

    public function note(): HasMany
    {
        return $this->hasMany(ObservationNote::class);
    }

    public function referenceRange(): HasMany
    {
        return $this->hasMany(ObservationReferenceRange::class);
    }

    public function member(): HasMany
    {
        return $this->hasMany(ObservationMember::class);
    }

    public function derivedFrom(): HasMany
    {
        return $this->hasMany(ObservationDerivedFrom::class);
    }

    public function component(): HasMany
    {
        return $this->hasMany(ObservationComponent::class);
    }
}
