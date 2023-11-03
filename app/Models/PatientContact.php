<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PatientContact extends Model
{
    use HasFactory;

    protected $table = 'patient_contact';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function telecom(): HasMany
    {
        return $this->hasMany(PatientContactTelecom::class, 'contact_id', 'id');
    }
}
