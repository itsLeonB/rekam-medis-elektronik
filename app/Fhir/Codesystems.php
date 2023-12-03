<?php

namespace App\Fhir;

use GuzzleHttp\Client;

class Codesystems
{
    public function searchSnomed(string $ecl, string $term, Client $client)
    {
        $client = new Client();

        $headers = [
            'Accept' => 'application/json',
            'Accept-Language' => 'en-X-900000000000509007,en-X-900000000000508004,en',
        ];

        $query = [
            'term' => $term,
            'ecl' => $ecl,
            'includeLeafFlag' => 'false',
            'form' => 'inferred',
            'offset' => 0,
            'limit' => 50,
        ];

        $response = $client->request('GET', Codesystems::SNOMEDCT['url'], [
            'headers' => $headers,
            'query' => $query,
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        return $body;
    }

    public const SNOMEDCT = [
        'system' => 'http://snomed.info/sct',
        'url' => 'https://browser.ihtsdotools.org/snowstorm/snomed-ct/MAIN/concepts'
    ];

    public const ICD10 = [
        'system' => 'http://hl7.org/fhir/sid/icd-10',
        'table' => 'codesystem_icd10',
    ];

    public const ConditionCategoryCodes = [
        'system' => 'http://terminology.hl7.org/CodeSystem/condition-category',
        'code' => ['problem-list-item', 'encounter-diagnosis'],
        'display' => ['problem-list-item' => 'Problem List Item', 'encounter-diagnosis' => 'Encounter Diagnosis'],
        'definition' =>  ["problem-list-item" => "Daftar keluhan/masalah yang dapat dikelola waktu ke waktu dan dapat diungkapkan oleh tenaga kesehatan, pasien, atau orang terkait", "encounter-diagnosis" => "Diagnosis pasien pada waktu tertentu dalam kunjungan"],
    ];

    public const ConditionVerificationStatus = [
        'system' => 'http://terminology.hl7.org/CodeSystem/condition-ver-status',
        'code' => ['unconfirmed', 'provisional', 'differential', 'confirmed', 'refuted', 'entered-in-error'],
        'display' => ['unconfirmed' => 'Unconfirmed', 'provisional' => 'Provisional', 'differential' => 'Differential', 'confirmed' => 'Confirmed', 'refuted' => 'Refuted', 'entered-in-error' => 'Entered in Error'],
        'definition' => ["unconfirmed" => "Tidak ada cukup bukti diagnostik dan/atau klinis untuk memperlakukan ini sebagai kondisi/diagnosis yang dikonfirmasi", "provisional" => "Diagnosis sementara masih merupakan kandidat yang sedang dipertimbangkan.", "differential" => "Salah satu dari daftar kemungkinan diagnosis, diikuti lebih lanjut dengan proses diagnostik dan pengobatan awal.", "confirmed" => "Ada cukup bukti diagnostik dan/atau klinis untuk mengkonfirmasi adanya suatu kondisi/diagnosis", "refuted" => "Kondisi ini telah dikesampingkan oleh bukti diagnostik dan klinis berikutnya.", "entered-in-error" => "Informasi yang dimasukkan salah dan tidak valid."],
    ];

    public const ConditionClinicalStatusCodes = [
        'system' => 'http://terminology.hl7.org/CodeSystem/condition-clinical',
        'code' => ['active', 'recurrence', 'relapse', 'inactive', 'remission', 'resolved'],
        'display' => ['active' => 'Active', 'recurrence' => 'Recurrence', 'relapse' => 'Relapse', 'inactive' => 'Inactive', 'remission' => 'Remission', 'resolved' => 'Resolved'],
        'definition' => ["active" => "Pasien saat ini sedang mengalami gejala atau ada bukti dari kondisi tersebut.", "recurrence" => "Pasien mengalami rekurensi atau kembalinya kondisi setelah kondisi tersebut telah sembuh", "relapse" => "Pasien mengalami kembalinya kondisi, atau tanda dan gejala setelah periode perbaikan atau remisi", "inactive" => "Pasien tidak memiliki gejala dari suatu penyakit atau sudah tidak ada buktiadanya suatu penyakit", "remission" => "Pasien tidak memiliki gejala dari suatu penyakit, namun memiliki risiko gejala muncul kembali", "resolved" => "Pasien tidak memiliki gejala dari suatu penyakit dan tidak memiliki risiko gejala muncul kembali"]
    ];

    public const LocationUpgradeClass = [
        'system' => 'http://terminology.kemkes.go.id/CodeSystem/locationUpgradeClass',
        'code' => ['kelas-tetap', 'naik-kelas', 'turun-kelas', 'titip-rawat'],
        'display' => ['kelas-tetap' => 'Kelas Tetap Perawatan', 'naik-kelas' => 'Kenaikan Kelas Perawatan', 'turun-kelas' => 'Penurunan Kelas Perawatan', 'titip-rawat' => 'Titip Kelas Perawatan'],
        'definition' => ["kelas-tetap" => "Pasien memiliki Kelas Perawatan yang sama dengan Hak Kelas Perawatan yang dimiliki", "naik-kelas" => "Pasien memiliki Kelas Perawatan yang lebih Tinggi dari Hak Kelas Perawatan yang dimiliki berdasarkan pengajuan dari pasien", "turun-kelas" => "Pasien memiliki Kelas Perawatan yang lebihRendah dari Hak Kelas Perawatan yang dimiliki berdasarkan pengajuan dari pasien", "titip-rawat" => "Pasien memiliki Kelas Perawatan yang berbeda dengan Hak Kelas Perawatan yang dimiliki karena ketidaktersediaan ruangan yang sesuai dengan Hak Kelasnya"]
    ];

    public const DischargeDisposition = [
        'system' => 'http://terminology.hl7.org/CodeSystem/discharge-disposition',
        'code' => ['home', 'alt-home', 'other-hcf', 'hosp', 'long', 'aadvice', 'exp', 'psy', 'rehab', 'snf', 'oth'],
        'display' => ["home" => "Home", "alt-home" => "Alternative home", "other-hcf" => "Other healthcare facility", "hosp" => "Hospice", "long" => "Long-term care", "aadvice" => "Left against advice", "exp" => "Expired", "psy" => "Psychiatric hospital", "rehab" => "Rehabilitation", "snf" => "Skilled nursing facility", "oth" => "Other"],
        'definition' => ["home" => "The patient was dicharged and has indicated that they are going to return home afterwards.", "alt-home" => "The patient was discharged and has indicated that they are going to return home afterwards, but not the patient's home - e.g. a family member's home.", "other-hcf" => "The patient was transferred to another healthcare facility.", "hosp" => "The patient has been discharged into palliative care.", "long" => "The patient has been discharged into long-term care where is likely to be monitored through an ongoing episode-of-care.", "aadvice" => "The patient self discharged against medical advice.", "exp" => "The patient has deceased during this encounter.", "psy" => "The patient has been transferred to a psychiatric facility.", "rehab" => "The patient was discharged and is to receive post acute care rehabilitation services.", "snf" => "The patient has been discharged to a skilled nursing facility for the patient to receive additional care.", "oth" => "The discharge disposition has not otherwise defined."]
    ];

    public const SpecialArrangements = [
        'system' => 'http://terminology.hl7.org/CodeSystem/encounter-special-arrangements',
        'code' => ['wheel', 'add-bed', 'int', 'att', 'dog'],
        'display' => ['wheel' => 'Wheelchair', 'add-bed' => 'Additional bedding', 'int' => 'Interpreter', 'att' => 'Attendant', 'dog' => 'Guide dog'],
        'definition' => ["wheel" => "Kursi roda", "add-bed" => "Tambahan kasur", "int" => "Penerjemah", "att" => "Asisten yang membantu pasien melakukan kegiatan.", "dog" => "Anjing Pemandu"]
    ];

    public const Diet = [
        'system' => 'http://terminology.hl7.org/CodeSystem/diet',
        'code' => ['vegetarian', 'dairy-free', 'nut-free', 'gluten-free', 'vegan', 'halal', 'kosher'],
        'display' => ['vegetarian' => 'Vegetarian', 'dairy-free' => 'Dairy Free', 'nut-free' => 'Nut Free', 'gluten-free' => 'Gluten Free', 'vegan' => 'Vegan', 'halal' => 'Halal', 'kosher' => 'Kosher'],
        'definition' => ["vegetarian" => "Makanan tanpa daging, unggas, makanan laut.", "dairy-free" => "Makanan tanpa susu atau olahan susu", "nut-free" => "Makanan tanpa kandungan kacang", "gluten-free" => "Makanan tanpa kandungan kacang", "vegan" => "Makanan tanpa daging, unggas,makanan laut, telur, produk susu, danbahan turunan hewanlainnya", "halal" => "Makanan yang sesuai dengan peraturan Islam", "kosher" => "Makanan yang sesuai dengan peraturan diet Yahudi"]
    ];

    public const v20092 = [
        'system' => 'http://terminology.hl7.org/CodeSystem/v2-0092',
        'code' => ['R'],
        'display' => 'Re-admission'
    ];

    public const AdmitSource = [
        'system' => 'http://terminology.hl7.org/CodeSystem/admit-source',
        'code' => ['hosp-trans', 'emd', 'outp', 'born', 'gp', 'mp', 'nursing', 'psych', 'rehab', 'other'],
        'display' => ['hosp-trans' => 'Transferred from other hospital', 'emd' => 'From accident/emergency department', 'outp' => 'From outpatient department', 'born' => 'Born in hospital', 'gp' => 'General Practitioner referral', 'mp' => 'MedicalPractitioner/physicia n referral', 'nursing' => 'From nursing home', 'psych' => 'From psychiatric hospital', 'rehab' => 'From rehabilitation facility', 'other' => 'Other'],
        'definition' => ["hosp-trans" => "Pasien dirujuk ke rumah sakit lain", "emd" => "Pasien dipindahkan dari departemen gawat darurat dalam rumah sakit tersebut. Biasanya terjadi ketika transisi ke kunjungan rawat inap.", "outp" => "Pasien dipindahkan dari departemen rawat jalandalam rumah sakit tersebut", "born" => "Pasien adalah bayi baru lahir dan kunjungan ini digunakan untuk melacak kondisi bayi", "gp" => "Pasien masuk/admisi akibat rujukan dari fasyankes tingkat satu", "mp" => "Pasien diadmisi karena rujukan dari spesialis", "nursing" => "Pasien dipindahkan dari panti jompo", "psych" => "Pasien dipindahkan darifasilitas psikiatri", "rehab" => "Pasien dipindahkan dari fasilitas atau klinik rehabilitasi", "other" => "Pasien masuk dari sumber yang tidakdiketahui"]
    ];

    public const DiagnosisRole = [
        'system' => 'http://terminology.hl7.org/CodeSystem/diagnosis-role',
        'code' => ['AD', 'DD', 'CC', 'CM', 'pre-op', 'post-op', 'billing'],
        'display' => ['AD' => 'Admission diagnosis', 'DD' => 'Discharge diagnosis', 'CC' => 'Chief complaint', 'CM' => 'Comorbidity diagnosis', 'pre-op' => 'pre-op diagnosis', 'post-op' => 'post-op diagnosis', 'billing' => 'Billing']
    ];

    public const ServiceType = [
        'system' => 'http://terminology.hl7.org/CodeSystem/service-type',
        'table' => 'codesystem_servicetype',
    ];

    public const EncounterType = [
        'system' => 'http://terminology.hl7.org/CodeSystem/encounter-type',
        'code' => ['ADMS', 'BD/BM-clin', 'CCS60', 'OKI'],
        'display' => ["ADMS" => "Annual diabetes mellitus screening", "BD/BM-clin" => "Bone drilling/bone marrow punction in clinic", "CCS60" => "Infant colon screening - 60 minutes", "OKI" => "Outpatient Kenacort injection"],
    ];

    public const ClinicalSpecialty = [
        'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-speciality',
        'table' => 'codesystem_clinicalspecialty',
    ];

    public const AdministrativeArea = [
        'system' => 'http://sys-ids.kemkes.go.id/administrative-area',
        'table' => 'codesystem_administrativearea',
    ];
}
