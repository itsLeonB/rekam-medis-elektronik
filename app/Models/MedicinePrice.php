<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicinePrice extends Model
{
    protected $guarded = [];

    public function medicines()
    {
        return $this->hasMany(Medicine::class, 'medicine_prices_id');
    }
}