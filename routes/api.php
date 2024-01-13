<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DaftarPasienController;
use App\Http\Controllers\Fhir\{
    AllergyIntoleranceController,
    ClinicalImpressionController,
    CompositionController,
    ConditionController,
    EncounterController,
    LocationController,
    MedicationController,
    MedicationRequestController,
    MedicationStatementController,
    ObservationController,
    OrganizationController,
    PatientController,
    PractitionerController,
    ProcedureController,
    QuestionnaireResponseController,
    ResourceController,
    ServiceRequestController,
};
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\RekamMedisController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SatusehatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Integration endpoint
Route::get('/integration/{res_type}/{satusehat_id}', [IntegrationController::class, 'show'])->name('integration.show');
Route::post('/integration/{res_type}', [IntegrationController::class, 'store'])->name('integration.store');
Route::put('/integration/{res_type}/{satusehat_id}', [IntegrationController::class, 'update'])->name('integration.update');

// Satusehat resource endpoint
Route::prefix('/satusehat/resource')->group(function () {
    Route::get('/{res_type}/{res_id}', [SatusehatController::class, 'show'])->name('satusehat.resource.show');
    Route::post('/{res_type}', [SatusehatController::class, 'store'])->name('satusehat.resource.store');
    Route::put('/{res_type}/{res_id}', [SatusehatController::class, 'update'])->name('satusehat.resource.update');
});

// SATUSEHAT consent endpoint
Route::get('/satusehat/consent/{patient_id}', [SatusehatController::class, 'readConsent'])->name('satusehat.consent.show');
Route::post('/satusehat/consent', [SatusehatController::class, 'updateConsent'])->name('satusehat.consent.store');

// Kamus Farmasi dan Alat Kesehatan
Route::get('/kfa', [SatusehatController::class, 'searchKfaProduct'])->name('kfa');

// SATUSEHAT search resource endpoint
Route::group(['prefix' => 'satusehat/search', 'as' => 'satusehat.search.'], function () {
    Route::get('/practitioner', [SatusehatController::class, 'searchPractitioner'])->name('practitioner');
    Route::get('/organization', [SatusehatController::class, 'searchOrganization'])->name('organization');
    Route::get('/location', [SatusehatController::class, 'searchLocation'])->name('location');
    Route::get('/patient', [SatusehatController::class, 'searchPatient'])->name('patient');
    Route::get('/encounter', [SatusehatController::class, 'searchEncounter'])->name('encounter');
    Route::get('/condition', [SatusehatController::class, 'searchCondition'])->name('condition');
    Route::get('/observation', [SatusehatController::class, 'searchObservation'])->name('observation');
    Route::get('/procedure', [SatusehatController::class, 'searchProcedure'])->name('procedure');
    Route::get('/medicationrequest', [SatusehatController::class, 'searchMedicationRequest'])->name('medicationrequest');
    Route::get('/composition', [SatusehatController::class, 'searchComposition'])->name('composition');
    Route::get('/allergyintolerance', [SatusehatController::class, 'searchAllergyIntolerance'])->name('allergyintolerance');
    Route::get('/clinicalimpression', [SatusehatController::class, 'searchClinicalImpression'])->name('clinicalimpression');
    Route::get('/servicerequest', [SatusehatController::class, 'searchServiceRequest'])->name('servicerequest');
    Route::get('/medicationstatement', [SatusehatController::class, 'searchMedicationStatement'])->name('medicationstatement');
    Route::get('/questionnaireresponse', [SatusehatController::class, 'searchQuestionnaireResponse'])->name('questionnaireresponse');
});

// Daftar Pasien
Route::group(['prefix' => 'daftar-pasien', 'as' => 'daftar-pasien.'], function () {
    Route::get('/rawat-jalan/{serviceType}', [DaftarPasienController::class, 'getDaftarRawatJalan'])->name('rawat-jalan');
    Route::get('/rawat-inap/{serviceType}', [DaftarPasienController::class, 'getDaftarRawatInap'])->name('rawat-inap');
    Route::get('/igd', [DaftarPasienController::class, 'getDaftarIgd'])->name('igd');
});

// Rekam Medis
Route::get('/daftar-rekam-medis', [RekamMedisController::class, 'index'])->name('rekam-medis.index');
Route::get('/rekam-medis/{patient_id}', [RekamMedisController::class, 'show'])->name('rekam-medis.show');
Route::get('/rekam-medis/{patient_id}/update', [SatusehatController::class, 'updateRekamMedis'])->name('rekam-medis.update');

// Dashboard Analytics
Route::get('/analytics/pasien-dirawat', [AnalyticsController::class, 'getActiveEncounters'])->name('analytics.pasien-dirawat');
Route::get('/analytics/pasien-baru-bulan-ini', [AnalyticsController::class, 'getThisMonthNewPatients'])->name('analytics.pasien-baru-bulan-ini');
Route::get('/analytics/jumlah-pasien', [AnalyticsController::class, 'countPatients'])->name('analytics.jumlah-pasien');
Route::get('/analytics/pasien-per-bulan', [AnalyticsController::class, 'getEncountersPerMonth'])->name('analytics.pasien-per-bulan');
Route::get('/analytics/sebaran-usia-pasien', [AnalyticsController::class, 'getPatientAgeGroups'])->name('analytics.sebaran-usia-pasien');


// Local DB resource endpoint
Route::get('/{res_type}', [ResourceController::class, 'index'])->name('resource.index');

