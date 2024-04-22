<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DaftarPasienController;
use App\Http\Controllers\EncounterFormController;
use App\Http\Controllers\Fhir\AllergyIntoleranceController;
use App\Http\Controllers\Fhir\ClinicalImpressionController;
use App\Http\Controllers\Fhir\CompositionController;
use App\Http\Controllers\Fhir\ConditionController;
use App\Http\Controllers\Fhir\EncounterController;
use App\Http\Controllers\Fhir\LocationController;
use App\Http\Controllers\Fhir\MedicationController;
use App\Http\Controllers\Fhir\MedicationRequestController;
use App\Http\Controllers\Fhir\MedicationStatementController;
use App\Http\Controllers\Fhir\ObservationController;
use App\Http\Controllers\Fhir\OrganizationController;
use App\Http\Controllers\Fhir\PatientController;
use App\Http\Controllers\Fhir\PractitionerController;
use App\Http\Controllers\Fhir\ProcedureController;
use App\Http\Controllers\Fhir\QuestionnaireResponseController;
use App\Http\Controllers\Fhir\ResourceController;
use App\Http\Controllers\Fhir\ServiceRequestController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\SatusehatController;
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


# Admin
Route::middleware('auth')->group(function () {
    # Home
    Route::get('/home', function () {
        return Inertia::render('Dashboard');
    })->name('home.index');

    # Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/details', [ProfileController::class, 'details'])->name('profile.details');

    # Rawat Jalan
    Route::get('/rawat-jalan', function () {
        return Inertia::render('RawatJalan/RawatJalan');
    })->name('rawatjalan');
    Route::get('/rawat-jalan/details/{encounter_satusehat_id}', function ($encounter_satusehat_id) {
        return Inertia::render('RawatJalan/RawatJalanDetails', ['encounter_satusehat_id' => $encounter_satusehat_id]);
    })->name('rawatjalan.details');

    # Rawat Inap
    Route::get('/rawat-inap', function () {
        return Inertia::render('RawatInap/RawatInap');
    })->name('rawatinap');
    Route::get('/rawat-inap/details/{encounter_satusehat_id}', function ($encounter_satusehat_id) {
        return Inertia::render('RawatInap/RawatInapDetails', ['encounter_satusehat_id' => $encounter_satusehat_id]);
    })->name('rawatinap.details');

    # Gawat Darurat
    Route::get('/gawat-darurat', function () {
        return Inertia::render('GawatDarurat/GawatDarurat');
    })->name('gawatdarurat');
    Route::get('/gawat-darurat/details/{encounter_satusehat_id}', function ($encounter_satusehat_id) {
        return Inertia::render('GawatDarurat/GawatDaruratDetails', ['encounter_satusehat_id' => $encounter_satusehat_id]);
    })->name('gawatdarurat.details');

    # Rekam Medis
    Route::get('/rekam-medis-pasien', function () {
        return Inertia::render('RekamMedis/RekamMedis');
    })->name('rekammedis');
    Route::get('/rekam-medis-pasien/details/{patient_satusehat_id}', function ($patient_satusehat_id) {
        return Inertia::render('RekamMedis/RekamMedisDetails', ['patient_satusehat_id' => $patient_satusehat_id]);
    })->name('rekammedis.details');

    # Daftar (admin and perekam medis)
    Route::middleware('role:admin|perekammedis')->group(function () {
        Route::get('/rekam-medis-pasien/daftar', function () {
            return Inertia::render('RekamMedis/TambahRekamMedis');
        })->name('rekammedis.tambah');
        Route::get('/gawat-darurat/daftar', function () {
            return Inertia::render('GawatDarurat/DaftarGawatDarurat');
        })->name('gawatdarurat.daftar');
        Route::get('/rawat-inap/daftar', function () {
            return Inertia::render('RawatInap/DaftarRawatInap');
        })->name('rawatinap.daftar');
        Route::get('/rawat-jalan/daftar', function () {
            return Inertia::render('RawatJalan/DaftarRawatJalan');
        })->name('rawatjalan.daftar');
    });

    # User Management (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/user-management', function () {
            return Inertia::render('UserManagement/UserManagement');
        })->name('usermanagement');
        Route::get('/user-management/details/{user_id}', function ($user_id) {
            return Inertia::render('UserManagement/UserDetails', ['user_id' => $user_id]);
        })->name('usermanagement.details');
        Route::get('/user-management/tambah-user', function () {
            return Inertia::render('UserManagement/TambahUser');
        })->name('usermanagement.tambah');
        Route::get('/user-management/edit-user/{user_id}', function ($user_id) {
            return Inertia::render('UserManagement/EditUser', ['user_id' => $user_id]);
        })->name('usermanagement.edit');
    });

    // Finance
    Route::get('/finance', function () {
        return Inertia::render('Finance/Finance');
    })->name('finance');
    Route::get('/finance/invoice', function () {
        return Inertia::render('Finance/InvoiceIndex');
    })->name('finance.invoice');
    Route::get('/finance/invoice/create', function () {
        return Inertia::render('Finance/FormInvoice');
    })->name('finance.newinvoice');
});

