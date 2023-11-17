<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRequestOrderDetail extends Model
{
    public const SYSTEM = 'http://snomed.info/sct';
    public const CODE = ['47545007', '286812008', '243144002', '243150007', '59427005'];
    public const DISPLAY = ["47545007" => "Continuous positive airway pressure ventilation treatment (regime/therapy)", "286812008" => "Pressure controlled ventilation (procedure)", "243144002" => "Patient triggered inspiratory assistance (procedure)", "243150007" => "Assisted controlled mandatory ventilation (procedure)", "59427005" => "Synchronized intermittent mandatory ventilation (procedure)"];

    protected $table = 'service_request_order_detail';
    public $timestamps = false;

    public function request(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }
}
