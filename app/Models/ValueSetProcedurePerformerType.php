<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValueSetProcedurePerformerType extends Model
{
    protected $table = 'valueset_procedure_performertype';
    public $timestamps = false;

    public const SYSTEM = 'http://snomed.info/sct';
}
