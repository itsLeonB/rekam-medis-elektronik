<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationRequestDosageDoseRate extends Model
{
    public const TYPE_SYSTEM = 'https://hl7.org/FHIR/codesystem-dose-rate-type.html';
    public const TYPE_CODE = ['calculated', 'ordered'];
    public const TYPE_DISPLAY = ["calculated" => "Calculated", "ordered" => "Ordered"];
    public const TYPE_DEFINITION = ["calculated" => "Dosis yang ditentukan dihitung oleh sistem atau yang meresepkan obat", "ordered" => "Dosis yang ditentukan seperti yang diminta oleh peresep obat"];

    protected $table = 'med_req_dosage_dose_rate';
    protected $casts = [
        'dose' => 'json',
        'rate' => 'json'
    ];
    public $timestamps = false;

    public function dosage(): BelongsTo
    {
        return $this->belongsTo(MedicationRequestDosage::class, 'med_req_dosage_id');
    }
}
