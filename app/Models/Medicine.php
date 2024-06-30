<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    protected $collection = 'medicines';

    protected $fillable = [
        'medicine_code', 'name', 'expiry_date', 'quantity', 'package', 'uom',
        'amount_per_package', 'manufacturer', 'is_fast_moving', 'ingredients',
        'minimum_quantity', 'dosage_form', 'prices'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_fast_moving' => 'boolean',
        'ingredients' => 'array',
        'prices' => 'array'
    ];
}
