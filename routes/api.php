<?php

use App\Http\Controllers\AllergyIntoleranceController;
use App\Http\Controllers\ClinicalImpressionController;
use App\Http\Controllers\CompositionController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\EncounterController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\MedicationDispenseController;
use App\Http\Controllers\MedicationRequestController;
use App\Http\Controllers\ObservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SatusehatController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\TestController;

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
// Route::middleware(['satusehat'])->group(function () {
//     Route::get('/satusehat/{resourceType}/{satusehatId}', [SatusehatResourceController::class, 'getResource']);
// });

Route::group(['middleware' => ['web']], function () {
    Route::get('/satusehat/{resourceType}/{satusehatId}', [SatusehatController::class, 'getResource']);
});


// Local DB resource endpoint
Route::get('/{res_type}', [ResourceController::class, 'index']);
Route::get('/{res_type}/{res_id}', [ResourceController::class, 'show']);

// Patient resource endpoint
Route::post('/patient/create', [PatientController::class, 'store']);

// Encounter resource endpoint
Route::post('/encounter/create', [EncounterController::class, 'store']);

// Condition resource endpoint
Route::post('/condition/create', [ConditionController::class, 'store']);

// AllergyIntolerance resource endpoint
Route::post('/allergyintolerance/create', [AllergyIntoleranceController::class, 'store']);
Route::put('/allergyintolerance/{res_id}', [AllergyIntoleranceController::class, 'update']);

// Observation resource endpoint
Route::post('/observation/create', [ObservationController::class, 'store']);

// Procedure resource endpoint
Route::post('/procedure/create', [ProcedureController::class, 'store']);

// Medication resource endpoint
Route::post('/medication/create', [MedicationController::class, 'store']);

// MedicationRequest resource endpoint
Route::post('/medicationrequest/create', [MedicationRequestController::class, 'store']);

// MedicationDispense resource endpoint
Route::post('/medicationdispense/create', [MedicationDispenseController::class, 'store']);

// Composition resource endpoint
Route::post('/composition/create', [CompositionController::class, 'store']);

// ClinicalImpression resource endpoint
Route::post('/clinicalimpression/create', [ClinicalImpressionController::class, 'store']);
Route::put('/clinicalimpression/{res_id}', [ClinicalImpressionController::class, 'update']);

// ServiceRequest resource endpoint
Route::post('/servicerequest/create', [ServiceRequestController::class, 'store']);

// Testing endpoint
Route::get('/test-get/servicerequest/{satusehat_id}', [TestController::class, 'testServiceRequestResource']);
Route::get('/test-get/clinicalimpression/{satusehat_id}', [TestController::class, 'testClinicalImpressionResource']);
Route::get('/test-get/composition/{satusehat_id}', [TestController::class, 'testCompositionResource']);
Route::get('/test-get/medicationdispense/{satusehat_id}', [TestController::class, 'testMedicationDispenseResource']);
Route::get('/test-get/medicationrequest/{satusehat_id}', [TestController::class, 'testMedicationRequestResource']);
Route::get('/test-get/medication/{satusehat_id}', [TestController::class, 'testMedicationResource']);
Route::get('/test-get/procedure/{satusehat_id}', [TestController::class, 'testProcedureResource']);
Route::get('/test-get/allergyintolerance/{satusehat_id}', [TestController::class, 'testAllergyIntoleranceResource']);
Route::get('/test-get/observation/{satusehat_id}', [TestController::class, 'testObservationResource']);
Route::get('/test-create/encounter/create', [TestController::class, 'testCreateEncounter']);
Route::get('/test-create/condition/create', [TestController::class, 'testCreateCondition']);
