<?php

namespace App\Models;

use App\Model;

class Identifier extends Model
{
    public const USE = ['usual', 'official', 'temp', 'secondary', 'old'];
}
