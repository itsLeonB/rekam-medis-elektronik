<?php

namespace App\Fhir;

class Codesystems
{
    public const ObservationReferenceRangeMeaningCodes = [
        'system' => 'http://terminology.hl7.org/CodeSystem/referencerange-meaning',
        'code' => ['type', 'normal', 'recommended', 'treatment', 'therapeutic', 'pre', 'post', 'endocrine', 'pre-puberty', 'follicular', 'midcycle', 'luteal', 'postmenopausal'],
        'display' => ["type" => "Type", "normal" => "Normal Range", "recommended" => "Recommended Range", "treatment" => "Treatment Range", "therapeutic" => "Therapeutic Desired Level", "pre" => "Pre Therapeutic Desired Level", "post" => "Post Therapeutic Desired Level", "endocrine" => "Endocrine", "pre-puberty" => "Pre-Puberty", "follicular" => "Follicular Stage", "midcycle" => "MidCycle", "luteal" => "Luteal", "postmenopausal" => "Post-Menopause"],
        'definition' => ["type" => "General types of reference range.", "normal" => "Values expected for a normal member of the relevant control population being measured. Typically each results producer such as a laboratory has specific normal ranges and they are usually defined as within two standard deviations from the mean and account for 95.45% of this population.", "recommended" => "The range that is recommended by a relevant professional body.", "treatment" => "The range at which treatment would/should be considered.", "therapeutic" => "The optimal range for best therapeutic outcomes.", "pre" => "The optimal range for best therapeutic outcomes for a specimen taken immediately before administration.", "post" => "The optimal range for best therapeutic outcomes for a specimen taken immediately after administration.", "endocrine" => "Endocrine related states that change the expected value.", "pre-puberty" => "An expected range in an individual prior to puberty.", "follicular" => "An expected range in an individual during the follicular stage of the cycle.", "midcycle" => "An expected range in an individual during the midcycle stage of the cycle.", "luteal" => "An expected range in an individual during the luteal stage of the cycle.", "postmenopausal" => "An expected range in an individual post-menopause."]
    ];

    public const DataAbsentReason = [
        'system' => 'http://terminology.hl7.org/CodeSystem/data-absent-reason',
        'code' => ['unknown', 'asked-unknown', 'temp-unknown', 'not-asked', 'asked-declined', 'masked', 'not-applicable', 'unsupported', 'as-text', 'error', 'not-a-number', 'negative-infinity', 'positive-infinity', 'not-performed', 'not-permitted'],
        'display' => ['unknown' => 'Unknown', 'asked-unknown' => 'Asked But Unknown', 'temp-unknown' => 'Temporarily Unknown', 'not-asked' => 'Not Asked', 'asked-declined' => 'Asked But Declined', 'masked' => 'Masked', 'not-applicable' => 'Not Applicable', 'unsupported' => 'Unsupported', 'as-text' => 'As Text', 'error' => 'Error', 'not-a-number' => 'Not a Number (NaN)', 'negative-infinity' => 'Negative Infinity (NINF)', 'positive-infinity' => 'Positive Infinity (PINF)', 'not-performed' => 'Not Performed', 'not-permitted' => 'Not Permitted'],
        'definition' => ['unknown' => 'Nilainya diharapkan ada tetapi tidak diketahui.', 'asked-unknown' => 'Sudah ditanyakan tapi tidak diketahui nilainya.', 'temp-unknown' => 'Ada alasan untuk mengharapkan (dari alur kerja) bahwa nilainya dapat diketahui.', 'not-asked' => 'Hasil observasi tidak ditanyakan', 'asked-declined' => 'Sumber data ditanya tetapi menolak untuk menjawab.', 'masked' => 'Informasi tidak tersedia karena alasan keamanan, privasi, atau alasan lain terkait.', 'not-applicable' => 'Tidak ada nilai yang tepat untuk elemen ini (misalnya periode menstruasi terakhir untuk pria).', 'unsupported' => 'Sistem tidak mampu mendukung pencatatan elemen ini.', 'as-text' => 'Hasil observasi direpresentasikan dalam naratif', 'error' => 'Ketidaktersediaan data akibat kesalahan dalam sistem ataupun alur kerja', 'not-a-number' => 'Nilai numerik tidak ditentukan atau tidak dapat direpresentasikan karena kesalahan pemrosesan floating point.', 'negative-infinity' => 'Nilai numerik terlalu rendah dan tidak dapat direpresentasikan karena kesalahan pemrosesan floating point.', 'positive-infinity' => 'Nilai numerik terlalu tinggi dan tidak dapat direpresentasikan karena kesalahan pemrosesan floating point.', 'not-performed' => 'Hasil observasi tidak tersedia karena prosedur observasi tidak dilakukan', 'not-permitted' => 'Hasil observasi tidak diizinkan dalam konteks ini (contoh : akibat profile FHIR atau tipe data)']
    ];

