<?php

namespace App\Models\Fhir;

use App\Models\Fhir\Resources\AllergyIntolerance;
use App\Models\Fhir\Resources\ClinicalImpression;
use App\Models\Fhir\Resources\Composition;
use App\Models\Fhir\Resources\Condition;
use App\Models\Fhir\Resources\Encounter;
use App\Models\FhirModel;
use App\Models\Fhir\Resources\Location;
use App\Models\Fhir\Resources\Medication;
use App\Models\Fhir\Resources\MedicationRequest;
use App\Models\Fhir\Resources\MedicationStatement;
use App\Models\Fhir\Resources\Observation;
use App\Models\Fhir\Resources\Organization;
use App\Models\Fhir\Resources\Patient;
use App\Models\Fhir\Resources\Practitioner;
use App\Models\Fhir\Resources\Procedure;
use App\Models\Fhir\Resources\QuestionnaireResponse;
use App\Models\Fhir\Resources\ServiceRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Resource extends FhirModel
{
    use HasFactory;

    protected $table = 'resource';

    public function content(): HasMany
    {
        return $this->hasMany(ResourceContent::class);
    }

    public function practitioner(): HasOne
    {
        return $this->hasOne(Practitioner::class);
    }

    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class);
    }

    public function location(): HasOne
    {
        return $this->hasOne(Location::class);
    }

    public function organization(): HasOne
    {
        return $this->hasOne(Organization::class);
    }

    public function encounter(): HasOne
    {
        return $this->hasOne(Encounter::class);
    }

    public function condition(): HasOne
    {
        return $this->hasOne(Condition::class);
    }

    public function observation(): HasOne
    {
        return $this->hasOne(Observation::class);
    }

    public function procedure(): HasOne
    {
        return $this->hasOne(Procedure::class);
    }

    public function medication(): HasOne
    {
        return $this->hasOne(Medication::class);
    }

    public function medicationRequest(): HasOne
    {
        return $this->hasOne(MedicationRequest::class);
    }

    public function composition(): HasOne
    {
        return $this->hasOne(Composition::class);
    }

    public function allergyIntolerance(): HasOne
    {
        return $this->hasOne(AllergyIntolerance::class);
    }

    public function clinicalImpression(): HasOne
    {
        return $this->hasOne(ClinicalImpression::class);
    }

    public function serviceRequest(): HasOne
    {
        return $this->hasOne(ServiceRequest::class);
    }

    public function medicationStatement(): HasOne
    {
        return $this->hasOne(MedicationStatement::class);
    }

    public function questionnaireResponse(): HasOne
    {
        return $this->hasOne(QuestionnaireResponse::class);
    }
}
