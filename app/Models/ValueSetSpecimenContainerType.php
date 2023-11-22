<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValueSetSpecimenContainerType extends Model
{
    protected $table = 'valueset_procedure_statusreason';
    public $timestamps = false;

    public const SYSTEM = 'http://snomed.info/sct';
}
