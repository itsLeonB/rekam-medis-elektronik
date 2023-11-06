<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeSystemICD9CMProcedure extends Model
{
    public const SYSTEM = 'http://hl7.org/fhir/sid/icd-9-cm';

    protected $table = 'codesystem_icd9cm_procedure';
    public $timestamps = false;
}
