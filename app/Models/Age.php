<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Age extends Model
{
    public const COMPARATOR = ['<', '<=', '>=', '>'];
}
