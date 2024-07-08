<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AnalyticsObatController;
use App\Http\Controllers\DaftarPasienController;
use App\Http\Controllers\EncounterFormController;
use App\Http\Controllers\IdentifierController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\SatusehatController;
use App\Http\Controllers\TerminologyController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\MedicineTransactionController;
use App\Http\Controllers\ExpertSystemController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\MedicationDispense;
use App\Http\Controllers\ObatController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\MedicineController;
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
});

# Rawat Jalan
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rawat-jalan', function () {
        return Inertia::render('RawatJalan/RawatJalan');
    })->name('rawatjalan');
    Route::get('/rawat-jalan/daftar', function () {
        return Inertia::render('RawatJalan/DaftarRawatJalan');
    })->name('rawatjalan.daftar');
    Route::get('/rawat-jalan/details/{encounter_satusehat_id}', function ($encounter_satusehat_id) {
        return Inertia::render('RawatJalan/RawatJalanDetails', ['encounter_satusehat_id' => $encounter_satusehat_id]);
    })->name('rawatjalan.details');
});

# Rawat Inap
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rawat-inap', function () {
        return Inertia::render('RawatInap/RawatInap');
    })->name('rawatinap');
    Route::get('/rawat-inap/daftar', function () {
        return Inertia::render('RawatInap/DaftarRawatInap');
    })->name('rawatinap.daftar');
    Route::get('/rawat-inap/details/{encounter_satusehat_id}', function ($encounter_satusehat_id) {
        return Inertia::render('RawatInap/RawatInapDetails', ['encounter_satusehat_id' => $encounter_satusehat_id]);
    })->name('rawatinap.details');
});

# Gawat Darurat
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/gawat-darurat', function () {
        return Inertia::render('GawatDarurat/GawatDarurat');
    })->name('gawatdarurat');
});

# Rekam Medis
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rekam-medis-pasien', function () {
        return Inertia::render('RekamMedis/RekamMedis');
    })->name('rekammedis');
    Route::get('/rekam-medis-pasien/details/{patient_satusehat_id}', function ($patient_satusehat_id) {
        return Inertia::render('RekamMedis/RekamMedisDetails', ['patient_satusehat_id' => $patient_satusehat_id]);
    })->name('rekammedis.details');
});


# Daftar (admin and perekam medis)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/rekam-medis-pasien/daftar', function () {
        return Inertia::render('RekamMedis/TambahRekamMedis');
    })->name('rekammedis.tambah');
});

# User Management
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user-management', function () {
        return Inertia::render('UserManagement/UserManagement');
    })->name('usermanagement');
    Route::get('/user-management/details/{user_id}', function ($user_id) {
        return Inertia::render('UserManagement/UserDetails', ['user_id' => $user_id]);
    })->name('usermanagement.details');
    Route::get('/user-management/tambah-user', function () {
        return Inertia::render('UserManagement/TambahUser');
    })->name('usermanagement.tambah');
});

# Transaksi
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/medicine-transaction', function () {
        return Inertia::render('MedicineTransaction/MedicineTransaction');
    })->name('medicinetransaction');
    Route::get('/medicine-transaction/form-transaction', function () {
        return Inertia::render('MedicineTransaction/FormTransaction');
    })->name('medicinetransaction.tambah');
});


# Medication
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/medication/table', function () {
        return Inertia::render('Medication/Medication');
    })->name('medication.table');
    Route::get('/medication/details/', function ($medication_id) {
        return Inertia::render('Medication/MedicationDetails', ['medication_id' => $medication_id]);
    })->name('medication.details');
    Route::get('/medication/tambah', function () {
        return Inertia::render('Medication/TambahMedication');
    })->name('medication.tambah');
    Route::get('/medication', function () {
        return Inertia::render('Medication/DashboardMedication');
    })->name('medication');
    Route::get('/medication/prescription', function () {
        return Inertia::render('Medication/MedicationDispense');
    })->name('medication.prescription');
    Route::get('/medication-dispense/details/{medication_dispense_id}', function ($medication_dispense_id) {
        return Inertia::render('Medication/MedicationDispenseDetails', ['medication_dispense_id' => $medication_dispense_id]);
    })->name('medicationDispense.details');
    // Route::get('/expertsystems', function () {
    //     return Inertia::render('Medication/ExpertSystem');
    // })->name('expertsystems.index');
});

# Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/details', [ProfileController::class, 'details'])->name('profile.details');
});

// APIs 
Route::middleware('auth')->group(function () {
    // Endpoint untuk Integrasi SATUSEHAT
    Route::group(['prefix' => 'integration', 'as' => 'integration.'], function () {
        // Get resource dan simpan local
        Route::get('/{resourceType}/{id}', [SatusehatController::class, 'integrationGet'])->name('show');
        // Save resource dan kirim ke SATUSEHAT
        Route::post('/{resourceType}', [SatusehatController::class, 'integrationPost'])->name('store');
        // Update resource dan kirim ke SATUSEHAT
        Route::put('/{resourceType}/{id}', [SatusehatController::class, 'integrationPut'])->name('update');
    });

    // Endpoint untuk View Rekam Medis
    Route::group(['prefix' => 'rekam-medis', 'as' => 'rekam-medis.'], function () {
        // Daftar rekam medis pasien
        Route::get('/', [RekamMedisController::class, 'index'])->name('index');
        // Detail rekam medis pasien
        Route::get('/{patient_id}', [RekamMedisController::class, 'show'])->name('show');
        // Pull update dari rekam medis pasien dari SATUSEHAT
        Route::get('/{patient_id}/update', [SatusehatController::class, 'updateRekamMedis'])->name('update');
    });

    // Endpoint untuk View Daftar pasien
    Route::group(['prefix' => 'daftar-pasien', 'as' => 'daftar-pasien.'], function () {
        // Daftar pasien rawat jalan
        Route::middleware('permission:akses poli umum')->get('/rawat-jalan/umum', [DaftarPasienController::class, 'getDaftarPoliUmum'])->name('rawat-jalan.umum');
        Route::middleware('permission:akses poli neurologi')->get('/rawat-jalan/neurologi', [DaftarPasienController::class, 'getDaftarPoliNeurologi'])->name('rawat-jalan.neurologi');
        Route::middleware('permission:akses poli obgyn')->get('/rawat-jalan/obgyn', [DaftarPasienController::class, 'getDaftarPoliObgyn'])->name('rawat-jalan.obgyn');
        Route::middleware('permission:akses poli gigi')->get('/rawat-jalan/gigi', [DaftarPasienController::class, 'getDaftarPoliGigi'])->name('rawat-jalan.gigi');
        Route::middleware('permission:akses poli kulit')->get('/rawat-jalan/kulit', [DaftarPasienController::class, 'getDaftarPoliKulit'])->name('rawat-jalan.kulit');
        Route::middleware('permission:akses poli ortopedi')->get('/rawat-jalan/ortopedi', [DaftarPasienController::class, 'getDaftarPoliOrtopedi'])->name('rawat-jalan.ortopedi');
        Route::middleware('permission:akses poli penyakit dalam')->get('/rawat-jalan/dalam', [DaftarPasienController::class, 'getDaftarPoliDalam'])->name('rawat-jalan.dalam');
        Route::middleware('permission:akses poli bedah')->get('/rawat-jalan/bedah', [DaftarPasienController::class, 'getDaftarPoliBedah'])->name('rawat-jalan.bedah');
        Route::middleware('permission:akses poli anak')->get('/rawat-jalan/anak', [DaftarPasienController::class, 'getDaftarPoliAnak'])->name('rawat-jalan.anak');
        // Daftar pasien rawat inap, serviceType per ruangan
        Route::get('/rawat-inap', [DaftarPasienController::class, 'getDaftarRawatInap'])->name('rawat-inap');
        // Daftar pasien IGD
        Route::get('/igd', [DaftarPasienController::class, 'getDaftarIgd'])->name('igd');
    });

    // Endpoint untuk View mediation
    Route::group(['prefix' => 'obat', 'as' => 'obat.'], function () {
        // Daftar obat
        Route::get('/', [ObatController::class, 'index'])->name('index');
        // Detail obat
        Route::get('/{medication_id}', [ObatController::class, 'show'])->name('show');
        // Pull update dari obat dari SATUSEHAT
        Route::get('/{medication_id}/update', [ObatController::class, 'updateObat'])->name('update');
        Route::post('/obat', [ObatController::class, 'store'])->name('store');
    });

    // Endpoint untuk View Medication
    Route::group(['prefix' => 'medication', 'as' => 'medication.'], function () {
        // Daftar pasien rawat jalan
        Route::middleware('permission:akses poli umum')->get('/rawat-jalan/umum', [DaftarPasienController::class, 'getDaftarPoliUmum'])->name('rawat-jalan.umum');
        Route::middleware('permission:akses poli neurologi')->get('/rawat-jalan/neurologi', [DaftarPasienController::class, 'getDaftarPoliNeurologi'])->name('rawat-jalan.neurologi');
        Route::middleware('permission:akses poli obgyn')->get('/rawat-jalan/obgyn', [DaftarPasienController::class, 'getDaftarPoliObgyn'])->name('rawat-jalan.obgyn');
        Route::middleware('permission:akses poli gigi')->get('/rawat-jalan/gigi', [DaftarPasienController::class, 'getDaftarPoliGigi'])->name('rawat-jalan.gigi');
        Route::middleware('permission:akses poli kulit')->get('/rawat-jalan/kulit', [DaftarPasienController::class, 'getDaftarPoliKulit'])->name('rawat-jalan.kulit');
        Route::middleware('permission:akses poli ortopedi')->get('/rawat-jalan/ortopedi', [DaftarPasienController::class, 'getDaftarPoliOrtopedi'])->name('rawat-jalan.ortopedi');
        Route::middleware('permission:akses poli penyakit dalam')->get('/rawat-jalan/dalam', [DaftarPasienController::class, 'getDaftarPoliDalam'])->name('rawat-jalan.dalam');
        Route::middleware('permission:akses poli bedah')->get('/rawat-jalan/bedah', [DaftarPasienController::class, 'getDaftarPoliBedah'])->name('rawat-jalan.bedah');
        Route::middleware('permission:akses poli anak')->get('/rawat-jalan/anak', [DaftarPasienController::class, 'getDaftarPoliAnak'])->name('rawat-jalan.anak');
        // Daftar pasien rawat inap, serviceType per ruangan
        // Daftar pasien IGD
        Route::get('/igd', [DaftarPasienController::class, 'getDaftarIgd'])->name('igd');
        Route::get('/medication/details/{code_medication}', function ($code_medication) {
            return Inertia::render('RekamMedis/RekamMedisDetails', ['medication_id' => $medication_id]);
        })->name('rekammedis.details');
    });

    // Endpoint untuk Dashboard Analytics
    Route::group(['prefix' => 'analytics', 'as' => 'analytics.'], function () {
        // Jumlah pasien sedang dirawat
        Route::get('/pasien-dirawat', [AnalyticsController::class, 'getActiveEncounters'])->name('pasien-dirawat');
        // Jumlah pasien baru mendaftar bulan ini
        Route::get('/pasien-baru-bulan-ini', [AnalyticsController::class, 'getThisMonthNewPatients'])->name('pasien-baru-bulan-ini');
        // Jumlah total pasien yang pernah dirawat
        Route::get('/jumlah-pasien', [AnalyticsController::class, 'countPatients'])->name('jumlah-pasien');
        // Jumlah pasien yang pernah dirawat per bulan untuk 12 bulan kebelakang
        Route::get('/pasien-per-bulan', [AnalyticsController::class, 'getEncountersPerMonth'])->name('pasien-per-bulan');
        // Jumlah pasien yang pernah dirawat berdasarkan usia
        Route::get('/sebaran-usia-pasien', [AnalyticsController::class, 'getPatientAgeGroups'])->name('sebaran-usia-pasien');

        // card :
        // - jumlah mendekati kadaluarsa exp kurang dari 1 bulan
        Route::get('/obat-mendekati-kadaluarsa', [AnalyticsObatController::class, 'getObatMendekatiKadaluarsa'])->name('obat-mendekati-kadaluarsa');

        // - jumlah stok sedikit - kosong
        Route::get('/obat-stok-sedikit', [AnalyticsObatController::class, 'getObatStokSedikit'])->name('obat-stok-sedikit');
        // - jumlah obat fast 
        Route::get('/obat-fast-moving', [AnalyticsObatController::class, 'getObatFastMoving'])->name('obat-fast-moving');
        
        // - jumlah obat penggunaan paling banyak
        Route::get('/obat-penggunaan-paling-banyak', [AnalyticsObatController::class, 'getObatPenggunaanPalingBanyak'])->name('obat-penggunaan-paling-banyak');

        // chart :
        // - line chart stok obat secara keseluruhan perbulan
        Route::get('/obat-transaksi-perbandingan-per-bulan', [AnalyticsObatController::class, 'getObatTransaksiPerbandinganPerBulan'])->name('obat-transaksi-perbandingan-per-bulan');

        // - pie chart distribusi stok obat berdasarkan jenis
        Route::get('/obat-persebaran-stok', [AnalyticsObatController::class, 'getObatPersebaranStok'])->name('obat-persebaran-stok');

    });

    // Endpoint untuk Formulir Perawatan
    Route::group(['prefix' => 'form', 'as' => 'form.'], function () {
        // Daftar nakes
        Route::get('/daftar/practitioner', [EncounterFormController::class, 'indexPractitioner'])->name('index.encounter');
        // Daftar ruangan/bed
        Route::get('/daftar/location', [EncounterFormController::class, 'indexLocation'])->name('index.location');
        // Reference Organization per layanan = rawat-jalan | rawat-inap | igd
        Route::get('/ref/organization/{layanan}', [EncounterFormController::class, 'getOrganization'])->name('ref.organization');
        Route::get('/ref/identifier/{layanan}/{res_type}', [IdentifierController::class, 'getIdentifier'])->name('ref.identifier');
    });

    // Endpoint untuk Data Kunjungan Pasien
    Route::get('/kunjungan/{resType}/{encounterId}', [RekamMedisController::class, 'getKunjunganData'])->name('kunjungan');

    // Endpoint untuk User Management
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        // Daftar user
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        // Detail user
        Route::get('/{user_id}', [UserManagementController::class, 'show'])->name('show');
        // Tambah user
        Route::post('/', [UserManagementController::class, 'store'])->name('store');
        // Update user
        Route::put('/{user_id}', [UserManagementController::class, 'update'])->name('update');
        // Hapus user
        Route::delete('/{user_id}', [UserManagementController::class, 'destroy'])->name('destroy');
        // Daftar roles
        Route::get('/get/roles', [UserManagementController::class, 'getRoles'])->name('roles');
    });

    // Endpoint untuk MedicineTransaction Management
    Route::group(['prefix' => 'medicinetransactions', 'as' => 'medicinetransactions.'], function () {
        // Daftar medicinetransaction
        Route::get('/', [MedicineTransactionController::class, 'index'])->name('index');
        // Detail medicinetransaction
        Route::get('/{id}', [MedicineTransactionController::class, 'show'])->name('show');
        // Tambah medicinetransaction
        Route::post('/', [MedicineTransactionController::class, 'store'])->name('store');
        // Update medicinetransaction
        Route::put('/{id}', [MedicineTransactionController::class, 'update'])->name('update');
        // Hapus medicinetransaction
        Route::delete('/{id}', [MedicineTransactionController::class, 'destroy'])->name('destroy');
        // Daftar roles
        Route::get('/get/roles', [MedicineTransactionController::class, 'getRoles'])->name('roles');
    });
    // Daftar medicine
    Route::get('/getmedicine', [MedicineTransactionController::class, 'getmedicine'])->name('getmedicine');

    // Endpoint kode terminologi
    Route::group(['prefix' => 'terminologi', 'as' => 'terminologi.'], function () {
        // Terminologi per atribut per resource type
        Route::get('/get', [TerminologyController::class, 'returnTerminologi'])->name('get');

        // Untuk Procedure.code
        Route::group(['prefix' => 'procedure', 'as' => 'procedure.'], function () {
            // Tindakan atau prosedur medis untuk keperluan klaim
            Route::get('/tindakan', [TerminologyController::class, 'returnProcedureTindakan'])->name('tindakan');
            // Prosedur medis seperti edukasi, perawatan terhadap bayi baru lahir
            Route::get('/edukasi-bayi', [TerminologyController::class, 'returnProcedureEdukasiBayi'])->name('edukasi-bayi');
            // Prosedur medis lainnya
            Route::get('/other', [TerminologyController::class, 'returnProcedureOther'])->name('other');
        });

        // Untuk Condition.code atau MedicationStatement.reasonCode
        Route::group(['prefix' => 'condition', 'as' => 'condition.'], function () {
            // Diagnosis pasien saat kunjungan
            Route::get('/kunjungan', [TerminologyController::class, 'returnConditionKunjungan'])->name('kunjungan');
            // Kondisi saat meninggalkan rumah sakit
            Route::get('/keluar', [TerminologyController::class, 'returnConditionKeluar'])->name('keluar');
            // Keluhan utama, kondisi pasien, temuan pemeriksaan klinis
            Route::get('/keluhan', [TerminologyController::class, 'returnConditionKeluhan'])->name('keluhan');
            // Riwayat penyakit pribadi
            Route::get('/riwayat-pribadi', [TerminologyController::class, 'returnConditionRiwayatPribadi'])->name('riwayat-pribadi');
            // Riwayat penyakit keluarga
            Route::get('/riwayat-keluarga', [TerminologyController::class, 'returnConditionRiwayatKeluarga'])->name('riwayat-keluarga');
        });

        // Untuk QuestionnaireResponse.item.item.answer.valueCoding
        Route::group(['prefix' => 'questionnaire', 'as' => 'questionnaire.'], function () {
            // Untuk lokasi kecelakaan
            Route::get('/lokasi-kecelakaan', [TerminologyController::class, 'returnQuestionLokasiKecelakaan'])->name('lokasi-kecelakaan');
            // Untuk poli tujuan
            Route::get('/poli-tujuan', [TerminologyController::class, 'returnQuestionPoliTujuan'])->name('poli-tujuan');
            // Lainnya
            Route::get('/other', [TerminologyController::class, 'returnQuestionOther'])->name('other');
        });

        // Untuk Medication.code atau MedicationIngredient.itemCodeableConcept
        Route::get('/medication', [SatusehatController::class, 'searchKfaProduct'])->name('medication');

        Route::get('/ingridients', [SatusehatController::class, 'searchKfaProduct'])->name('ingridients');

        // Endpoint codesystems
        Route::get('/icd10', [TerminologyController::class, 'getIcd10'])->name('icd10');
        Route::get('/icd9cm-procedure', [TerminologyController::class, 'getIcd9CmProcedure'])->name('icd9cm-procedure');
        Route::get('/loinc', [TerminologyController::class, 'getLoinc'])->name('loinc');
        Route::get('/snomed-ct', [TerminologyController::class, 'getSnomedCt'])->name('snomed-ct');
        Route::group(['prefix' => 'wilayah', 'as' => 'wilayah.'], function () {
            Route::get('/provinsi', [TerminologyController::class, 'getProvinsi'])->name('provinsi');
            Route::get('/kabko', [TerminologyController::class, 'getKabupatenKota'])->name('kabko');
            Route::get('/kotalahir', [TerminologyController::class, 'getKotaLahir'])->name('kotalahir');
            Route::get('/kecamatan', [TerminologyController::class, 'getKecamatan'])->name('kecamatan');
            Route::get('/kelurahan', [TerminologyController::class, 'getKelurahan'])->name('kelurahan');
        });
        Route::get('/bcp13', [TerminologyController::class, 'getBcp13'])->name('bcp13');
        Route::get('/bcp47', [TerminologyController::class, 'getBcp47'])->name('bcp47');
        Route::get('/iso3166', [TerminologyController::class, 'getIso3166'])->name('iso3166');
        Route::get('/ucum', [TerminologyController::class, 'getUcum'])->name('ucum');
    });

    // Endpoint untuk call API SATUSEHAT
    Route::group(['prefix' => 'satusehat', 'as' => 'satusehat.'], function () {
        // Consent
        Route::get('/consent/{patient_id}', [SatusehatController::class, 'readConsent'])->name('consent.show');
        Route::post('/consent', [SatusehatController::class, 'updateConsent'])->name('consent.store');

        // Kamus Farmasi dan Alat Kesehatan
        Route::get('/kfa', [SatusehatController::class, 'searchKfaProduct'])->name('kfa');

        // Search resource
        Route::group(['prefix' => 'search', 'as' => 'search.'], function () {
            Route::get('/practitioner', [SatusehatController::class, 'searchPractitioner'])->name('practitioner');
            Route::get('/organization', [SatusehatController::class, 'searchOrganization'])->name('organization');
            Route::get('/location', [SatusehatController::class, 'searchLocation'])->name('location');
            Route::get('/patient', [SatusehatController::class, 'searchPatient'])->name('patient');
            // Route::get('/encounter', [SatusehatController::class, 'searchEncounter'])->name('encounter');
            Route::get('/condition', [SatusehatController::class, 'searchCondition'])->name('condition');
            // Route::get('/observation', [SatusehatController::class, 'searchObservation'])->name('observation');
            // Route::get('/procedure', [SatusehatController::class, 'searchProcedure'])->name('procedure');
            Route::get('/medicationrequest', [SatusehatController::class, 'searchMedicationRequest'])->name('medicationrequest');
            // Route::get('/composition', [SatusehatController::class, 'searchComposition'])->name('composition');
            // Route::get('/allergyintolerance', [SatusehatController::class, 'searchAllergyIntolerance'])->name('allergyintolerance');
            // Route::get('/clinicalimpression', [SatusehatController::class, 'searchClinicalImpression'])->name('clinicalimpression');
            // Route::get('/servicerequest', [SatusehatController::class, 'searchServiceRequest'])->name('servicerequest');
            // Route::get('/medicationstatement', [SatusehatController::class, 'searchMedicationStatement'])->name('medicationstatement');
            // Route::get('/questionnaireresponse', [SatusehatController::class, 'searchQuestionnaireResponse'])->name('questionnaireresponse');
        });
    });

    // Local resource manipulation
    Route::group(['prefix' => 'resources', 'as' => 'resources.'], function () {
        Route::get('/{resType}', [ResourceController::class, 'index'])->name('index');
        Route::post('/{resType}', [ResourceController::class, 'store'])->name('store');
        Route::get('/{resType}/{id}', [ResourceController::class, 'show'])->name('show');
        Route::put('/{resType}/{id}', [ResourceController::class, 'update'])->name('update');
        Route::delete('/{resType}/{id}', [ResourceController::class, 'destroy'])->name('destroy');
    });
    Route::middleware('auth')->group(function () {
        Route::get('/expertsystems', function () {
            return Inertia::render('ExpertSystem/index');
        })->name('expertsystems.index');

        // end-point expert system
        Route::get('/rule-peresepan-obat/{id}', [ExpertSystemController::class, 'rulePeresepanStore'])->name('ruleperesepan.store');
        Route::get('/get-keluhan/{id}', [ExpertSystemController::class, 'getKeluhan'])->name('get.keluhan');
        Route::get('/get-alergi/{id}', [ExpertSystemController::class, 'getAlergi'])->name('get.alergi');
        Route::get('/get-diagnosa/{id}', [ExpertSystemController::class, 'getDiagnosa'])->name('get.diagnosa');
        Route::get('/status-kehamilan/{id}', [ExpertSystemController::class, 'statusKehamilan'])->name('status.kehamilan');
        Route::get('/get-medication-req/{id}', [ExpertSystemController::class, 'getMedicationReq'])->name('get.medicationReq');
        Route::get('/kategori-umur/{id}', [ExpertSystemController::class, 'kategoriUmur'])->name('get.umur');
        Route::get('/data-fisik/{id}', [ExpertSystemController::class, 'dataFisik'])->name('get.dataFisik');
        Route::get('/rule/{rule}/{id}', [ExpertSystemController::class, 'rulePeresepanShow'])->name('ruleperesepan.show');
    });

    Route::group(['prefix' => 'medicine', 'as' => 'medicine.'], function () {
        Route::get('/', [MedicineController::class, 'index'])->name('index');
        Route::post('/', [MedicineController::class, 'store'])->name('store');
        Route::get('/{medicine_code}', [MedicineController::class, 'show'])->name('show');
        Route::put('/{medicine_code}', [MedicineController::class, 'update'])->name('update');
        Route::delete('/{medicine_code}', [MedicineController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'medicationDispense', 'as' => 'medicationDispense.'], function () {
        Route::get('/', [MedicationDispense::class, 'index'])->name('index');
        Route::get('/{medicationReq_id}', [MedicationDispense::class, 'show'])->name('show');
    });
});
Route::get('medicationOrg', [ExpertSystemController::class, 'indexMedication'])->name('get.medicationOrg');

require __DIR__ . '/auth.php';
