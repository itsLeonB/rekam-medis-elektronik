<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValueSetConditionCodeRiwayatPenyakitKeluarga extends Model
{
    protected $table = 'valueset_condition_code_riwayatpenyakit_keluarga';
    public $timestamps = false;

    public const SYSTEM = 'http://snomed.info/sct';
}
