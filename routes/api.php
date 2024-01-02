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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekamMedisController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SatusehatController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserManagementController;

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


// SATUSEHAT resource endpoint
Route::get('/satusehat/{res_type}/{res_id}', [SatusehatController::class, 'get'])->name('satusehat.get');
Route::post('/satusehat/{res_type}', [SatusehatController::class, 'post'])->name('satusehat.post');
Route::put('/satusehat/{res_type}/{res_id}', [SatusehatController::class, 'put'])->name('satusehat.put');


// Web APIs

// Profile
Route::get('/profile-details', [ProfileController::class, 'getProfile'])->name('profile.details');

// Daftar pasien untuk view Rawat Jalan dan Rawat Inap
Route::get('/daftar-pasien/{class}/{serviceType}', [DaftarPasienController::class, 'getDaftarPasien'])->name('daftar-pasien.index');

// Rekam Medis
Route::get('/daftar-rekam-medis', [RekamMedisController::class, 'index'])->name('rekam-medis.index');
Route::get('/rekam-medis/{patient_id}', [RekamMedisController::class, 'show'])->name('rekam-medis.show');

// Dashboard Analytics
Route::get('/analytics/pasien-hari-ini', [AnalyticsController::class, 'getTodayEncounters'])->name('analytics.pasien-hari-ini');
Route::get('/analytics/pasien-baru-bulan-ini', [AnalyticsController::class, 'getThisMonthNewPatients'])->name('analytics.pasien-baru-bulan-ini');
Route::get('/analytics/jumlah-pasien', [AnalyticsController::class, 'countPatients'])->name('analytics.jumlah-pasien');
Route::get('/analytics/pasien-per-bulan', [AnalyticsController::class, 'getEncountersPerMonth'])->name('analytics.pasien-per-bulan');
Route::get('/analytics/sebaran-usia-pasien', [AnalyticsController::class, 'getPatientAgeGroups'])->name('analytics.sebaran-usia-pasien');

// Users dashboard (Super admin)
Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
Route::get('/users/{user_id}', [UserManagementController::class, 'show'])->name('users.show');
Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
Route::put('/users/{user_id}', [UserManagementController::class, 'update'])->name('users.update');
Route::delete('/users/{user_id}', [UserManagementController::class, 'destroy'])->name('users.destroy');


// Local DB resource endpoint
Route::get('/{res_type}', [ResourceController::class, 'index'])->name('resource.index');

// Organization resource endpoint
Route::get('/organization/{satusehat_id}', [OrganizationController::class, 'show'])->name('Organization.show');
Route::post('/organization', [OrganizationController::class, 'store'])->name('Organization.store');
Route::put('/organization/{satusehat_id}', [OrganizationController::class, 'update'])->name('Organization.update');

// Location resource endpoint
Route::get('/location/{satusehat_id}', [LocationController::class, 'show'])->name('Location.show');
Route::post('/location', [LocationController::class, 'store'])->name('Location.store');
Route::put('/location/{satusehat_id}', [LocationController::class, 'update'])->name('Location.update');

// Patient resource endpoint
Route::get('/patient/{satusehat_id}', [PatientController::class, 'show'])->name('Patient.show');
Route::post('/patient', [PatientController::class, 'store'])->name('Patient.store');

// Practitioner resource endpoint
Route::get('/practitioner/{satusehat_id}', [PractitionerController::class, 'show'])->name('Practitioner.show');
Route::post('/practitioner', [PractitionerController::class, 'store'])->name('Practitioner.store');

// Encounter resource endpoint
Route::get('/encounter/{satusehat_id}', [EncounterController::class, 'show'])->name('Encounter.show');
Route::post('/encounter', [EncounterController::class, 'store'])->name('Encounter.store');
Route::put('/encounter/{satusehat_id}', [EncounterController::class, 'update'])->name('Encounter.update');

// Condition resource endpoint
Route::get('/condition/{satusehat_id}', [ConditionController::class, 'show'])->name('Condition.show');
Route::post('/condition', [ConditionController::class, 'store'])->name('Condition.store');
Route::put('/condition/{satusehat_id}', [ConditionController::class, 'update'])->name('Condition.update');

// Observation resource endpoint
Route::get('/observation/{satusehat_id}', [ObservationController::class, 'show'])->name('Observation.show');
Route::post('/observation', [ObservationController::class, 'store'])->name('Observation.store');
Route::put('/observation/{satusehat_id}', [ObservationController::class, 'update'])->name('Observation.update');

// Procedure resource endpoint
Route::get('/procedure/{satusehat_id}', [ProcedureController::class, 'show'])->name('Procedure.show');
Route::post('/procedure', [ProcedureController::class, 'store'])->name('Procedure.store');
Route::put('/procedure/{satusehat_id}', [ProcedureController::class, 'update'])->name('Procedure.update');

