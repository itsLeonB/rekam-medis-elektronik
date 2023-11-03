<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Resource extends Model
{
    use HasFactory;

    const VALID_RESOURCE_TYPES = [
        'patient', 'practitioner', 'location', 'organization', 'encounter', 'condition', 'observation', 'imagingstudy', 'procedure', 'servicerequest', 'medicationrequest', 'medication', 'medicationdispense', 'composition', 'allergyintolerance', 'clinicalimpression'
    ];

    protected $table = 'resource';

    protected $attributes = [
        'res_version' => 1,
        'fhir_ver' => 'R4'
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function content(): HasMany
    {
        return $this->hasMany(ResourceContent::class);
    }

    public function practitioner(): HasMany
    {
        return $this->hasMany(Practitioner::class);
    }

    public function patient(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    public function location(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function organization(): HasMany
    {
        return $this->hasMany(Organization::class);
    }

    public function encounter(): HasMany
    {
        return $this->hasMany(Encounter::class);
    }

    public function condition(): HasMany
    {
        return $this->hasMany(Condition::class);
    }

    public function observation(): HasMany
    {
        return $this->hasMany(Observation::class);
    }

    public function imagingStudy(): HasMany
    {
        return $this->hasMany(ImagingStudy::class);
    }

    public function procedure(): HasMany
    {
        return $this->hasMany(Procedure::class);
    }

    public function serviceRequest(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }

    public function medicationRequest(): HasMany
    {
        return $this->hasMany(MedicationRequest::class);
    }

    public function medication(): HasMany
    {
        return $this->hasMany(Medication::class);
    }

    public function medicationDispense(): HasMany
    {
        return $this->hasMany(MedicationDispense::class);
    }

    public function composition(): HasMany
    {
        return $this->hasMany(Composition::class);
    }

    public function allergyIntolerance(): HasMany
    {
        return $this->hasMany(AllergyIntolerance::class);
    }

    public function clinicalImpression(): HasMany
    {
        return $this->hasMany(ClinicalImpression::class);
    }
}
