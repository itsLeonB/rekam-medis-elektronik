<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValueSetConditionCodeRiwayatPenyakitPribadi extends Model
{
    protected $table = 'valueset_condition_code_riwayatpenyakit_pribadi';
    public $timestamps = false;

    public const SYSTEM = 'http://snomed.info/sct';
}
