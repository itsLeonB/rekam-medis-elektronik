<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRequestLocation extends Model
{
    public const SYSTEM = 'http://terminology.hl7.org/CodeSystem/v3-RoleCode';
    public const CODE = ['AMB'];
    public const DISPLAY = ["AMB" => "ambulance"];

    protected $table = 'service_request_location';
    public $timestamps = false;

    public function request(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }
}
