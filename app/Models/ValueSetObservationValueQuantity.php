<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValueSetObservationValueQuantity extends Model
{
    protected $table = 'valueset_observation_valuequantity';
    public $timestamps = false;

    public const SYSTEM = 'http://unitsofmeasure.org';
}
