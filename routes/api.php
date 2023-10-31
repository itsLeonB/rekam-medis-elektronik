<?php

use App\Http\Controllers\EncounterController;
use App\Http\Resources\PatientResource;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SatusehatController;
use App\Http\Controllers\SatusehatResourceController;
use App\Http\Controllers\SatusehatTokenController;
use App\Http\Resources\FhirResource;

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
Route::get('/{res_type}', [ResourceController::class, 'indexResource']);
Route::get('/{res_type}/{satusehat_id}', [ResourceController::class, 'getResource']);

// Patient resource endpoint
Route::post('/patient/create', [PatientController::class, 'postPatient']);

// Encounter resource endpoint
Route::post('/encounter/create', [EncounterController::class, 'postEncounter']);

// Testing endpoint
Route::get('/test-get/{res_type}/{satusehat_id}', [ResourceController::class, 'testResource']);
Route::get('/test-create/encounter/create', [EncounterController::class, 'testResource']);
