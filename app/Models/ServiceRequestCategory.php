<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRequestCategory extends Model
{
    public const SYSTEM = 'http://snomed.info/sct';
    public const CODE = ['152200007', '363679005', '409063005', '309073007', '387713003'];
    public const DISPLAY = ["152200007" => "Laboratory test", "363679005" => "Imaging", "409063005" => "Counseling", "309073007" => "Education", "387713003" => "Surgical procedure"];

    protected $table = 'service_request_category';
    public $timestamps = false;

    public function request(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }
}
