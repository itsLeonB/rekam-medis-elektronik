<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        Relation::enforceMorphMap([
            'Encounter' => 'App\Models\Fhir\Resources\Encounter',
            'EncounterParticipant' => 'App\Models\Fhir\BackboneElements\EncounterParticipant',
            'EncounterDiagnosis' => 'App\Models\Fhir\BackboneElements\EncounterDiagnosis',
            'EncounterStatusHistory' => 'App\Models\Fhir\BackboneElements\EncounterStatusHistory',
            'EncounterClassHistory' => 'App\Models\Fhir\BackboneElements\EncounterClassHistory',
            'EncounterHospitalization' => 'App\Models\Fhir\BackboneElements\EncounterHospitalization',
            'EncounterLocation' => 'App\Models\Fhir\BackboneElements\EncounterLocation',
            'Organization' => 'App\Models\Fhir\Resources\Organization',
            'Identifier' => 'App\Models\Fhir\Datatypes\Identifier',
            'CodeableConcept' => 'App\Models\Fhir\Datatypes\CodeableConcept',
            'ContactPoint' => 'App\Models\Fhir\Datatypes\ContactPoint',
            'Address' => 'App\Models\Fhir\Datatypes\Address',
            'Period' => 'App\Models\Fhir\Datatypes\Period',
            'Reference' => 'App\Models\Fhir\Datatypes\Reference',
            'HumanName' => 'App\Models\Fhir\Datatypes\HumanName',
            'Patient' => 'App\Models\Fhir\Resources\Patient',
            'Practitioner' => 'App\Models\Fhir\Resources\Practitioner',
            'OrganizationContact' => 'App\Models\Fhir\BackboneElements\OrganizationContact',
            'ComplexExtension' => 'App\Models\Fhir\Datatypes\ComplexExtension',
            'Extension' => 'App\Models\Fhir\Datatypes\Extension',
            'Location' => 'App\Models\Fhir\Resources\Location',
            'LocationPosition' => 'App\Models\Fhir\BackboneElements\LocationPosition',
            'LocationHoursOfOperation' => 'App\Models\Fhir\BackboneElements\LocationHoursOfOperation',
            'Attachment' => 'App\Models\Fhir\Datatypes\Attachment',
            'PractitionerQualification' => 'App\Models\Fhir\BackboneElements\PractitionerQualification',
            'PatientContact' => 'App\Models\Fhir\BackboneElements\PatientContact',
            'PatientCommunication' => 'App\Models\Fhir\BackboneElements\PatientCommunication',
            'PatientLink' => 'App\Models\Fhir\BackboneElements\PatientLink',
        ]);
    }
}
