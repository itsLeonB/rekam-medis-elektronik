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

        $fhirResources = [
            'Organization' => 'App\Models\Fhir\Resources\Organization',
            'Location' => 'App\Models\Fhir\Resources\Location',
            'Practitioner' => 'App\Models\Fhir\Resources\Practitioner',
            'Patient' => 'App\Models\Fhir\Resources\Patient',
            'Encounter' => 'App\Models\Fhir\Resources\Encounter',
            'Condition' => 'App\Models\Fhir\Resources\Condition',
            'Observation' => 'App\Models\Fhir\Resources\Observation',
            'Procedure' => 'App\Models\Fhir\Resources\Procedure',
            'Medication' => 'App\Models\Fhir\Resources\Medication',
            'MedicationRequest' => 'App\Models\Fhir\Resources\MedicationRequest',
        ];

        $fhirDatatypes = [
            'Address' => 'App\Models\Fhir\Datatypes\Address',
            'Age' => 'App\Models\Fhir\Datatypes\Age',
            'Annotation' => 'App\Models\Fhir\Datatypes\Annotation',
            'Attachment' => 'App\Models\Fhir\Datatypes\Attachment',
            'CodeableConcept' => 'App\Models\Fhir\Datatypes\CodeableConcept',
            'Coding' => 'App\Models\Fhir\Datatypes\Coding',
            'ComplexExtension' => 'App\Models\Fhir\Datatypes\ComplexExtension',
            'ContactPoint' => 'App\Models\Fhir\Datatypes\ContactPoint',
            'Dosage' => 'App\Models\Fhir\Datatypes\Dosage',
            'DoseAndRate' => 'App\Models\Fhir\Datatypes\DoseAndRate',
            'Duration' => 'App\Models\Fhir\Datatypes\Duration',
            'Extension' => 'App\Models\Fhir\Datatypes\Extension',
            'HumanName' => 'App\Models\Fhir\Datatypes\HumanName',
            'Identifier' => 'App\Models\Fhir\Datatypes\Identifier',
            'Period' => 'App\Models\Fhir\Datatypes\Period',
            'Quantity' => 'App\Models\Fhir\Datatypes\Quantity',
            'Range' => 'App\Models\Fhir\Datatypes\Range',
            'Ratio' => 'App\Models\Fhir\Datatypes\Ratio',
            'Reference' => 'App\Models\Fhir\Datatypes\Reference',
            'SampledData' => 'App\Models\Fhir\Datatypes\SampledData',
            'SimpleQuantity' => 'App\Models\Fhir\Datatypes\SimpleQuantity',
            'Timing' => 'App\Models\Fhir\Datatypes\Timing',
            'TimingRepeat' => 'App\Models\Fhir\Datatypes\TimingRepeat',
        ];

        $fhirBackboneElements = [
            'ConditionEvidence' => 'App\Models\Fhir\BackboneElements\ConditionEvidence',
            'ConditionStage' => 'App\Models\Fhir\BackboneElements\ConditionStage',
            'EncounterClassHistory' => 'App\Models\Fhir\BackboneElements\EncounterClassHistory',
            'EncounterDiagnosis' => 'App\Models\Fhir\BackboneElements\EncounterDiagnosis',
            'EncounterHospitalization' => 'App\Models\Fhir\BackboneElements\EncounterHospitalization',
            'EncounterLocation' => 'App\Models\Fhir\BackboneElements\EncounterLocation',
            'EncounterParticipant' => 'App\Models\Fhir\BackboneElements\EncounterParticipant',
            'EncounterStatusHistory' => 'App\Models\Fhir\BackboneElements\EncounterStatusHistory',
            'LocationHoursOfOperation' => 'App\Models\Fhir\BackboneElements\LocationHoursOfOperation',
            'LocationPosition' => 'App\Models\Fhir\BackboneElements\LocationPosition',
            'MedicationBatch' => 'App\Models\Fhir\BackboneElements\MedicationBatch',
            'MedicationIngredient' => 'App\Models\Fhir\BackboneElements\MedicationIngredient',
            'MedicationRequestDispenseRequest' => 'App\Models\Fhir\BackboneElements\MedicationRequestDispenseRequest',
            'MedicationRequestDispenseRequestInitialFill' => 'App\Models\Fhir\BackboneElements\MedicationRequestDispenseRequestInitialFill',
            'MedicationRequestSubstitution' => 'App\Models\Fhir\BackboneElements\MedicationRequestSubstitution',
            'ObservationComponent' => 'App\Models\Fhir\BackboneElements\ObservationComponent',
            'ObservationComponentReferenceRange' => 'App\Models\Fhir\BackboneElements\ObservationComponentReferenceRange',
            'ObservationReferenceRange' => 'App\Models\Fhir\BackboneElements\ObservationReferenceRange',
            'OrganizationContact' => 'App\Models\Fhir\BackboneElements\OrganizationContact',
            'PatientCommunication' => 'App\Models\Fhir\BackboneElements\PatientCommunication',
            'PatientContact' => 'App\Models\Fhir\BackboneElements\PatientContact',
            'PatientLink' => 'App\Models\Fhir\BackboneElements\PatientLink',
            'PractitionerQualification' => 'App\Models\Fhir\BackboneElements\PractitionerQualification',
            'ProcedureFocalDevice' => 'App\Models\Fhir\BackboneElements\ProcedureFocalDevice',
            'ProcedurePerformer' => 'App\Models\Fhir\BackboneElements\ProcedurePerformer',
        ];

        Relation::enforceMorphMap(
            array_merge(
                $fhirResources,
                $fhirDatatypes,
                $fhirBackboneElements
            )
        );
    }
}
