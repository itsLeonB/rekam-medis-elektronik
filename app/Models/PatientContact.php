<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PatientContact extends Model
{
    public const RELATIONSHIP_SYSTEM = 'http://terminology.hl7.org/CodeSystem/v2-0131';
    public const RELATIONSHIP_CODE = ['C', 'E', 'F', 'I', 'N', 'S', 'U'];
    public const RELATIONSHIP_DISPLAY = ['C' => 'Emergency Contact', 'E' => 'Employer', 'F' => 'Federal Agency', 'I' => 'Insurance Company', 'N' => 'Next-of-Kin', 'S' => 'State Agency', 'U' => 'Unknown'];

    protected $table = 'patient_contact';

    public $timestamps = false;

    protected $casts = [
        'relationship' => 'array',
        'address_line' => 'array'
    ];

    protected $guarded = ['id'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function telecom(): HasMany
    {
        return $this->hasMany(PatientContactTelecom::class, 'contact_id');
    }
}
