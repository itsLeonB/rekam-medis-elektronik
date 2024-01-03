<?php

namespace App\Models\Fhir\BackboneElements;

use App\Models\Fhir\Datatypes\Duration;
use App\Models\Fhir\Datatypes\SimpleQuantity;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class MedicationRequestDispenseRequestInitialFill extends FhirModel
{
    use HasFactory;

    protected $table = 'med_req_disp_req_initial_fill';

    public $timestamps = false;

    public function dispenseRequest(): BelongsTo
    {
        return $this->belongsTo(MedicationRequestDispenseRequest::class, 'med_req_disp_req_id');
    }

    public function quantity(): MorphOne
    {
        return $this->morphOne(SimpleQuantity::class, 'simple_quantifiable');
    }

    public function duration(): MorphOne
    {
        return $this->morphOne(Duration::class, 'durationable');
    }
}
