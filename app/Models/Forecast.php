<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Forecast extends Model
{
    use HasFactory;
    protected $collection = 'forecast';

    protected $fillable = ['month', 'year', 'forecast'];
}
