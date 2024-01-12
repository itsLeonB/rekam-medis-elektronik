<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TerminologyController;
use App\Http\Controllers\UserManagementController;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->middleware([RedirectIfAuthenticated::class, 'guest']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', function () {
        return Inertia::render('Dashboard');
    })->name('home.index');
    Route::get('/rawat-jalan', function () {
        return Inertia::render('RawatJalan/RawatJalan');
    })->name('rawatjalan');
    Route::get('/rawat-inap', function () {
        return Inertia::render('RawatInap/RawatInap');
    })->name('rawatinap');
    Route::get('/rekam-medis', function () {
        return Inertia::render('RekamMedis/RekamMedis');
    })->name('rekammedis');
    Route::get('/user-management', function () {
        return Inertia::render('UserManagement/UserManagement');
    })->name('usermanagement');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/details', [ProfileController::class, 'details'])->name('profile.details');
});

Route::group(['middleware' => 'auth', 'prefix' => 'users', 'as' => 'users.'], function () {
    Route::get('/', [UserManagementController::class, 'index'])->name('index');
    Route::get('/{user_id}', [UserManagementController::class, 'show'])->name('show');
    Route::post('/', [UserManagementController::class, 'store'])->name('store');
    Route::put('/{user_id}', [UserManagementController::class, 'update'])->name('update');
    Route::delete('/{user_id}', [UserManagementController::class, 'destroy'])->name('destroy');
});

Route::group(['prefix' => 'terminologi', 'as' => 'terminologi.'], function () {
    Route::get('/icd10', [TerminologyController::class, 'getIcd10'])->name('icd10');
    Route::get('/icd9cm-procedure', [TerminologyController::class, 'getIcd9CmProcedure'])->name('icd9cm-procedure');
    Route::get('/loinc', [TerminologyController::class, 'getLoinc'])->name('loinc');
    Route::get('/snomed-ct', [TerminologyController::class, 'getSnomedCt'])->name('snomed-ct');
    Route::group(['prefix' => 'wilayah', 'as' => 'wilayah.'], function () {
        Route::get('/provinsi', [TerminologyController::class, 'getProvinsi'])->name('provinsi');
        Route::get('/kabko', [TerminologyController::class, 'getKabupatenKota'])->name('kabko');
        Route::get('/kecamatan', [TerminologyController::class, 'getKecamatan'])->name('kecamatan');
        Route::get('/kelurahan', [TerminologyController::class, 'getKelurahan'])->name('kelurahan');
    });
    Route::get('/bcp13', [TerminologyController::class, 'getBcp13'])->name('bcp13');
    Route::get('/bcp47', [TerminologyController::class, 'getBcp47'])->name('bcp47');
    Route::get('/iso3166', [TerminologyController::class, 'getIso3166'])->name('iso3166');
    Route::get('/ucum', [TerminologyController::class, 'getUcum'])->name('ucum');
});

require __DIR__ . '/auth.php';
