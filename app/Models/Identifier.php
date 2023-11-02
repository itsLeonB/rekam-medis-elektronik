<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Identifier extends Model
{
    public const USE = ['usual', 'official', 'temp', 'secondary', 'old'];
}
