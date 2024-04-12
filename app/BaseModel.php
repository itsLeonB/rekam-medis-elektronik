<?php

namespace App;

use MongoDB\Laravel\Eloquent\Model;

class BaseModel extends Model
{
    protected $guarded = [];
}
