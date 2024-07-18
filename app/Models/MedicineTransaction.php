<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicineTransaction extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        '_id',
        'id_transaction',
        'id_medicine',
        'quantity',
        'note',
    ];
     /**
     * Get the medicine associated with the transaction.
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'id_medicine');
    }
}

