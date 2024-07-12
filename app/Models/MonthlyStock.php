<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class MonthlyStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'year',
        'quantity',
    ];
}
