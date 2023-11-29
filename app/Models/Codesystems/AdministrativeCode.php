<?php

namespace App\Models\Codesystems;

use Illuminate\Database\Eloquent\Model;

class AdministrativeCode extends Model
{
    public const URL = "https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode";
    protected $table = 'codesystem_administrativecode';
    public $timestamps = false;
}
