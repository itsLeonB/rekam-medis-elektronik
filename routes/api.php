<?php

use App\Http\Resources\PatientResource;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
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


// SATUSEHAT resource API
// Route::middleware(['satusehat'])->group(function () {
//     Route::get('/satusehat/{resourceType}/{satusehatId}', [SatusehatResourceController::class, 'getResource']);
// });

Route::group(['middleware' => ['web']], function () {
    Route::get('/satusehat/{resourceType}/{satusehatId}', [SatusehatResourceController::class, 'getResource']);
    Route::get('/satusehat/accesstoken', [SatusehatTokenController::class, 'getAccessToken']);
});


// Patient resource API
Route::get('/patient/{satusehat_id}', function ($satusehat_id) {
    return new PatientResource(
        Resource::where('satusehat_id', $satusehat_id)->firstOrFail()
    );
});

Route::post('/patient', function (Request $request) {
    $patient = Resource::create($request->all());

    return (new PatientResource($patient))
        ->response()
        ->setStatusCode(201);
});
