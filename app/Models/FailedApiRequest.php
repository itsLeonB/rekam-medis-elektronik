<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedApiRequest extends Model
{
    use HasFactory;

    protected $fillable = ['method', 'res_type', 'res_id', 'data'];

    protected $casts = ['data' => 'array'];
}