// Organization resource endpoint
Route::get('/organization/{satusehat_id}', [OrganizationController::class, 'show'])->name('organization.show');
Route::post('/organization', [OrganizationController::class, 'store'])->name('organization.store');
Route::put('/organization/{satusehat_id}', [OrganizationController::class, 'update'])->name('organization.update');

// Location resource endpoint
Route::get('/location/{satusehat_id}', [LocationController::class, 'show'])->name('location.show');
Route::post('/location', [LocationController::class, 'store'])->name('location.store');
Route::put('/location/{satusehat_id}', [LocationController::class, 'update'])->name('location.update');

// Patient resource endpoint
Route::get('/patient/{satusehat_id}', [PatientController::class, 'show'])->name('patient.show');
Route::post('/patient', [PatientController::class, 'store'])->name('patient.store');

// Practitioner resource endpoint
Route::get('/practitioner/{satusehat_id}', [PractitionerController::class, 'show'])->name('practitioner.show');
Route::post('/practitioner', [PractitionerController::class, 'store'])->name('practitioner.store');

// Encounter resource endpoint
Route::get('/encounter/{satusehat_id}', [EncounterController::class, 'show'])->name('encounter.show');
Route::post('/encounter', [EncounterController::class, 'store'])->name('encounter.store');
Route::put('/encounter/{satusehat_id}', [EncounterController::class, 'update'])->name('encounter.update');

// Condition resource endpoint
Route::get('/condition/{satusehat_id}', [ConditionController::class, 'show'])->name('condition.show');
Route::post('/condition', [ConditionController::class, 'store'])->name('condition.store');
Route::put('/condition/{satusehat_id}', [ConditionController::class, 'update'])->name('condition.update');

// Observation resource endpoint
Route::get('/observation/{satusehat_id}', [ObservationController::class, 'show'])->name('observation.show');
Route::post('/observation', [ObservationController::class, 'store'])->name('observation.store');
Route::put('/observation/{satusehat_id}', [ObservationController::class, 'update'])->name('observation.update');

// Procedure resource endpoint
Route::get('/procedure/{satusehat_id}', [ProcedureController::class, 'show'])->name('procedure.show');
Route::post('/procedure', [ProcedureController::class, 'store'])->name('procedure.store');
Route::put('/procedure/{satusehat_id}', [ProcedureController::class, 'update'])->name('procedure.update');

// Medication resource endpoint
Route::get('/medication/{satusehat_id}', [MedicationController::class, 'show'])->name('medication.show');
Route::post('/medication', [MedicationController::class, 'store'])->name('medication.store');
Route::put('/medication/{satusehat_id}', [MedicationController::class, 'update'])->name('medication.update');

// MedicationRequest resource endpoint
Route::get('/medicationrequest/{satusehat_id}', [MedicationRequestController::class, 'show'])->name('medicationrequest.show');
Route::post('/medicationrequest', [MedicationRequestController::class, 'store'])->name('medicationrequest.store');
Route::put('/medicationrequest/{satusehat_id}', [MedicationRequestController::class, 'update'])->name('medicationrequest.update');

// Composition resource endpoint
Route::get('/composition/{satusehat_id}', [CompositionController::class, 'show'])->name('composition.show');
Route::post('/composition', [CompositionController::class, 'store'])->name('composition.store');
Route::put('/composition/{satusehat_id}', [CompositionController::class, 'update'])->name('composition.update');

// AllergyIntolerance resource endpoint
Route::get('/allergyintolerance/{satusehat_id}', [AllergyIntoleranceController::class, 'show'])->name('allergyintolerance.show');
Route::post('/allergyintolerance', [AllergyIntoleranceController::class, 'store'])->name('allergyintolerance.store');
Route::put('/allergyintolerance/{satusehat_id}', [AllergyIntoleranceController::class, 'update'])->name('allergyintolerance.update');

// ClinicalImpression resource endpoint
Route::get('/clinicalimpression/{satusehat_id}', [ClinicalImpressionController::class, 'show'])->name('clinicalimpression.show');
Route::post('/clinicalimpression', [ClinicalImpressionController::class, 'store'])->name('clinicalimpression.store');
Route::put('/clinicalimpression/{satusehat_id}', [ClinicalImpressionController::class, 'update'])->name('clinicalimpression.update');

// ServiceRequest resource endpoint
Route::get('/servicerequest/{satusehat_id}', [ServiceRequestController::class, 'show'])->name('servicerequest.show');
Route::post('/servicerequest', [ServiceRequestController::class, 'store'])->name('servicerequest.store');
Route::put('/servicerequest/{satusehat_id}', [ServiceRequestController::class, 'update'])->name('servicerequest.update');

// MedicationStatement resource endpoint
Route::get('/medicationstatement/{satusehat_id}', [MedicationStatementController::class, 'show'])->name('medicationstatement.show');
Route::post('/medicationstatement', [MedicationStatementController::class, 'store'])->name('medicationstatement.store');
Route::put('/medicationstatement/{satusehat_id}', [MedicationStatementController::class, 'update'])->name('medicationstatement.update');

// QuestionnaireResponse resource endpoint
Route::get('/questionnaireresponse/{satusehat_id}', [QuestionnaireResponseController::class, 'show'])->name('questionnaireresponse.show');
Route::post('/questionnaireresponse', [QuestionnaireResponseController::class, 'store'])->name('questionnaireresponse.store');
Route::put('/questionnaireresponse/{satusehat_id}', [QuestionnaireResponseController::class, 'update'])->name('questionnaireresponse.update');

