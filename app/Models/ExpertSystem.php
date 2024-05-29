<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class ExpertSystem extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'expert_systems';
    protected $fillable = [
        'keluhan',
        'diagnosa',
        'fisik',
        'obat',
        'alergi',
        'umur',
        'status_kehamilan',
    ];

    protected $casts = [
        'keluhan' => 'array',
        'obat' => 'array',
        'fisik' => 'array',
        'alergi' => 'array',
        'status_kehamilan' => 'boolean',
    ];
}
