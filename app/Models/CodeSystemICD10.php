<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;

class CodeSystemICD10 extends Model
{
    public const SYSTEM = 'http://hl7.org/fhir/sid/icd-10';

    protected $table = 'codesystem_icd10';
    public $timestamps = false;
}
