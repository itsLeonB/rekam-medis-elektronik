<?php

namespace App\Fhir;

class Satusehat
{
    public const AVAILABLE_METHODS = [
        'Practitioner' => ['get'],
        'Organization' => ['get', 'post', 'put', 'patch'],
        'Location' => ['get', 'post', 'put', 'patch'],
        'Encounter' => ['get', 'post', 'put', 'patch'],
        'Condition' => ['get', 'post', 'put', 'patch'],
        'Observation' => ['get', 'post', 'put', 'patch'],
        'Composition' => ['get', 'post', 'put', 'patch'],
        'Procedure' => ['get', 'post', 'put', 'patch'],
        'Medication' => ['get', 'post', 'put', 'patch'],
        'MedicationRequest' => ['get', 'post', 'put', 'patch'],
        // 'MedicationDispense' => ['get', 'post', 'put', 'patch'],
        // 'DiagnosticReport' => ['get', 'post', 'put', 'patch'],
        'AllergyIntolerance' => ['get', 'post', 'put', 'patch'],
        'ClinicalImpression' => ['get', 'post', 'put', 'patch'],
        // 'HealthcareService' => ['get', 'post', 'put', 'patch'],
        // 'Appointment' => ['get', 'post', 'put', 'patch'],
        // 'AppointmentResponse' => ['get', 'post', 'put', 'patch'],
        // 'PractitionerRole' => ['get', 'post', 'put', 'patch'],
        // 'Slot' => ['get', 'post', 'put', 'patch'],
        // 'Immunization' => ['get', 'post', 'put', 'patch'],
        // 'ImagingStudy' => ['get', 'post', 'put'],
        // 'Consent' => ['get', 'post'],
        // 'EpisodeOfCare' => ['get', 'post', 'put', 'patch'],
        // 'CarePlan' => ['get', 'post', 'put', 'patch'],
        // 'FamilyMemberHistory' => ['get', 'post', 'put', 'patch'],
        'QuestionnaireResponse' => ['get', 'post', 'put', 'patch'],
        'ServiceRequest' => ['get', 'post', 'put', 'patch'],
        // 'Specimen' => ['get', 'post', 'put', 'patch'],
        // 'RelatedPerson' => ['get', 'post', 'put', 'patch'],
        'Patient' => ['get', 'post']
    ];
}
