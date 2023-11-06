<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValueSetConditionCodeKeluhanUtama extends Model
{
    protected $table = 'valueset_condition_code_keluhanutama';
    public $timestamps = false;

    public const SYSTEM = 'http://snomed.info/sct';
}
