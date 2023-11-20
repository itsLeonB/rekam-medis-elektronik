<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;

class CodeSystemEncounterReason extends Model
{
    public const SYSTEM = 'http://snomed.info/sct';

    protected $table = 'codesystem_encounterreason';
    public $timestamps = false;
}
