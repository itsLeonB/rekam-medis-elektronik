<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValueSetProcedureReasonCode extends Model
{
    protected $table = 'valueset_procedure_reasoncode';
    public $timestamps = false;

    public const SYSTEM = 'http://snomed.info/sct';
}
