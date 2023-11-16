<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeSystemLoinc extends Model
{
    public const SYSTEM = "http://loinc.org";

    protected $table = 'codesystem_loinc';
    public $timestamps = false;
}
