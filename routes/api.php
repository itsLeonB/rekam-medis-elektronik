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
use App\Http\Controllers\UserController;

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
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{user_id}', [UserController::class, 'show'])->name('users.show');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::put('/users/{user_id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user_id}', [UserController::class, 'destroy'])->name('users.destroy');


// Local DB resource endpoint
Route::get('/{res_type}', [ResourceController::class, 'index'])->name('resource.index');

// Organization resource endpoint
Route::get('/organization/{res_id}', [OrganizationController::class, 'show'])->name('organization.show');
Route::post('/organization', [OrganizationController::class, 'store'])->name('organization.store');
Route::put('/organization/{satusehat_id}', [OrganizationController::class, 'update'])->name('organization.update');

// Location resource endpoint
Route::get('/location/{res_id}', [LocationController::class, 'show'])->name('location.show');
Route::post('/location', [LocationController::class, 'store'])->name('location.store');
Route::put('/location/{satusehat_id}', [LocationController::class, 'update'])->name('location.update');

// Patient resource endpoint
Route::get('/patient/{res_id}', [PatientController::class, 'show'])->name('patient.show');
Route::post('/patient', [PatientController::class, 'store'])->name('patient.store');

// Practitioner resource endpoint
Route::get('/practitioner/{res_id}', [PractitionerController::class, 'show'])->name('practitioner.show');

// Encounter resource endpoint
Route::get('/encounter/{res_id}', [EncounterController::class, 'show'])->name('encounter.show');
Route::post('/encounter', [EncounterController::class, 'store'])->name('encounter.store');
Route::put('/encounter/{satusehat_id}', [EncounterController::class, 'update'])->name('encounter.update');

// Condition resource endpoint
Route::get('/condition/{res_id}', [ConditionController::class, 'show'])->name('condition.show');
Route::post('/condition', [ConditionController::class, 'store'])->name('condition.store');
Route::put('/condition/{satusehat_id}', [ConditionController::class, 'update'])->name('condition.update');

// Observation resource endpoint
Route::get('/observation/{res_id}', [ObservationController::class, 'show'])->name('observation.show');
Route::post('/observation', [ObservationController::class, 'store'])->name('observation.store');
Route::put('/observation/{satusehat_id}', [ObservationController::class, 'update'])->name('observation.update');

// Procedure resource endpoint
Route::get('/procedure/{res_id}', [ProcedureController::class, 'show'])->name('procedure.show');
Route::post('/procedure', [ProcedureController::class, 'store'])->name('procedure.store');
Route::put('/procedure/{satusehat_id}', [ProcedureController::class, 'update'])->name('procedure.update');

// Medication resource endpoint
Route::get('/medication/{res_id}', [MedicationController::class, 'show'])->name('medication.show');
Route::post('/medication', [MedicationController::class, 'store'])->name('medication.store');
Route::put('/medication/{satusehat_id}', [MedicationController::class, 'update'])->name('medication.update');

// MedicationRequest resource endpoint
Route::get('/medicationrequest/{res_id}', [MedicationRequestController::class, 'show'])->name('medicationrequest.show');
Route::post('/medicationrequest', [MedicationRequestController::class, 'store'])->name('medicationrequest.store');
Route::put('/medicationrequest/{satusehat_id}', [MedicationRequestController::class, 'update'])->name('medicationrequest.update');

// Composition resource endpoint
Route::get('/composition/{res_id}', [CompositionController::class, 'show'])->name('composition.show');
Route::post('/composition', [CompositionController::class, 'store'])->name('composition.store');
Route::put('/composition/{satusehat_id}', [CompositionController::class, 'update'])->name('composition.update');

// AllergyIntolerance resource endpoint
Route::get('/allergyintolerance/{res_id}', [AllergyIntoleranceController::class, 'show'])->name('allergyintolerance.show');
Route::post('/allergyintolerance', [AllergyIntoleranceController::class, 'store'])->name('allergyintolerance.store');
Route::put('/allergyintolerance/{satusehat_id}', [AllergyIntoleranceController::class, 'update'])->name('allergyintolerance.update');

// ClinicalImpression resource endpoint
Route::get('/clinicalimpression/{res_id}', [ClinicalImpressionController::class, 'show'])->name('clinicalimpression.show');
Route::post('/clinicalimpression', [ClinicalImpressionController::class, 'store'])->name('clinicalimpression.store');
Route::put('/clinicalimpression/{satusehat_id}', [ClinicalImpressionController::class, 'update'])->name('clinicalimpression.update');

// ServiceRequest resource endpoint
Route::get('/servicerequest/{res_id}', [ServiceRequestController::class, 'show'])->name('servicerequest.show');
Route::post('/servicerequest', [ServiceRequestController::class, 'store'])->name('servicerequest.store');
Route::put('/servicerequest/{satusehat_id}', [ServiceRequestController::class, 'update'])->name('servicerequest.update');

// MedicationStatement resource endpoint
Route::get('/medicationstatement/{res_id}', [MedicationStatementController::class, 'show'])->name('medicationstatement.show');
Route::post('/medicationstatement', [MedicationStatementController::class, 'store'])->name('medicationstatement.store');
Route::put('/medicationstatement/{satusehat_id}', [MedicationStatementController::class, 'update'])->name('medicationstatement.update');

// QuestionnaireResponse resource endpoint
Route::get('/questionnaireresponse/{res_id}', [QuestionnaireResponseController::class, 'show'])->name('questionnaireresponse.show');
Route::post('/questionnaireresponse', [QuestionnaireResponseController::class, 'store'])->name('questionnaireresponse.store');
Route::put('/questionnaireresponse/{satusehat_id}', [QuestionnaireResponseController::class, 'update'])->name('questionnaireresponse.update');


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
