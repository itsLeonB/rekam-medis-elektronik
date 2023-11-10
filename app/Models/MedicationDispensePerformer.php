<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationDispensePerformer extends Model
{
    public const FUNCTION_SYSTEM = 'http://www.hl7.org/fhir/codesystem-medicationdispense-performer-function.html';
    public const FUNCTION_CODE = ['dataenterer', 'packager', 'checker', 'finachecker'];
    public const FUNCTION_DISPLAY = ["dataenterer" => "Data Enterer", "packager" => "Packager", "checker" => "Checker", "finachecker" => "Final Checker"];
    public const FUNCTION_DEFINITION = ["dataenterer" => "Yang memasukkan data", "packager" => "Pengemas", "checker" => "Pengecek", "finachecker" => "Pengecek akhir"];

    protected $table = 'medication_dispense_performer';
    public $timestamps = false;

    public function dispense(): BelongsTo
    {
        return $this->belongsTo(MedicationDispense::class, 'dispense_id');
    }
}
