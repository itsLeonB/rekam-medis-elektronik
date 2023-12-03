<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    public const MARITAL_STATUS_SYSTEM = 'http://terminology.hl7.org/CodeSystem/v3-MaritalStatus';
    public const MARITAL_STATUS_CODE = ['A', 'D', 'I', 'L', 'M', 'P', 'S', 'T', 'U', 'W'];
    public const MARITAL_STATUS_DISPLAY = ['A' => 'Annulled', 'D' => 'Divorced', 'I' => 'Interlocutory', 'L' => 'Legally Separated', 'M' => 'Married', 'P' => 'Polygamous', 'S' => 'Never Married', 'T' => 'Domestic partner', 'U' => 'unmarried', 'W' => 'Widowed'];
    public const MARITAL_STATUS_DEFINITION = ["A" => "Marriage contract has been declared null and to not have existed", "D" => "Marriage contract has been declared dissolved and inactive", "I" => "Subject to an Interlocutory Decree.", "L" => "Legally Separated", "M" => "A current marriage contract is active", "P" => "More than 1 current spouse", "S" => "No marriage contract has ever been entered", "T" => "Person declares that a domestic partner relationship exists.", "U" => "Currently not in a marriage contract.", "W" => "The spouse has died"];

    protected $table = 'patient';

    protected $casts = [
        'active' => 'boolean',
        'prefix' => 'array',
        'suffix' => 'array',
        'birth_date' => 'date',
        'deceased' => 'array',
        'multiple_birth' => 'array',
        'communication' => 'array',
        'general_practitioner' => 'array',
        'link' => 'array',
    ];

    protected $guarded = ['id'];

    protected $with = ['identifier', 'telecom', 'address', 'contact'];

    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(PatientIdentifier::class);
    }

    public function telecom(): HasMany
    {
        return $this->hasMany(PatientTelecom::class);
    }

    public function address(): HasMany
    {
        return $this->hasMany(PatientAddress::class);
    }

    public function contact(): HasMany
    {
        return $this->hasMany(PatientContact::class);
    }
}
