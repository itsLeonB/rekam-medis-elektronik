<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicinePrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'base_price', 'purchase_price', 'treatment_price_1', 'treatment_price_2',
        'treatment_price_3', 'treatment_price_4', 'treatment_price_5', 
        'treatment_price_6', 'treatment_price_7', 'treatment_price_8', 
        'treatment_price_9'
    ];

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
}
