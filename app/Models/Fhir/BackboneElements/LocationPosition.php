<?php

namespace App\Models\Fhir\BackboneElements;

use App\Models\FhirModel;
use App\Models\Fhir\Resources\Location;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LocationPosition extends FhirModel
{
    use HasFactory;

    protected $table = 'location_position';

    protected $casts = [
        'longitude' => 'double',
        'latitude' => 'double',
        'altitude' => 'double',
    ];

    public $timestamps = false;

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
