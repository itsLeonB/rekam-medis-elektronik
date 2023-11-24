<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecimenCondition extends Model
{
    public const SYSTEM = 'http://hl7.org/fhir/R4/v2/0493/index.html';
    public const CODE = ['AUT', 'CFU', 'CLOT', 'CON', 'COOL', 'FROZ', 'HEM', 'LIVE', 'ROOM'];
    public const DISPLAY = ["AUT" => "Autolyzed", "CFU" => "Centrifuged", "CLOT" => "Clotted", "CON" => "Contaminated", "COOL" => "Cool", "FROZ" => "Frozen", "HEM" => "Hemolyzed", "LIVE" => "Live", "ROOM" => "Room temperature"];
    public const DEFINITION = ["AUT" => "Sampel hasil diautolisasi", "CFU" => "Sampel hasil sentrifugasi", "CLOT" => "Sampel hasil pembekuan darah", "CON" => "Sampel terkontaminasi", "COOL" => "Sampel dalam kondisi dingin", "FROZ" => "Sampel dalam kondisi beku", "HEM" => "Sampel dalam kondisi terhemolisis", "LIVE" => "Sampel hidup", "ROOM" => "Sampel pada suhu ruangan"];

    protected $table = 'specimen_condition';
    public $timestamps = false;

    public function specimen(): BelongsTo
    {
        return $this->belongsTo(Specimen::class);
    }
}