// Medication resource endpoint
Route::get('/medication/{satusehat_id}', [MedicationController::class, 'show'])->name('Medication.show');
Route::post('/medication', [MedicationController::class, 'store'])->name('Medication.store');
Route::put('/medication/{satusehat_id}', [MedicationController::class, 'update'])->name('Medication.update');

// MedicationRequest resource endpoint
Route::get('/medicationrequest/{satusehat_id}', [MedicationRequestController::class, 'show'])->name('MedicationRequest.show');
Route::post('/medicationrequest', [MedicationRequestController::class, 'store'])->name('MedicationRequest.store');
Route::put('/medicationrequest/{satusehat_id}', [MedicationRequestController::class, 'update'])->name('MedicationRequest.update');

// Composition resource endpoint
Route::get('/composition/{satusehat_id}', [CompositionController::class, 'show'])->name('Composition.show');
Route::post('/composition', [CompositionController::class, 'store'])->name('Composition.store');
Route::put('/composition/{satusehat_id}', [CompositionController::class, 'update'])->name('Composition.update');

// AllergyIntolerance resource endpoint
Route::get('/allergyintolerance/{satusehat_id}', [AllergyIntoleranceController::class, 'show'])->name('AllergyIntolerance.show');
Route::post('/allergyintolerance', [AllergyIntoleranceController::class, 'store'])->name('AllergyIntolerance.store');
Route::put('/allergyintolerance/{satusehat_id}', [AllergyIntoleranceController::class, 'update'])->name('AllergyIntolerance.update');

// ClinicalImpression resource endpoint
Route::get('/clinicalimpression/{satusehat_id}', [ClinicalImpressionController::class, 'show'])->name('ClinicalImpression.show');
Route::post('/clinicalimpression', [ClinicalImpressionController::class, 'store'])->name('ClinicalImpression.store');
Route::put('/clinicalimpression/{satusehat_id}', [ClinicalImpressionController::class, 'update'])->name('ClinicalImpression.update');

// ServiceRequest resource endpoint
Route::get('/servicerequest/{satusehat_id}', [ServiceRequestController::class, 'show'])->name('ServiceRequest.show');
Route::post('/servicerequest', [ServiceRequestController::class, 'store'])->name('ServiceRequest.store');
Route::put('/servicerequest/{satusehat_id}', [ServiceRequestController::class, 'update'])->name('ServiceRequest.update');

// MedicationStatement resource endpoint
Route::get('/medicationstatement/{satusehat_id}', [MedicationStatementController::class, 'show'])->name('MedicationStatement.show');
Route::post('/medicationstatement', [MedicationStatementController::class, 'store'])->name('MedicationStatement.store');
Route::put('/medicationstatement/{satusehat_id}', [MedicationStatementController::class, 'update'])->name('MedicationStatement.update');

// QuestionnaireResponse resource endpoint
Route::get('/questionnaireresponse/{satusehat_id}', [QuestionnaireResponseController::class, 'show'])->name('QuestionnaireResponse.show');
Route::post('/questionnaireresponse', [QuestionnaireResponseController::class, 'store'])->name('QuestionnaireResponse.store');
Route::put('/questionnaireresponse/{satusehat_id}', [QuestionnaireResponseController::class, 'update'])->name('QuestionnaireResponse.update');


// Testing endpoint
Route::get('/test/get/organization', [TestController::class, 'testOrganization']);
Route::get('/test/get/location', [TestController::class, 'testLocation']);
Route::get('/test/get/practitioner', [TestController::class, 'testPractitioner']);
Route::get('/test/get/patient', [TestController::class, 'testPatient']);
Route::get('/test/get/encounter', [TestController::class, 'testEncounter']);
Route::get('/test/get/condition', [TestController::class, 'testCondition']);
Route::get('/test/get/observation', [TestController::class, 'testObservation']);
Route::get('/test/get/procedure', [TestController::class, 'testProcedure']);
Route::get('/test/get/medication', [TestController::class, 'testMedication']);
Route::get('/test/get/medicationrequest', [TestController::class, 'testMedicationRequest']);
Route::get('/test/get/composition', [TestController::class, 'testComposition']);
Route::get('/test/get/allergyintolerance', [TestController::class, 'testAllergyIntolerance']);
Route::get('/test/get/clinicalimpression', [TestController::class, 'testClinicalImpression']);
Route::get('/test/get/servicerequest', [TestController::class, 'testServiceRequest']);
Route::get('/test/get/medicationstatement', [TestController::class, 'testMedicationStatement']);
Route::get('/test/get/questionnaireresponse', [TestController::class, 'testQuestionnaireResponse']);
