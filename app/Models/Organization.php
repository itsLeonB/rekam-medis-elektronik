<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    public const TYPE_SYSTEM = 'http://terminology.hl7.org/CodeSystem/organization-type';
    public const TYPE_CODE = ['prov', 'dept', 'team', 'govt', 'ins', 'pay', 'edu', 'reli', 'crs', 'cg', 'bus', 'other'];
    public const TYPE_DISPLAY = ['prov' => 'Healthcare Provider', 'dept' => 'Hospital Department', 'team' => 'Organizational team', 'govt' => 'Government', 'ins' => 'Insurance Company', 'pay' => 'Payer', 'edu' => 'Educational Institute', 'reli' => 'Religious Institution', 'crs' => 'Clinical Research Sponsor', 'cg' => 'Community Group', 'bus' => 'Non-Healthcare Business or Corporation', 'other' => 'Other'];
    public const TYPE_DEFINITION = ['prov' => 'Fasilitas Pelayanan Kesehatan', 'dept' => 'Departemen dalam Rumah Sakit', 'team' => 'Kelompok praktisi/tenaga kesehatan yang menjalankan fungsi tertentu dalam suatu organisasi', 'govt' => 'Organisasi Pemerintah', 'ins' => 'Perusahaan Asuransi', 'pay' => 'Badan Penjamin', 'edu' => 'Institusi Pendidikan/Penelitian', 'reli' => 'Organisasi Keagamaan', 'crs' => 'Sponsor penelitian klinis', 'cg' => 'Kelompok Masyarakat', 'bus' => 'Perusahaan diluar bidang kesehatan', 'other' => 'Lain-lain'];

    protected $table = 'organization';
    protected $casts = [
        'active' => 'boolean',
        'type' => 'array',
        'alias' => 'array',
        'endpoint' => 'array'
    ];
    protected $with = ['identifier', 'telecom', 'address', 'contact'];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(OrganizationIdentifier::class);
    }

    public function telecom(): HasMany
    {
        return $this->hasMany(OrganizationTelecom::class);
    }

    public function address(): HasMany
    {
        return $this->hasMany(OrganizationAddress::class);
    }

    public function contact(): HasMany
    {
        return $this->hasMany(OrganizationContact::class);
    }
}
