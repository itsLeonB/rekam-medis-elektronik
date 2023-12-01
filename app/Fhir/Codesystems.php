<?php

namespace App\Fhir;

class Codesystems
{
    public const ClinicalSpecialty = [
        'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-speciality',
        'table' => 'codesystem_clinicalspecialty',
    ];

    public const AdministrativeArea = [
        'system' => 'http://sys-ids.kemkes.go.id/administrative-area',
        'table' => 'codesystem_administrativearea',
    ];
}