// APIs
Route::middleware('auth')->group(function () {
    // Endpoint untuk Integrasi SATUSEHAT
    Route::group(['prefix' => 'integration', 'as' => 'integration.'], function () {
        // Get resource dan simpan local
        Route::get('/{res_type}/{satusehat_id}', [IntegrationController::class, 'show'])->name('show');
        // Save resource dan kirim ke SATUSEHAT
        Route::post('/{res_type}', [IntegrationController::class, 'store'])->name('store');
        // Update resource dan kirim ke SATUSEHAT
        Route::put('/{res_type}/{satusehat_id}', [IntegrationController::class, 'update'])->name('update');
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

<<<<<<< HEAD
    // Untuk QuestionnaireResponse.item.item.answer.valueCoding
    Route::group(['prefix' => 'questionnaire', 'as' => 'questionnaire.'], function () {
        // Untuk lokasi kecelakaan
        Route::get('/lokasi-kecelakaan', [TerminologyController::class, 'returnQuestionLokasiKecelakaan'])->name('lokasi-kecelakaan');
        // Untuk poli tujuan
        Route::get('/poli-tujuan', [TerminologyController::class, 'returnQuestionPoliTujuan'])->name('poli-tujuan');
        // Lainnya
        Route::get('/other', [TerminologyController::class, 'returnQuestionOther'])->name('other');
=======
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
>>>>>>> 7eebbab728597826ccdb377ee2bc3577f2645459
    });

    // Untuk Medication.code atau MedicationIngredient.itemCodeableConcept
    Route::get('/medication', [SatusehatController::class, 'searchKfaProduct'])->name('medication');

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

    // Resource manipulation (only for BE)
    Route::group(['prefix' => 'resource', 'as' => 'resource.'], function () {
        Route::get('/{res_type}/{res_id}', [SatusehatController::class, 'show'])->name('show');
        Route::post('/{res_type}', [SatusehatController::class, 'store'])->name('store');
        Route::put('/{res_type}/{res_id}', [SatusehatController::class, 'update'])->name('update');
    });
});

// Endpoint untuk local DB manipulation (only for BE)
Route::group(['prefix' => 'local', 'as' => 'local.'], function () {
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

<<<<<<< HEAD
    // QuestionnaireResponse resource endpoint
    Route::get('/questionnaireresponse/{satusehat_id}', [QuestionnaireResponseController::class, 'show'])->name('questionnaireresponse.show');
    Route::post('/questionnaireresponse', [QuestionnaireResponseController::class, 'store'])->name('questionnaireresponse.store');
    Route::put('/questionnaireresponse/{satusehat_id}', [QuestionnaireResponseController::class, 'update'])->name('questionnaireresponse.update');
});
// });
=======
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
    });
});
>>>>>>> 7eebbab728597826ccdb377ee2bc3577f2645459

require __DIR__ . '/auth.php';
