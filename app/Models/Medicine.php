<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $primaryKey = 'medicine_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'medicine_code', 'name', 'expiry_date', 'quantity', 'package', 'uom',
        'amount_per_package', 'manufacturer', 'is_fast_moving', 'ingredients', 
        'minimum_quantity', 'dosage_form', 'medicine_prices_id'
    ];

    protected $casts = [
        'ingredients' => 'array',
        'dosage_form' => 'json',
    ];

    public function medicinePrice()
    {
        return $this->belongsTo(MedicinePrice::class);
    }
}
