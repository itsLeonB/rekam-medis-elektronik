<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class RequestStock extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $collection = 'request_stock';
    // protected $fillable = ['code_kfa', 'display'];
    protected $guarded = [];
}
