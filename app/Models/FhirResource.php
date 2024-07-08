<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class FhirResource extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

}
