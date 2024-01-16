<?php

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Asia/Jakarta',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => 'file',
        // 'store'  => 'redis',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,
    ])->toArray(),


    /*
    |--------------------------------------------------------------------------
    | SATUSEHAT Configuration
    |--------------------------------------------------------------------------
    |
    | Nilai konfigurasi untuk integrasi dengan layanan SATUSEHAT
    |
    */

    'auth_url' => env('auth_url'),
    'base_url' => env('base_url'),
    'consent_url' => env('consent_url'),
    'kfa_v1_url' => env('kfa_v1_url'),
    'kfa_v2_url' => env('kfa_v2_url'),
    'client_id' => env('client_id'),
    'client_secret' => env('client_secret'),
    'organization_id' => env('organization_id'),

    'rawat_jalan_org_id' => env('rawat_jalan_org_id'),
    'rawat_inap_org_id' => env('rawat_inap_org_id'),
    'igd_org_id' => env('igd_org_id'),

    'kode_layanan' => [
        'rawat-jalan' => 'AMB',
        'rawat-inap' => 'IMP',
        'igd' => 'EMER',
    ],

    'kode_poli' => [
        'umum' => 124,
        'neurologi' => 177,
        'obgyn' => 186,
        'gigi' => 88,
        'kulit' => 168,
        'ortopedi' => 218,
        'dalam' => 557,
        'bedah' => 221,
        'anak' => 286
    ],

    'identifier_systems' => [
        'patient' => [
            'nik' => 'https://fhir.kemkes.go.id/id/nik',
            'paspor' => 'https://fhir.kemkes.go.id/id/paspor',
            'kk' => 'https://fhir.kemkes.go.id/id/kk',
            'nik-ibu' => 'https://fhir.kemkes.go.id/id/nik-ibu',
            'ihs-number' => 'https://fhir.kemkes.go.id/id/ihs-number',
            'rekam-medis' => 'http://sys-ids.kemkes.go.id/' . config('app.organization_id') . '/rekam-medis',
            'bpjs' => 'http://sys-ids.kemkes.go.id/' . config('app.organization_id') . '/bpjs',
        ],
        'practitioner' => [
            'nik' => 'https://fhir.kemkes.go.id/id/nik',
            'nakes-his-number' => 'https://fhir.kemkes.go.id/id/nakes-his-number',
            'nakes-id' => 'https://fhir.kemkes.go.id/id/nakes-id',
        ],
        'alleryintolerance' => 'http://sys-ids.kemkes.go.id/allergy/' . config('app.organization_id'),
        'clinicalimpression' => 'http://sys-ids.kemkes.go.id/clinicalimpression/' . config('app.organization_id'),
        'composition' => 'http://sys-ids.kemkes.go.id/composition/' . config('app.organization_id'),
        'condition' => 'http://sys-ids.kemkes.go.id/condition/' . config('app.organization_id'),
        'encounter' => 'http://sys-ids.kemkes.go.id/encounter/' . config('app.organization_id'),
        'location' => 'http://sys-ids.kemkes.go.id/location/' . config('app.organization_id'),
        'medication' => 'http://sys-ids.kemkes.go.id/medication/' . config('app.organization_id'),
        'medicationrequest' => 'http://sys-ids.kemkes.go.id/prescription/' . config('app.organization_id'),
        'medicationstatement' => 'http://sys-ids.kemkes.go.id/medicationstatement/' . config('app.organization_id'),
        'observation' => 'http://sys-ids.kemkes.go.id/observation/' . config('app.organization_id'),
        'organization' => 'http://sys-ids.kemkes.go.id/organization/' . config('app.organization_id'),
        'procedure' => 'http://sys-ids.kemkes.go.id/procedure/' . config('app.organization_id'),
        'questionnaireresponse' => 'http://sys-ids.kemkes.go.id/questionnaireresponse/' . config('app.organization_id'),
        'servicerequest' => 'http://sys-ids.kemkes.go.id/servicerequest/' . config('app.organization_id'),
    ],

    'available_methods' => [
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
        'MedicationStatement' => ['get', 'post', 'put', 'patch'],  // Dokumentasi Postman tidak ada
        // 'MedicationDispense' => ['get', 'post', 'put', 'patch'],  // Not yet implemented
        // 'DiagnosticReport' => ['get', 'post', 'put', 'patch'],  // Not yet implemented
        'AllergyIntolerance' => ['get', 'post', 'put', 'patch'],
        'ClinicalImpression' => ['get', 'post', 'put', 'patch'],
        // 'HealthcareService' => ['get', 'post', 'put', 'patch'],  // Not yet implemented
        // 'Appointment' => ['get', 'post', 'put', 'patch'],  // Not yet implemented
        // 'AppointmentResponse' => ['get', 'post', 'put', 'patch'],  // Not yet implemented
        // 'PractitionerRole' => ['get', 'post', 'put', 'patch'],  // Not yet implemented
        // 'Slot' => ['get', 'post', 'put', 'patch'],  // Not yet implemented
        // 'Immunization' => ['get', 'post', 'put', 'patch'],  // Not yet implemented
        // 'ImagingStudy' => ['get', 'post', 'put'],  // Not yet implemented
        // 'Consent' => ['get', 'post'],  // Tidak perlu local storage/API
        // 'EpisodeOfCare' => ['get', 'post', 'put', 'patch'],  // Not yet implemented
        // 'CarePlan' => ['get', 'post', 'put', 'patch'],  // Not yet implemented
        // 'FamilyMemberHistory' => ['get', 'post', 'put', 'patch'],  // Not yet implemented
        'QuestionnaireResponse' => ['get', 'post', 'put', 'patch'],
        'ServiceRequest' => ['get', 'post', 'put', 'patch'],
        // 'Specimen' => ['get', 'post', 'put', 'patch'],  // Not yet implemented
        // 'RelatedPerson' => ['get', 'post', 'put', 'patch'],  // Not yet implemented
        'Patient' => ['get', 'post']
    ],

    'resource_type_map' => [
        'practitioner' => 'Practitioner',
        'organization' => 'Organization',
        'location' => 'Location',
        'encounter' => 'Encounter',
        'condition' => 'Condition',
        'observation' => 'Observation',
        'composition' => 'Composition',
        'procedure' => 'Procedure',
        'medication' => 'Medication',
        'medicationrequest' => 'MedicationRequest',
        'medicationstatement' => 'MedicationStatement',
        // 'medicationdispense' => 'MedicationDispense',  // Not yet implemented
        // 'diagnosticreport' => 'DiagnosticReport',  // Not yet implemented
        'allergyintolerance' => 'AllergyIntolerance',
        'clinicalimpression' => 'ClinicalImpression',
        // 'healthcareservice' => 'HealthcareService',  // Not yet implemented
        // 'appointment' => 'Appointment',  // Not yet implemented
        // 'appointmentresponse' => 'AppointmentResponse',  // Not yet implemented
        // 'practitionerrole' => 'PractitionerRole',  // Not yet implemented
        // 'slot' => 'Slot',  // Not yet implemented
        // 'immunization' => 'Immunization',  // Not yet implemented
        // 'imagingstudy' => 'ImagingStudy',  // Not yet implemented
        // 'consent' => 'Consent',  // Tidak perlu local storage
        // 'episodeofcare' => 'EpisodeOfCare',  // Not yet implemented
        // 'careplan' => 'CarePlan',  // Not yet implemented
        // 'familymemberhistory' => 'FamilyMemberHistory',  // Not yet implemented
        'questionnaireresponse' => 'QuestionnaireResponse',
        'servicerequest' => 'ServiceRequest',
        // 'specimen' => 'Specimen',  // Not yet implemented
        // 'relatedperson' => 'RelatedPerson',  // Not yet implemented
        'patient' => 'Patient'
    ],

    'terminologi' => [
        'Organization' => [
            'type' => Codesystems::OrganizationType
        ],
        'OrganizationContact' => [
            'purpose' => Codesystems::ContactEntityType
        ],
        'Location' => [
            'status' => Codesystems::LocationStatus,
            'operationalStatus' => Codesystems::v20116,
            'mode' => Codesystems::LocationMode,
            'type' => Codesystems::LocationType,
            'physicalType' => Valuesets::LocationPhysicalType,
            'serviceClass' => Codesystems::LocationServiceClass,
        ],
        'LocationHoursOfOperation' => [
            'daysOfWeek' => Valuesets::DaysOfWeek
        ],
        'Patient' => [
            'gender' => Codesystems::AdministrativeGender,
            'maritalStatus' => Valuesets::MaritalStatusCodes,
            'citizenship' => Codesystems::ISO3166,
            'religion' => Valuesets::IndonesiaReligion,
            'citizenshipStatus' => Valuesets::KemkesCitizenshipStatus,
        ],
        'PatientCommunication' => [
            'language' => Codesystems::BCP47
        ],
        'PatientContact' => [
            'relationship' => Valuesets::PatientContactRelationship,
            'gender' => Codesystems::AdministrativeGender
        ],
        'PatientLink' => [
            'type' => Valuesets::LinkType
        ],
        'Encounter' => [
            'status' => Valuesets::EncounterStatus,
            'class' => Valuesets::EncounterClass,
            'type' => Codesystems::EncounterType,
            'serviceType' => Codesystems::ServiceType,
            'priority' => Valuesets::EncounterPriority,
            'reasonCode' => Valuesets::EncounterReasonCodes
        ],
        'EncounterClassHistory' => [
            'class' => Valuesets::EncounterClass
        ],
        'EncounterDiagnosis' => [
            'use' => Codesystems::DiagnosisRole
        ],
        'EncounterHospitalization' => [
            'admitSource' => Codesystems::AdmitSource,
            'reAdmission' => Codesystems::v20092,
            'dietPreference' => Codesystems::Diet,
            'specialArrangement' => Codesystems::SpecialArrangements,
            'dischargeDisposition' => Valuesets::DischargeDisposition
        ],
        'EncounterLocation' => [
            'status' => Codesystems::LocationStatus,
            'serviceClass' => Valuesets::LocationServiceClass,
            'upgradeClass' => Codesystems::LocationUpgradeClass
        ],
        'EncounterParticipant' => [
            'type' => Valuesets::EncounterParticipantType
        ],
        'EncounterStatusHistory' => [
            'status' => Valuesets::EncounterStatus
        ],
        'Condition' => [
            'clinicalStatus' => Codesystems::ConditionClinicalStatusCodes,
            'verificationStatus' => Codesystems::ConditionVerificationStatus,
            'category' => Codesystems::ConditionCategoryCodes,
            'severity' => Valuesets::ConditionDiagnosisSeverity,
            'code' => Valuesets::ConditionProblemDiagnosisCodes,
            'bodySite' => Valuesets::SNOMEDCTBodySite
        ],
        'ConditionEvidence' => [
            'code' => Valuesets::ManifestationAndSymptomCodes
        ],
        'ConditionStage' => [
            'summary' => Valuesets::ConditionStage,
            'type' => Valuesets::ConditionStageType
        ],
        'Observation' => [
            'status' => Codesystems::ObservationStatus,
            'category' => Codesystems::ObservationCategoryCodes,
            'code' => Valuesets::ObservationCode,
            'valueQuantity' => Codesystems::UCUM,
            'valueCodeableConcept' => [
                Codesystems::SNOMEDCT,
                Codesystems::LOINC
            ],
            'dataAbsentReason' => Codesystems::DataAbsentReason,
            'interpretation' => Valuesets::ObservationInterpretationCodes,
            'bodySite' => Valuesets::SNOMEDCTBodySite,
            'method' => Valuesets::ObservationMethods
        ],
        'ObservationComponent' => [
            'code' => Codesystems::LOINC,
            'valueQuantity' => Codesystems::UCUM,
            'valueCodeableConcept' => [
                Codesystems::SNOMEDCT,
                Codesystems::LOINC
            ],
            'dataAbsentReason' => Codesystems::DataAbsentReason,
            'interpretation' => Valuesets::ObservationInterpretationCodes
        ],
        'ObservationComponentReferenceRange' => [
            'type' => Codesystems::ObservationReferenceRangeMeaningCodes,
            'appliesTo' => Valuesets::ObservationReferenceRangeAppliesToCodes
        ],
        'ObservationReferenceRange' => [
            'type' => Codesystems::ObservationReferenceRangeMeaningCodes,
            'appliesTo' => Valuesets::ObservationReferenceRangeAppliesToCodes
        ],
        'Procedure' => [
            'status' => Codesystems::EventStatus,
            'statusReason' => Valuesets::ProcedureNotPerformedReason,
            'category' => Valuesets::ProcedureCategoryCodes,
            'code' => Valuesets::ProcedureCodes,
            'reasonCode' => Codesystems::ICD10,
            'bodySite' => Valuesets::SNOMEDCTBodySite,
            'outcome' => Valuesets::ProcedureOutcomeCodes,
            'complication' => Valuesets::SNOMEDCTClinicalFindings,
            'followUp' => Valuesets::ProcedureFollowUpCodes,
            'usedCode' => Codesystems::SNOMEDCT
        ],
        'ProcedureFocalDevice' => [
            'action' => Valuesets::ProcedureDeviceActionCodes
        ],
        'ProcedurePerformer' => [
            'function' => Valuesets::ProcedurePerformerRoleCodes
        ],
        'Medication' => [
            'code' => Codesystems::KFA,
            'status' => Codesystems::MedicationStatusCodes,
            'form' => Codesystems::MedicationForm,
            'medicationType' => Codesystems::MedicationType
        ],
        'MedicationIngredient' => [
            'itemCodeableConcept' => Codesystems::KFA,
            'strengthDenominator' => [
                Codesystems::UCUM,
                Valuesets::MedicationIngredientStrengthDenominator
            ],
        ],
        'MedicationRequest' => [
            'status' => Codesystems::MedicationRequestStatus,
            'statusReason' => Codesystems::MedicationRequestStatusReasonCodes,
            'intent' => Codesystems::MedicationRequestIntent,
            'category' => Codesystems::MedicationRequestCategoryCodes,
            'priority' => Codesystems::RequestPriority,
            'performerType' => Valuesets::ProcedurePerformerRoleCodes,
            'reasonCode' => Codesystems::ICD10,
            'courseOfTherapyType' => Codesystems::MedicationRequestCourseOfTherapyCodes,
        ],
        'MedicationRequestDispenseRequst' => [
            'dispenseInterval' => Valuesets::MedicationRequestDispenseInterval,
            'quantity' => [Valuesets::MedicationIngredientStrengthDenominator, Valuesets::MedicationRequestQuantity],
            'expectedSupplyDuration' => Valuesets::MedicationRequestSupplyDuration
        ],
        'MedicationRequestSubstitution' => [
            'allowedCodeableConcept' => Codesystems::v3SubstanceAdminSubstitution,
            'reason' => Codesystems::v3ActReason
        ],
        'Composition' => [
            'status' => Codesystems::CompositionStatus,
            'type' => Valuesets::FHIRDocumentTypeCodes,
            'category' => Valuesets::DocumentClassValueSet,
            'confidentiality' => Valuesets::v3ConfidentialityClassification
        ],
        'CompositionAttester' => [
            'mode' => Codesystems::CompositionAttestationMode
        ],
        'CompositionEvent' => [
            'code' => Codesystems::v3ActCode
        ],
        'CompositionRelatesTo' => [
            'code' => Codesystems::DocumentRelationshipType
        ],
        'CompositionSection' => [
            'code' => Valuesets::DocumentSectionCodes,
            'mode' => Codesystems::ListMode,
            'orderedBy' => Codesystems::ListOrderCodes,
            'emptyReason' => Codesystems::ListEmptyReasons
        ],
        'AllergyIntolerance' => [
            'clinicalStatus' => Codesystems::AllergyIntoleranceClinicalStatusCodes,
            'verificationStatus' => Codesystems::AllergyIntoleranceVerificationStatusCodes,
            'type' => Codesystems::AllergyIntoleranceType,
            'category' => Codesystems::AllergyIntoleranceCategory,
            'criticality' => Codesystems::AllergyIntoleranceCriticality,
            'code' => Valuesets::AllergyIntoleranceSubstanceProductConditionAndNegationCodes,
        ],
        'AllergyIntoleranceReaction' => [
            'substance' => Valuesets::AllergyIntoleranceSubstanceProductConditionAndNegationCodes,
            'manifestation' => Valuesets::SNOMEDCTClinicalFindings,
            'severity' => Codesystems::AllergyIntoleranceSeverity,
            'exposureRoute' => Valuesets::SNOMEDCTRouteCodes
        ],
        'ClinicalImpression' => [
            'status' => Valuesets::ClinicalImpressionStatus,
            'statusReason' => Codesystems::ICD10,
            'prognosisCodeableConcept' => Valuesets::ClinicalImpressionPrognosis
        ],
        'ClinicalImpressionFinding' => [
            'itemCodeableConcept' => Codesystems::ICD10
        ],
        'ClinicalImpressionInvestigation' => [
            'code' => Valuesets::InvestigationType
        ],
        'ServiceRequest' => [
            'status' => Codesystems::RequestStatus,
            'intent' => Codesystems::RequestIntent,
            'category' => Valuesets::ServiceRequestCategoryCodes,
            'priority' => Codesystems::RequestPriority,
            'code' => Valuesets::ServiceRequestCodes,
            'orderDetail' => Valuesets::ServiceRequestOrderDetailsCodes,
            'asNeededCodeableConcept' => Codesystems::ICD10,
            'performerType' => Valuesets::ParticipantRoles,
            'locationCode' => Valuesets::ServiceRequestLocationCode,
            'reasonCode' => Codesystems::ICD10,
            'bodySite' => Valuesets::SNOMEDCTBodySite,
        ],
        'MedicationStatement' => [
            'status' => Valuesets::MedicationStatusCodes,
            'statusReason' => Valuesets::SNOMEDCTDrugTherapyStatusCodes,
            'category' => Valuesets::MedicationUsageCategoryCodes,
            'reasonCode' => Valuesets::ConditionProblemDiagnosisCodes
        ],
        'QuestionnaireResponse' => [
            'status' => Valuesets::QuestionnaireResponseStatus
        ],
        'QuestionnaireResponseItemAnswer' => [
            'valueCoding' => Valuesets::QuestionnaireAnswerCodes
        ],
        'Address' => [
            'use' => Codesystems::AddressUse,
            'type' => Codesystems::AddressType,
            'country' => Codesystems::ISO3166,
            'administrativeCode' => Codesystems::AdministrativeArea,
        ],
        'Age' => [
            'comparator' => Valuesets::Comparators
        ],
        'Attachment' => [
            'contentType' => Codesystems::MimeTypes,
            'language' => Codesystems::BCP47,
        ],
        'ContactPoint' => [
            'system' => Codesystems::ContactPointSystem,
            'use' => Codesystems::ContactPointUse,
        ],
        'Dosage' => [
            'additionalInstruction' => Valuesets::SNOMEDCTAdditionalDosageInstructions,
            'site' => Valuesets::SNOMEDCTAnatomicalStructureForAdministrationSiteCodes,
            'route' => Valuesets::DosageRoute,
            'method' => Valuesets::SNOMEDCTAdministrationMethodCodes,
        ],
        'DoseAndRate' => [
            'type' => Valuesets::DoseAndRateType,
            'dose' => [
                Codesystems::UCUM,
                Valuesets::MedicationIngredientStrengthDenominator
            ],
            'rate' => [
                Codesystems::UCUM,
                Valuesets::MedicationIngredientStrengthDenominator
            ]
        ],
        'Duration' => [
            'comparator' => Valuesets::Comparators,
        ],
        'HumanName' => [
            'use' => Codesystems::NameUse,
        ],
        'Identifier' => [
            'use' => Codesystems::IdentifierUse,
            'type' => Codesystems::v20203,
        ],
        'Narrative' => [
            'status' => Codesystems::NarrativeStatus,
        ],
        'Quantity' => [
            'comparator' => Valuesets::Comparators,
        ],
        'TimingRepeat' => [
            'durationUnit' => Valuesets::UnitsOfTime,
            'periodUnit' => Valuesets::UnitsOfTime,
            'dayOfWeek' => Valuesets::DaysOfWeek,
            'when' => Valuesets::EventTiming,
        ]
    ]
];
