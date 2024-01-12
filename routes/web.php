<?php

use App\Http\Controllers\ProfileController;
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
})->middleware([RedirectIfAuthenticated::class, 'guest']);;

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

require __DIR__ . '/auth.php';
