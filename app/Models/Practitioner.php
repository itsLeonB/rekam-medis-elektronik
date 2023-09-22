<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Practitioner extends Model
{
    use HasFactory;

    protected $table = 'practitioner';

    protected $attributes = [
        'nik' => 9999999999999999,
        'ihs_number' => 'N10000005',
        'active' => 1,
    ];

    public function telecom(): HasMany
    {
        return $this->hasMany(PractitionerTelecom::class, 'practitioner_id', 'id');
    }

    public function address(): HasMany
    {
        return $this->HasMany(PractitionerAddress::class, 'practitioner_id', 'id');
    }

    public function qualification(): HasMany
    {
        return $this->HasMany(PractitionerQualification::class, 'practitioner_id', 'id');
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'res_id', 'res_id');
    }

    public $timestamps = false;
}
