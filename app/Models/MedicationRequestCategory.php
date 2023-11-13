<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationRequestCategory extends Model
{
    public const SYSTEM = 'http://terminology.hl7.org/CodeSystem/medicationrequest-category';
    public const CODE = ['inpatient', 'outpatient', 'community', 'discharge'];
    public const DISPLAY = ["inpatient" => "Inpatient", "outpatient" => "Outpatient", "community" => "Community", "discharge" => "Discharge"];
    public const DEFINITION = ["inpatient" => "Peresepan untuk diadministrasikan atau dikonsumsi saat rawat inap", "outpatient" => "Peresepan untuk diadministrasikan atau dikonsumsi saat rawat jalan (cth. IGD, poliklinik rawat jalan, bedah rawat jalan, dll)", "community" => "Peresepan untuk diadministrasikan atau dikonsumsi di rumah (long term care atau nursing home, atau hospices)", "discharge" => "Peresepan obat yang dibuat ketika pasien dipulangkan dari fasilitas kesehatan"];

    protected $table = 'medication_request_category';
    public $timestamps = false;

    public function medicationRequest(): BelongsTo
    {
        return $this->belongsTo(MedicationRequest::class, 'med_req_id');
    }
}
