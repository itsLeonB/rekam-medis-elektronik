<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcedureFollowUp extends Model
{
    public const SYSTEM = 'http://snomed.info/sct';
    public const CODE = ['18949003', '30549001', '241031001', '35963001', '225164002', '447346005', '229506003', '274441001', '394725008', '359825008'];
    public const DISPLAY = ["18949003" => "Change of dressing", "30549001" => "Removal of suture", "241031001" => "Removal of drain", "35963001" => "Removal of staples", "225164002" => "Removal of ligature", "447346005" => "Cardiopulmonary exercise test", "229506003" => "Scar tissue massage", "274441001" => "Suction drainage", "394725008" => "Diabetes medication review", "359825008" => "Cytopathology, review of bronchioalveolar lavage specimen"];

    protected $table = 'procedure_follow_up';
    public $timestamps = false;

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }
}
