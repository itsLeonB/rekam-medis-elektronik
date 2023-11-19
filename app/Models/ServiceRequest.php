<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceRequest extends Model
{
    public const STATUS_SYSTEM = 'http://hl7.org/fhir/request-status';
    public const STATUS_CODE = ['draft', 'active', 'on-hold', 'revoked', 'completed', 'entered-in-error', 'unknown'];
    public const STATUS_DISPLAY = ["draft" => "Permintaan yang telah dibuat namun belum selesai atau belum siap untuk dilakukan", "active" => "Permintaan yang berlaku dan siap untuk dilakukan", "on-hold" => "Permintaan (dan setiap hak implisit untuk bertindak) yang telah ditarik/dihentikan sementara namun diharapkan untuk dilanjutkan nanti", "revoked" => "Permintaan (dan setiap hak implisit untuk bertindak) yang telah dihentikan secara penuh dari rencana. Tidak ada aktivitas lanjutan yang harus diteruskan", "completed" => "Aktivitas yang dideskripsikan oleh permintaan yang telah selesai. Tidak ada aktivitas lanjutan yang harus diteruskan", "entered-in-error" => "Permintaan yang seharusnya tidak ada dan sebaiknya dikosongi. (hal ini mungkin berdasarkan keputusan di lapangan. Jika kondisi aktivitas telah terjadi, maka status harus menjadi “revoked” daripada “entered-in-error”)", "unknown" => "Sistem pembuat/sumber tidak mengetahui status mana yang saat ini berlaku untuk permintaan tersebut. Catatan: Konsep ini tidak digunakan untuk “lainnya”, salah satu status yang terdaftar dianggap berlaku namun sistem pembuat/sumber yang tidak dapat mengidentifikasi"];

    public const INTENT_SYSTEM = 'http://hl7.org/fhir/request-intent';
    public const INTENT_CODE = ['proposal', 'plan', 'directive', 'order', 'original-order', 'reflex-order', 'filler-order', 'instance-order', 'option'];
    public const INTENT_DISPLAY = ["proposal" => "Permintaan berupa usulan yang dibuat oleh seseorang yang tidak memiliki keinginan untuk menjamin permintaan dilaksanakan dan tanpa memberikan otorisasi untuk bertindak", "plan" => "Permintaan yang merepresentasikan niat untuk menjamin sesuatu terjadi tanpa memberikan otorisasi bagi orang lain untuk bertindak", "directive" => "Permintaan yang merepresentasikan secara legal terkait permintaan yang dilakukan oleh Patient (Pasien) atau RelatedPerson.", "order" => "Permintaan yang merepresentasikan permintaan dan otorisasi yang dilakukan oleh Practitioner (tenaga kesehatan)", "original-order" => "Permintaan yang merepresentasikan otorisasi asli untuk bertindak (permintaan asli)", "reflex-order" => "Permintaan yang dilakukan sebagai tambahan permintaan terhadap hasil awal yang membutuhkan tambahan tindakan (permintaan tambahan)", "filler-order" => "Permintaan yang merepresentasikan pandangan tentang otorisasi yang dibuat oleh sistem fulfilling yang mewakili rincian keinginan pemberi tindakan atas perintah yang diberikan", "instance-order" => "Perintah yang dibuat untuk pemenuhan lebih permintaan lebih luas yang merepresentasikan hak untuk aktivitas tunggal. Misalnya: pemberian dosis obat tinggal", "option" => "Permintaan yang merepresentasikan komponen atau opsi untuk RequestGroup yang membentuk waktu, kondisionalitas, dan/atau konstrain pada kumpulan permintaan. Merujuk pada [[[RequestGroup]]] untuk informasi tambahan mengenai bagaimana status ini dibuat"];

    public const PRIORITY_SYSTEM = 'http://hl7.org/fhir/request-priority';
    public const PRIORITY_CODE = ['routine', 'urgent', 'asap', 'stat'];
    public const PRIORITY_DISPLAY = ["routine" => "Permintaan prioritas normal", "urgent" => "Permintaan yang harus dilakukan segera ditindaklanjuti/lebih prioritas daripada Routine", "asap" => "Permintaan yang harus dilakukan sesegera mungkin/lebih prioritas daripada Urgent", "stat" => "Permintaan yang harus dilakukan diberikan saat itu juga/lebih prioritas daripada ASAP"];

    protected $table = 'service_request';
    protected $casts = [
        'do_not_perform' => 'boolean',
        'quantity' => 'array',
        'occurrence' => 'array',
        'as_needed' => 'array',
        'authored_on' => 'datetime'
    ];
    public $timestamps = false;
    protected $with = ['identifier', 'basedOn', 'replaces', 'category', 'orderDetail', 'performer', 'location', 'reason', 'insurance', 'supportingInfo', 'specimen', 'bodySite', 'note', 'relevantHistory'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(ServiceRequestIdentifier::class, 'request_id');
    }

    public function basedOn(): HasMany
    {
        return $this->hasMany(ServiceRequestBasedOn::class, 'request_id');
    }

    public function replaces(): HasMany
    {
        return $this->hasMany(ServiceRequestReplaces::class, 'request_id');
    }

    public function category(): HasMany
    {
        return $this->hasMany(ServiceRequestCategory::class, 'request_id');
    }

    public function orderDetail(): HasMany
    {
        return $this->hasMany(ServiceRequestOrderDetail::class, 'request_id');
    }

    public function performer(): HasMany
    {
        return $this->hasMany(ServiceRequestPerformer::class, 'request_id');
    }

    public function location(): HasMany
    {
        return $this->hasMany(ServiceRequestLocation::class, 'request_id');
    }

    public function reason(): HasMany
    {
        return $this->hasMany(ServiceRequestReason::class, 'request_id');
    }

    public function insurance(): HasMany
    {
        return $this->hasMany(ServiceRequestInsurance::class, 'request_id');
    }

    public function supportingInfo(): HasMany
    {
        return $this->hasMany(ServiceRequestSupportingInfo::class, 'request_id');
    }

    public function specimen(): HasMany
    {
        return $this->hasMany(ServiceRequestSpecimen::class, 'request_id');
    }

    public function bodySite(): HasMany
    {
        return $this->hasMany(ServiceRequestBodySite::class, 'request_id');
    }

    public function note(): HasMany
    {
        return $this->hasMany(ServiceRequestNote::class, 'request_id');
    }

    public function relevantHistory(): HasMany
    {
        return $this->hasMany(ServiceRequestRelevantHistory::class, 'request_id');
    }
}
