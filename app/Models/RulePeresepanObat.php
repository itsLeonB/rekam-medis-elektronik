<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class RulePeresepanObat extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'rules_peresepan_obat';

    protected $fillable = [
        'keluhan', 'diagnosa', 'umur',
    ];
    protected $casts = [
        'keluhan' => 'array',
    ];
}
