<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceRequest extends Model
{
    protected $table = 'service_request';
    protected $casts = [
        'do_not_perform' => 'boolean',
        'quantity' => 'json',
        'occurence' => 'json',
        'as_needed' => 'json',
        'authored_on' => 'datetime'
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(ServiceRequestIdentifier::class, 'request_id');
    }

    public function basedOn(): HasMany
    {
        return $this->hasMany(ServiceRequestBasedOn::class, 'request_id');
    }

    public function replaces(): HasMany
    {
        return $this->hasMany(ServiceRequestReplaces::class, 'request_id');
    }

    public function category(): HasMany
    {
        return $this->hasMany(ServiceRequestCategory::class, 'request_id');
    }

    public function orderDetail(): HasMany
    {
        return $this->hasMany(ServiceRequestOrderDetail::class, 'request_id');
    }

    public function performer(): HasMany
    {
        return $this->hasMany(ServiceRequestPerformer::class, 'request_id');
    }

    public function location(): HasMany
    {
        return $this->hasMany(ServiceRequestLocation::class, 'request_id');
    }

    public function reason(): HasMany
    {
        return $this->hasMany(ServiceRequestReason::class, 'request_id');
    }

    public function insurance(): HasMany
    {
        return $this->hasMany(ServiceRequestInsurance::class, 'request_id');
    }

    public function supportingInfo(): HasMany
    {
        return $this->hasMany(ServiceRequestSupportingInfo::class, 'request_id');
    }

    public function specimen(): HasMany
    {
        return $this->hasMany(ServiceRequestSpecimen::class, 'request_id');
    }

    public function bodySite(): HasMany
    {
        return $this->hasMany(ServiceRequestBodySite::class, 'request_id');
    }

    public function note(): HasMany
    {
        return $this->hasMany(ServiceRequestNote::class, 'request_id');
    }

    public function relevantHistory(): HasMany
    {
        return $this->hasMany(ServiceRequestRelevantHistory::class, 'request_id');
    }
}
