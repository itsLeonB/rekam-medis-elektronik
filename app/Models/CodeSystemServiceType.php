<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;

class CodeSystemServiceType extends Model
{
    public const SYSTEM = 'http://terminology.hl7.org/CodeSystem/service-type';
    protected $table = 'codesystem_servicetype';
    public $timestamps = false;
}
