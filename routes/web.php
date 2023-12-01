<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DaftarRekamMedisController;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PatientController;

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
})->middleware([RedirectIfAuthenticated::class, 'guest']);;

Route::get('/home', [DaftarRekamMedisController::class, 'index'])
    ->middleware(['auth'])->name('home.index');

// Route::get('/rawat-jalan', function () {
//     return Inertia::render('RawatJalan/RawatJalan');
// })->middleware(['auth', 'verified'])->name('rawatjalan');

Route::middleware(['auth', 'verified'])->group(function () {
   Route::get('/rawat-jalan', function () {return Inertia::render('RawatJalan/RawatJalan');})->name('rawatjalan');
   Route::get('/rawat-inap', function () {return Inertia::render('RawatInap/RawatInap');})->name('rawatinap');
   Route::get('/rekam-medis', function () {return Inertia::render('RekamMedis/RekamMedis');})->name('rekammedis');
   
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
