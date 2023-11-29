<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganizationContact extends Model
{
    public const PURPOSE_SYSTEM = 'http://terminology.hl7.org/CodeSystem/contactentity-PURPOSE';
    public const PURPOSE_CODE = ['BILL', 'ADMIN', 'HR', 'PAYOR', 'PATINF', 'PRESS'];
    public const PURPOSE_DISPLAY = ['BILL' => 'Billing', 'ADMIN' => 'Administrative', 'HR' => 'Human Resource', 'PAYOR' => 'Payor', 'PATINF' => 'Patient', 'PRESS' => 'Press'];
    public const PURPOSE_DEFINITION = ['BILL' => 'Billing', 'ADMIN' => 'Administratif', 'HR' => 'SDM seperti informasi staf/tenaga kesehatan', 'PAYOR' => 'Klaim asuransi, pembayaran', 'PATINF' => 'Informasi umum untuk pasien', 'PRESS' => 'Pertanyaan terkait press'];

    protected $table = 'organization_contact';
    protected $casts = ['address_line' => 'array'];
    public $timestamps = false;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function telecom(): HasMany
    {
        return $this->hasMany(OrganizationContactTelecom::class, 'organization_contact_id', 'id');
    }
}