    public const UCUM = [
        'system' => 'http://unitsofmeasure.org',
        'table' => 'codesystem_ucum',
    ];

    public const LOINC = [
        'system' => 'http://loinc.org',
        'table' => 'codesystem_loinc',
    ];

    public const ObservationCategoryCodes = [
        'system' => 'http://terminology.hl7.org/CodeSystem/observation-category',
        'code' => ['social-history', 'vital-signs', 'imaging', 'laboratory', 'procedure', 'survey', 'exam', 'therapy', 'activity'],
        'display' => ['social-history' => 'Social History', 'vital-signs' => 'Vital Signs', 'imaging' => 'Imaging', 'laboratory' => 'Laboratory', 'procedure' => 'Procedure', 'survey' => 'Survey', 'exam' => 'Exam', 'therapy' => 'Therapy', 'activity' => 'Activity'],
        'definition' => ['social-history' => 'Digunakan ketika melaporkan pekerjaan pasien, riwayat sosial pasien, keluarga, kondisi lingkungan, dan faktor risiko kesehatan yang berdampak pada kesehatan pasien.', 'vital-signs' => 'Digunakan ketika melaporkan berkaitan dengan hasil pengukuran fungsi dasar tubuh seperti tekanan darah, denyut nadi, laju pernapasan, tinggi badan, berat badan, body mass index (BMI), lingkar kepala, saturasi oksigen, suhu tubuh, dan luas permukaan tubuh', 'imaging' => 'Digunakan ketika melaporkan berkaitan dengan pencitraan tubuh yang meliputi X-ray, ultrasound, CT Scan, MRI, angiografi, EKG, dan kedokteran nuklir.', 'laboratory' => 'Digunakan ketika melaporkan berkaitan dengan hasil analisis spesimen yang dikeluarkan oleh laboratorium yaitu kimia klinik, hematologi, serologi, histologi, sitologi, patologi anatomi (termasuk patologi digital), mikrobiologi, dan atau virologi.', 'procedure' => 'Digunakan ketika melaporkan hasil observasi yang dihasilkan dari prosedur lain. Kategori ini termasuk observasi dari tindakan intervensi dan non-intervensi di luar laboratorium dan imaging (contoh kateterisasi jantung, endoskopi, elektrodiagnostik). Contoh : dokter penyakit dalam melaporkan ukuran polip yang didapatkan melalui pemeriksaan kolonoskopi.', 'survey' => 'Digunakan ketika melaporkan berkaitan dengan alat asesmen maupun survei alat observasi seperti Skor APGAR, Montreal Cognitive Assessment (MoCA), dll).', 'exam' => 'Berkaitan dengan observasi fisik yang dilakukan langsung oleh tenaga kesehatan dan menggunakan alat sederhana.', 'therapy' => 'Berkaitan dengan protokol terapi non-intervensi seperti terapi okupasi, terapi fisik, terapi radiasi, terapi nutrisi, dan terapi medis.', 'activity' => 'Berkaitan dengan pengukuran aktivitas tubuh guna meningkatkan/memelihara kondisi fisik dan seluruh kesehatan dan tidak berkaitan dengan supervisi praktisi. Observasi tidak dibawah pengawasan langsung praktisi seperti ahli terapi fisik (contoh: jumlah putaran berenang, langkah kaki, data terkait tidur).'],
    ];

    public const ObservationStatus = [
        'system' => 'http://hl7.org/fhir/observation-status',
        'code' => ['registered', 'preliminary', 'final', 'amended', 'corrected', 'cancelled', 'entered-in-error', 'unknown'],
        'display' => [
            'registered' => 'Data observasi sudah di registrasi, namun belum ada hasil observasi yang tersedia',
            'preliminary' => 'Hasil observasi awal atau sementara; data mungkin tidak lengkap atau belum diverifikasi',
            'final' => 'Hasil observasi sudah selesai dan tidak memerlukan tindakan lebih lanjut.',
            'amended' => 'Setelah status "final", hasil observasi diubah untuk memperbarui, menambahkan informasi, dan koreksi hasil pemeriksaan',
            'corrected' => 'Setelah status "final", hasil observasi dimodifikasi untuk membenarkan error/kesalahan dari hasil pemeriksaan',
            'cancelled' => 'Hasil observasi tidak tersedia karena pemeriksaan dibatalkan',
            'entered-in-error' => 'Hasil observasi ditarik setelah dirilis "final" sebelumnya. Status ini seharusnya tidak boleh ada. Dalam kasus nyata, bila hasil observasi ditarik, status sebaiknya diisi dengan “cancelled” dibandingkan “entered-in error”',
            'unknown' => 'Sistem tidak mengetahui status dari data observasi'
        ],
    ];

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
