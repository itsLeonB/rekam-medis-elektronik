<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class ExpertSystem extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'expert_systems';
    
    public $timestamps = false;
    protected $guarded = [];
    
}
