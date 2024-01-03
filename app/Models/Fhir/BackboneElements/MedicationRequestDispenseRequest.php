<?php

namespace App\Models\Fhir\BackboneElements;

use App\Models\Fhir\Datatypes\Duration;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Datatypes\SimpleQuantity;
use App\Models\Fhir\Resources\MedicationRequest;
use App\Models\FhirModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class MedicationRequestDispenseRequest extends FhirModel
{
    use HasFactory;

    protected $table = 'med_req_dispense_request';

    public $timestamps = false;

    public function medicationRequest(): BelongsTo
    {
        return $this->belongsTo(MedicationRequest::class, 'med_req_id');
    }

    public function initialFill(): HasOne
    {
        return $this->hasOne(MedicationRequestDispenseRequestInitialFill::class, 'med_req_disp_req_id');
    }

    public function dispenseInterval(): MorphOne
    {
        return $this->morphOne(Duration::class, 'durationable')
            ->where('attr_type', 'dispenseInterval');
    }

    public function validityPeriod(): MorphOne
    {
        return $this->morphOne(Period::class, 'periodable');
    }

    public function quantity(): MorphOne
    {
        return $this->morphOne(SimpleQuantity::class, 'simple_quantifiable');
    }

    public function expectedSupplyDuration(): MorphOne
    {
        return $this->morphOne(Duration::class, 'durationable')
            ->where('attr_type', 'expectedSupplyDuration');
    }

    public function performer(): MorphOne
    {
        return $this->morphOne(Reference::class, 'referenceable');
    }
}
