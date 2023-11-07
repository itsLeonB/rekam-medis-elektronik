<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValueSetProcedureFocalDevice extends Model
{
    protected $table = 'valueset_procedure_focadevice';
    public $timestamps = false;

    public const SYSTEM = 'http://snomed.info/sct';
}
