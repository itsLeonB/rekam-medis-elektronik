<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class MedicationCopy extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'medication_copies';

    // Disable auto-incrementing _id
    public $incrementing = false;

    public $timestamps = false;
    protected $fillable = ['resourceType', 'identifier', 'id', 'meta', 'code', 'form', 'extension'];
    // protected $casts = [];
}
