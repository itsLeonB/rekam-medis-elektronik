<?php

namespace App\Fhir;

class Codesystems
{
    public const EncounterLocationStatus = [
        'system' => 'http://hl7.org/fhir/encounter-location-status',
        'code' => ['planned', 'active', 'reserved', 'completed'],
        'display' => ['planned' => 'Planned', 'active' => 'Active', 'reserved' => 'Reserved', 'completed' => 'Completed'],
        'definition' => ['planned' => 'The patient is planned to be moved to this location at some point in the future.', 'active' => 'The patient is currently at this location, or was between the period specified. A system may update these records when the patient leaves the location to either reserved, or completed.', 'reserved' => 'This location is held empty for this patient.', 'completed' => 'The patient was at this location during the period specified. Not to be used when the patient is currently at the location.']
    ];

    public const LocationServiceClass = [
        'url' => "https://fhir.kemkes.go.id/r4/StructureDefinition/LocationServiceClass",
        'system' => ['1' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient', '2' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient', '3' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient', 'vip' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient', 'vvip' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient', 'reguler' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Outpatient', 'eksekutif' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Outpatient'],
        'code' => ['1', '2', '3', 'vip', 'vvip', 'reguler', 'eksekutif'],
        'display' => ['1' => 'Kelas 1', '2' => 'Kelas 2', '3' => 'Kelas 3', 'vip' => 'Kelas VIP', 'vvip' => 'Kelas VVIP', 'reguler' => 'Kelas Reguler', 'eksekutif' => 'Kelas Eksekutif'],
        'definition' => ['1' => 'Perawatan Kelas 1', '2' => 'Perawatan Kelas 2', '3' => 'Perawatan Kelas 3', 'vip' => 'Perawatan Kelas VIP', 'vvip' => 'Perawatan Kelas VVIP', 'reguler' => 'Perawatan Kelas Reguler', 'eksekutif' => 'Perawatan Kelas Eksekutif']
    ];

    public const LocationType = [
        'system' => 'http://terminology.kemkes.go.id/CodeSystem/location-type',
        'code' => ['RT0001', 'RT0002', 'RT0003', 'RT0004', 'RT0005', 'RT0006', 'RT0007', 'RT0008', 'RT0009', 'RT0010', 'RT0011', 'RT0012', 'RT0013', 'RT0014', 'RT0015', 'RT0016', 'RT0017', 'RT0018', 'RT0019', 'RT0020', 'RT0021', 'RT0022', 'RT0023', 'RT0024', 'RT0025', 'RT0026', 'RT0027', 'RT0028', 'RT0029', 'RT0030', 'RT0031', 'RT0032', 'RT0033'],
        'display' => ['RT0001' => 'Wahana PIDI', 'RT0002' => 'Wahana PIDGI', 'RT0003' => 'RS Pendidikan', 'RT0004' => 'Tempat Tidur', 'RT0005' => 'Bank Darah', 'RT0006' => 'Instalasi Gawat Darurat', 'RT0007' => 'Ruang Perawatan Intensif Umum (ICU)', 'RT0008' => 'Ruangan Persalinan', 'RT0009' => 'Ruang Perawatan Intensif', 'RT0010' => 'Daerah Rawat Pasien ICU/ICCU/HCU/ PICU', 'RT0011' => 'Ruangan Perawatan Intensif Pediatrik (PICU)', 'RT0012' => 'Ruangan Perawatan Intensif Neonatus(NICU)', 'RT0013' => 'High Care Unit (HCU)', 'RT0014' => 'Intensive Cardiology Care Unit (ICCU)', 'RT0015' => 'Respiratory Intensive Care Unit (RICU)', 'RT0016' => 'Ruang Rawat Inap', 'RT0017' => 'Ruangan Perawatan (Post Partum)', 'RT0018' => 'Ruangan Perawatan Isolasi', 'RT0019' => 'Ruangan Perawatan Neonatus Infeksius/ Isolasi', 'RT0020' => 'Ruangan Perawatan Neonatus Non Infeksius', 'RT0021' => 'Ruangan Perawatan Pasien Paska Terapi', 'RT0022' => 'Ruangan Rawat Pasca Persalinan', 'RT0023' => 'Ruangan/ Daerah Rawat Pasien Isolasi', 'RT0024' => 'Ruangan/ Daerah Rawat Pasien Non Isolasi', 'RT0025' => 'Ruang Operasi', 'RT0026' => 'Ruangan Observasi', 'RT0027' => 'Ruangan Resusitasi', 'RT0028' => 'Ruangan Tindakan Anak', 'RT0029' => 'Ruangan Tindakan Bedah', 'RT0030' => 'Ruangan Tindakan Kebidanan', 'RT0031' => 'Ruangan Tindakan Non-Bedah', 'RT0032' => 'Ruangan Triase', 'RT0033' => 'Ruangan Ultra Sonografi (USG)']
    ];

    public const LocationMode = [
        'system' => 'http://hl7.org/fhir/location-mode',
        'code' => ['instance', 'kind'],
        'display' => ['instance' => 'Merepresentasikan lokasi spesifik', 'kind' => 'Merepresentasikan kelompok/kelas lokasi'],
    ];

    public const v20116 = [
        'system' => 'http://terminology.hl7.org/CodeSystem/v2-0116',
        'code' => ['C', 'H', 'I', 'K', 'O', 'U'],
        'display' => ['C' => 'Closed', 'H' => 'Housekeeping', 'I' => 'Isolated', 'K' => 'Contaminated', 'O' => 'Occupied', 'U' => 'Unoccupied'],
        'definition' => ['C' => 'Tutup', 'H' => 'Dalam pembersihan', 'I' => 'Isolasi', 'K' => 'Terkontaminasi', 'O' => 'Terisi', 'U' => 'Tidak terisi'],
    ];

    public const LocationStatus = [
        'system' => 'http://hl7.org/fhir/location-status',
        'code' => ['active', 'suspended', 'inactive'],
        'display' => ['active' => 'Lokasi sedang beroperasi', 'suspended' => 'Lokasi ditutup sementara', 'inactive' => 'Lokasi tidak lagi digunakan'],
    ];

    public const ContactEntityType = [
        'system' => 'http://terminology.hl7.org/CodeSystem/contactentity-PURPOSE',
        'code' => ['BILL', 'ADMIN', 'HR', 'PAYOR', 'PATINF', 'PRESS'],
        'display' => ['BILL' => 'Billing', 'ADMIN' => 'Administrative', 'HR' => 'Human Resource', 'PAYOR' => 'Payor', 'PATINF' => 'Patient', 'PRESS' => 'Press'],
        'definition' => ['BILL' => 'Billing', 'ADMIN' => 'Administratif', 'HR' => 'SDM seperti informasi staf/tenaga kesehatan', 'PAYOR' => 'Klaim asuransi, pembayaran', 'PATINF' => 'Informasi umum untuk pasien', 'PRESS' => 'Pertanyaan terkait press']
    ];

    public const OrganizationType = [
        'system' => 'http://terminology.hl7.org/CodeSystem/organization-type',
        'code' => ['prov', 'dept', 'team', 'govt', 'ins', 'pay', 'edu', 'reli', 'crs', 'cg', 'bus', 'other'],
        'display' => ['prov' => 'Healthcare Provider', 'dept' => 'Hospital Department', 'team' => 'Organizational team', 'govt' => 'Government', 'ins' => 'Insurance Company', 'pay' => 'Payer', 'edu' => 'Educational Institute', 'reli' => 'Religious Institution', 'crs' => 'Clinical Research Sponsor', 'cg' => 'Community Group', 'bus' => 'Non-Healthcare Business or Corporation', 'other' => 'Other'],
        'definition' => ['prov' => 'Fasilitas Pelayanan Kesehatan', 'dept' => 'Departemen dalam Rumah Sakit', 'team' => 'Kelompok praktisi/tenaga kesehatan yang menjalankan fungsi tertentu dalam suatu organisasi', 'govt' => 'Organisasi Pemerintah', 'ins' => 'Perusahaan Asuransi', 'pay' => 'Badan Penjamin', 'edu' => 'Institusi Pendidikan/Penelitian', 'reli' => 'Organisasi Keagamaan', 'crs' => 'Sponsor penelitian klinis', 'cg' => 'Kelompok Masyarakat', 'bus' => 'Perusahaan diluar bidang kesehatan', 'other' => 'Lain-lain'],
    ];

    public const ISO3166 = [
        'system' => 'urn:iso:std:iso:3166',
        'table' => 'codesystem_iso3166'
    ];

    public const AddressUse = [
        'system' => 'http://hl7.org/fhir/address-use',
        'code' => ['home', 'work', 'temp', 'old', 'billing'],
        'display' => ['home' => 'Home', 'work' => 'Work', 'temp' => 'Temporary', 'old' => 'Old / Incorrect', 'billing' => 'Billing'],
        'definition' => [
            'home' => 'Rumah',
            'work' => 'Tempat kerja',
            'temp' => 'Sementara',
            'old' => 'Tidak digunakan lagi',
            'billing' => 'Penagihan'
        ]
    ];

    public const AddressType = [
        'system' => 'http://hl7.org/fhir/address-type',
        'code' => ['postal', 'physical', 'both'],
        'display' => ['postal' => 'Alamat surat', 'physical' => 'Alamat fisik yang dapat dikunjungi', 'both' => 'Alamat yang bersifat fisik dan surat']
    ];

    public const ContactPointUse = [
        'system' => 'http://hl7.org/fhir/contact-point-use',
        'code' => ['home', 'work', 'temp', 'old', 'mobile'],
        'display' => ['home' => 'Home', 'work' => 'Work', 'temp' => 'Temp', 'old' => 'Old', 'mobile' => 'Mobile'],
        'definition' => [
            'home' => 'Rumah',
            'work' => 'Tempat kerja',
            'temp' => 'Sementara',
            'old' => 'Tidak digunakan lain',
            'mobile' => 'Telepon seluler'
        ]
    ];

    public const ContactPointSystem = [
        'system' => 'http://hl7.org/fhir/contact-point-system',
        'code' => ['phone', 'fax', 'email', 'pager', 'url', 'sms', 'other'],
        'display' => ['phone' => 'Phone', 'fax' => 'Fax', 'email' => 'Email', 'pager' => 'Pager', 'url' => 'URL', 'sms' => 'SMS', 'other' => 'Other'],
        'definition' => [
            'phone' => 'Nomor Telepon Kantor',
            'fax' => 'Nomor Fax',
            'email' => 'Email Kantor',
            'pager' => 'Pager',
            'url' => 'URL website kantor',
            'sms' => 'Nomor SMS kantor',
            'other' => 'Lain-lain'
        ]
    ];

    public const BCP47 = [
        'system' => 'urn:ietf:bcp:47',
        'table' => 'codesystem_bcp47'
    ];

    public const AdministrativeGender = ['male', 'female', 'other', 'unknown'];

    public const NameUse = [
        'system' => 'http://hl7.org/fhir/name-use',
        'code' => ['usual', 'official', 'temp', 'nickname', 'anonymous', 'old', 'maiden'],
        'display' => ['usual' => 'Usual', 'official' => 'Official', 'temp' => 'Temp', 'nickname' => 'Nickname', 'anonymous' => 'Anonymous', 'old' => 'Old', 'maiden' => 'Name changed for Marriage']
    ];

    public const MedicationDispensePerformerFunctionCodes = [
        'system' => 'http://www.hl7.org/fhir/codesystem-medicationdispense-performer-function.html',
        'code' => ['dataenterer', 'packager', 'checker', 'finachecker'],
        'display' => ["dataenterer" => "Data Enterer", "packager" => "Packager", "checker" => "Checker", "finachecker" => "Final Checker"],
        'definition' => ["dataenterer" => "Yang memasukkan data", "packager" => "Pengemas", "checker" => "Pengecek", "finachecker" => "Pengecek akhir"]
    ];

    public const MedicationDispenseCategoryCodes = [
        'system' => 'http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category',
        'code' => ['inpatient', 'outpatient', 'community', 'discharge'],
        'display' => ["inpatient" => "Inpatient", "outpatient" => "Outpatient", "community" => "Community", "discharge" => "Discharge"],
        'definition' => ["inpatient" => "Pemberian obat untuk diadministrasikan atau dikonsumsi saat rawat inap", "outpatient" => "Pemberian obat untuk diadministrasikan atau dikonsumsi saat rawat jalan (cth. IGD, poliklinik rawat jalan, bedah rawat jalan, dll)", "community" => "Pemberian obat untuk diadministrasikan atau dikonsumsi di rumah (long term care atau nursing home, atau hospices)", "discharge" => "Pemberian obat yang dibuat ketika pasien dipulangkan dari fasilitas kesehatan"]
    ];

    public const MedicationDispenseStatusCodes = [
        'system' => 'http://terminology.hl7.org/CodeSystem/medicationdispense-status',
        'code' => ['preparation', 'in-progress', 'cancelled', 'on-hold', 'completed', 'entered-in-error', 'stopped', 'declined', 'unknown'],
        'display' => ["preparation" => "Persiapan", "in-progress" => "Dalam proses", "cancelled" => "Dibatalkan", "on-hold" => "Tertahan", "completed" => "Lengkap", "entered-in-error" => "Salah", "stopped" => "Dihentikan", "declined" => "Ditolak", "unknown" => "Tidak diketahui"]
    ];

    public const IdentifierUse = [
        'system' => 'http://hl7.org/fhir/identifier-use',
        'code' => ['usual', 'official', 'temp', 'secondary', 'old'],
        'display' => [
            'usual' => 'Identifier yang direkomendasikan digunakan untuk interaksi dunia nyata',
            'official' => 'Identifier yang dianggap paling terpercaya. Terkadang juga dikenal sebagai "primer" dan "utama". Penentuan "resmi" bersifat subyektif dan panduan implementasi seringkali memberikan panduan tambahan untuk digunakan.',
            'temp' => 'Identifier sementara',
            'secondary' => 'Identifier yang ditugaskan dalam penggunaan sekunder ini berfungsi untuk mengidentifikasi objek dalam konteks relatif, tetapi tidak dapat secara konsisten ditugaskan ke objek yang sama lagi dalam konteks yang berbeda.',
            'old' => 'Id identifier sudah dianggap tidak valid, tetapi masih memungkinkan relevan untuk kebutuhan pencarian.'
        ]
    ];

    public const RequestIntent = [
        'system' => 'http://hl7.org/fhir/request-intent',
        'code' => ['proposal', 'plan', 'directive', 'order', 'original-order', 'reflex-order', 'filler-order', 'instance-order', 'option'],
        'display' => ["proposal" => "Permintaan berupa usulan yang dibuat oleh seseorang yang tidak memiliki keinginan untuk menjamin permintaan dilaksanakan dan tanpa memberikan otorisasi untuk bertindak", "plan" => "Permintaan yang merepresentasikan niat untuk menjamin sesuatu terjadi tanpa memberikan otorisasi bagi orang lain untuk bertindak", "directive" => "Permintaan yang merepresentasikan secara legal terkait permintaan yang dilakukan oleh Patient (Pasien) atau RelatedPerson.", "order" => "Permintaan yang merepresentasikan permintaan dan otorisasi yang dilakukan oleh Practitioner (tenaga kesehatan)", "original-order" => "Permintaan yang merepresentasikan otorisasi asli untuk bertindak (permintaan asli)", "reflex-order" => "Permintaan yang dilakukan sebagai tambahan permintaan terhadap hasil awal yang membutuhkan tambahan tindakan (permintaan tambahan)", "filler-order" => "Permintaan yang merepresentasikan pandangan tentang otorisasi yang dibuat oleh sistem fulfilling yang mewakili rincian keinginan pemberi tindakan atas perintah yang diberikan", "instance-order" => "Perintah yang dibuat untuk pemenuhan lebih permintaan lebih luas yang merepresentasikan hak untuk aktivitas tunggal. Misalnya: pemberian dosis obat tinggal", "option" => "Permintaan yang merepresentasikan komponen atau opsi untuk RequestGroup yang membentuk waktu, kondisionalitas, dan/atau konstrain pada kumpulan permintaan. Merujuk pada [[[RequestGroup]]] untuk informasi tambahan mengenai bagaimana status ini dibuat"]
    ];

    public const RequestStatus = [
        'system' => 'http://hl7.org/fhir/request-status',
        'code' => ['draft', 'active', 'on-hold', 'revoked', 'completed', 'entered-in-error', 'unknown'],
        'display' => ["draft" => "Permintaan yang telah dibuat namun belum selesai atau belum siap untuk dilakukan", "active" => "Permintaan yang berlaku dan siap untuk dilakukan", "on-hold" => "Permintaan (dan setiap hak implisit untuk bertindak) yang telah ditarik/dihentikan sementara namun diharapkan untuk dilanjutkan nanti", "revoked" => "Permintaan (dan setiap hak implisit untuk bertindak) yang telah dihentikan secara penuh dari rencana. Tidak ada aktivitas lanjutan yang harus diteruskan", "completed" => "Aktivitas yang dideskripsikan oleh permintaan yang telah selesai. Tidak ada aktivitas lanjutan yang harus diteruskan", "entered-in-error" => "Permintaan yang seharusnya tidak ada dan sebaiknya dikosongi. (hal ini mungkin berdasarkan keputusan di lapangan. Jika kondisi aktivitas telah terjadi, maka status harus menjadi “revoked” daripada “entered-in-error”)", "unknown" => "Sistem pembuat/sumber tidak mengetahui status mana yang saat ini berlaku untuk permintaan tersebut. Catatan: Konsep ini tidak digunakan untuk “lainnya”, salah satu status yang terdaftar dianggap berlaku namun sistem pembuat/sumber yang tidak dapat mengidentifikasi"]
    ];

    public const AllergyIntoleranceSeverity = [
        'system' => 'http://hl7.org/fhir/reaction-event-severity',
        'code' => ['mild', 'moderate', 'severe'],
        'display' => ['mild' => 'Menyebabkan efek fisiologis ringan', 'moderate' => 'Menyebabkan efek fisiologis sedang', 'severe' => 'Menyebabkan efek fisiologis berat']
    ];

    public const WHOATC = [
        // TODO: Cari API / beli resmi WHO $200
        // https://github.com/fabkury/atcd/blob/master/WHO%20ATC-DDD%202021-12-03.csv
        // https://www.who.int/tools/atc-ddd-toolkit/atc-classification
    ];
    public const AllergyIntoleranceCriticality = [
        'system' => 'http://hl7.org/fhir/allergy-intolerance-criticality',
        'code' => ['low', 'high', 'unable-to-assess'],
        'display' => ["low" => "Tidak mengancam jiwa atau berpotensi tinggi untuk kegagalan sistem organ", "high" => "Mengancam jiwa atau berpotensi menyebabkan kegagalan sistem organ", "unable-to-assess" => "Tidak dapat dikaji potensi bahaya klinis pada paparan mendatang"],
    ];

    public const AllergyIntoleranceCategory = [
        'system' => 'http://hl7.org/fhir/allergy-intolerance-category',
        'code' => ['food', 'medication', 'environment', 'biologic'],
        'display' => ["food" => "Segala zat atau substansi yang dikonsumsi untuk nutrisi bagi tubuh", "medication" => "Substansi yang diberikan untuk mencapai efek fisiologis (Obat)", "environment" => "Setiap substansi yang berasal atau ditemukan dari lingkungan, termasuk substansi yang tidak dikategorikan sebagai makanan, medikasi/obat, dan biologis", "biologic" => "Sediaan yang disintesis dari organisme hidup atau produknya, terutama manusia atau protein hewan, seperti hormon atau antitoksin, yang digunakan sebagai agen diagnostik, preventif, atau terapeutik. Contoh obat biologis meliputi: vaksin; ekstrak alergi, yang digunakan untuk diagnosis dan pengobatan (misalnya, suntikan alergi); terapi gen; terapi seluler. Ada produk biologis lain, seperti jaringan, yang biasanya tidak terkait dengan alergi."],
    ];

    public const AllergyIntoleranceType = [
        'system' => 'http://hl7.org/fhir/allergy-intolerance-type',
        'code' => ['allergy', 'intolerance'],
        'display' => ["allergy" => "Kecenderungan reaksi hipersensitif pada zat tertentu yang seringnya disebabkan oleh hipersensitivitas tipe I ditambah reaksi seperti alergi lain, termasuk pseudoallergy", "intolerance" => "Kecenderungan reaksi tidak diinginkan terhadap suatu zat yang tidak diidentifikasi sebagai alergi atau reaksi seperti alergi. Reaksi ini terkait non-imun dan terdapat beberapa derajat idiosinkratik dan/atau"],
    ];

    public const AllergyIntoleranceVerificationStatusCodes = [
        'system' => 'http://terminology.hl7.org/CodeSystem/allergyintolerance-verification',
        'code' => ['unconfirmed', 'confirmed', 'refuted', 'entered-in-error'],
        'display' => ["unconfirmed" => "Unconfirmed", "confirmed" => "Confirmed", "refuted" => "Refuted", "entered-in-error" => "Entered in Error"],
        'description' => ["unconfirmed" => "Belum terkonfirmasi secara klinis.Tingkat kepastian rendah tentang kecenderungan reaksi terhadap suatu zat.", "confirmed" => "Terkonfirmasi secara klinis. Tingkat kepastian yang tinggi tentang kecenderungan reaksi pada suatu zat yang dapat dibuktikan secara klinis melalui tes atau rechallenge", "refuted" => "Disangkal atau tidak terbukti. Reaksi terhadap suatu zat disangkal atau tidak terbukti berdasarkan bukti klinis. Hal ini dapat termasuk/tidak termasuk pengujian", "entered-in-error" => "Pernyataan yang dimasukkan sebagai error atau tidak valid"],
    ];

    public const AllergyIntoleranceClinicalStatusCodes = [
        'system' => 'http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical',
        'code' => ['active', 'inactive', 'resolved'],
        'display' => ['active' => 'Active', 'inactive' => 'Inactive', 'resolved' => 'Resolved'],
        'definition' => ['active' => 'Subjek saat ini mengalami atau dalam risiko reaksi terhadap suatu zat', 'inactive' => 'Subjek saat ini tidak berisiko reaksi terhadap suatu zat', 'resolved' => 'Reaksi pada zat telah dikaji ulang secara klinis melalui pengujian atau paparan ulang dan dianggap sudah tidak ada lagi. Paparan ulang dapat bersifat tidak sengaja, tidak terencana, atau di luar dari tatanan klinis'],
    ];

    public const ListEmptyReasons = [
        'system' => 'http://terminology.hl7.org/CodeSystem/list-empty-reason',
        'code' => ['nilknown', 'notasked', 'withheld', 'unavailable', 'notstarted', 'closed'],
        'display' => ["nilknown" => "Nil Known", "notasked" => "Not Asked", "withheld" => "Information Withheld", "unavailable" => "Unavailable", "notstarted" => "Not Started", "closed" => "Closed"],
        'definition' => ["nilknown" => "Tidak diketahui", "notasked" => "Tidak ditanyakan", "withheld" => "Konten tidak tersedia karena masalah privasi dan kerahasiaan.", "unavailable" => "Informasi tidak tersedia karena tidak bisa didapatkan. Contoh: pasien tidak sadarkan diri", "notstarted" => "Langkah untuk melengkapi informasi belum dimulai", "closed" => "Daftar sudah ditutup atau sudah tidak relevan"]
    ];

    public const ListOrderCodes = [
        'system' => 'http://terminology.hl7.org/CodeSystem/list-order',
        'code' => ['user', 'system', 'event-date', 'entry-date', 'priority', 'alphabetic', 'category', 'patient'],
        'display' => ["user" => "Sorted by User", "system" => "Sorted by System", "event-date" => "Sorted by Event Date", "entry-date" => "Sorted by Item Date", "priority" => "Sorted by Priority", "alphabetic" => "Sorted Alphabetically", "category" => "Sorted by Category", "patient" => "Sorted by Patient"],
        'definition' => ["user" => "Diurutkan berdasarkan User.", "system" => "Diurutkan berdasarkan System.", "event-date" => "Diurutkan berdasarkan Event Date.", "entry-date" => "Diurutkan berdasarkan Item Date.", "priority" => "Diurutkan berdasarkan prioritas.", "alphabetic" => "Diurutkan berdasarkan alfabet.", "category" => "Diurutkan berdasarkan kategori.", "patient" => "Diurutkan berdasarkan pasien."]
    ];

    public const ListMode = [
        'system' => 'http://hl7.org/fhir/list-mode',
        'code' => ['working', 'snapshot', 'changes'],
        'display' => ["working" => "Daftar ini merupakan daftar utama/master list dimana akan dipelihara dengan pembaruan rutin yang terjadi di dunia nyata", "snapshot" => "Daftar ini disiapkan sebagai snapshot. Tidak boleh dianggap sebagai kondisi saat ini.", "changes" => "Daftar sewaktu yang menunjukkan perubahan telah dibuat atau direkomendasikan. Misalnya. daftar obat keluar yang menunjukkan apa yang ditambahkan dan dihapus selama kunjungan."]
    ];

    public const NarrativeStatus = [
        'system' => 'http://hl7.org/fhir/narrative-status',
        'code' => ['generated', 'extensions', 'additional', 'empty'],
        'display' => ["generated" => "Isi keseluruhan narasi dihasilkan dari elemen inti dalam konten", "extensions" => "Isi keseluruhan narasi dihasilkan dari elemen inti dalam konten dan beberapa konten berasal dari extension. Narasi HARUS merefleksikan dampak dari seluruh modifier extension", "additional" => "Isi narasi dapat berisikan informasi tambahan yang tidak ditemukan dalam struktur data. Perhatikan bahwa tidak ada cara yang dapat dihitung untuk menentukan informasi tambahan kecuali oleh inspeksi seseorang", "empty" => "Isi narasi merupakan beberapa hal yang setara dengan “tidak ada teks yang dapat dibaca yang tersedia dalam kasus ini”"]
    ];

    public const v3ActCode = [
        'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
        'table' => 'codesystem_v3actcode'
    ];

    public const DocumentRelationshipType = [
        'system' => 'http://hl7.org/fhir/document-relationship-type',
        'code' => ['replaces', 'transforms', 'signs', 'appends'],
        'display' => ["replaces" => "Menggantikan dokumen target", "transforms" => "Dokumen dihasilkan dari transformasi dokumen target (contoh : translasi)", "signs" => "Tanda tangan dari dokumen target", "appends" => "Informasi tambahan dari dokumen target"]
    ];

    public const CompositionAttestationMode = [
        'system' => 'http://hl7.org/fhir/composition-attestation-mode',
        'code' => ['personal', 'professional', 'legal', 'official'],
        'display' => ["personal" => "Autentikasi dalam kapasitas personal", "professional" => "Autentikasi dalam kapasitas profesional", "legal" => "Autentikasi dalam kapasitas legal", "official" => "Organisasi mengautentikasi sesuai dengan kebijakan dan prosedur"]
    ];

    public const CompositionStatus = [
        'system' => 'http://hl7.org/fhir/composition-status',
        'code' => ['preliminary', 'final', 'amended', 'entered-in-error'],
        'display' => ["preliminary" => "Dokumen initial atau interim. Konten masih belum lengkap atau belum terverifikasi", "final" => "Versi dokumen sudah komplit dan diverifikasi", "amended" => "Konten dimodifikasi setelah status “final”", "entered-in-error" => "Konten error, bisa dianggap tidak valid"]
    ];

    public const v3SubstanceAdminSubstitution = [
        'system' => 'http://terminology.hl7.org/CodeSystem/v3-substanceAdminSubstitution',
        'code' => ['(_ActSubstanceAdminSubstitutionCode) Abstract', 'E', 'EC', 'BC', 'G', 'TE', 'TB', 'TG', 'F', 'N'],
        'display' => ["(_ActSubstanceAdminSubstitutionCode) Abstract" => "null", "E" => "equivalent", "EC" => "equivalent composition", "BC" => "brand composition", "G" => "generic composition", "TE" => "therapeutic alternative", "TB" => "therapeutic brand", "TG" => "therapeutic generic", "F" => "formulary", "N" => "none"],
        'definition' => ["(_ActSubstanceAdminSubstitutionCode) Abstract" => "Substitusi terjadi atau diperbolehkan dengan produk lain yang kemungkinan memiliki perbedaan kandungan zat tetapi memiliki efek biologis dan terapetik yang sama", "E" => "Substitusi terjadi atau diperbolehkan dengan produk lain yang bioekivalen dan efek terapi sama.", "EC" => "Substitusi terjadi atau diperbolehkan dengan produk lain dimana a. Pharmaceutical alternative : memiliki kandungan zat aktif yang sama tetapi berbeda formulasi/bentuk garam. Contoh : Erythromycin Ethylsuccinate dengan Erythromycin Stearate b. Pharmaceutical equivalent : memiliki kandungan zat aktif, kekuatan, dan rute administrasi yang sama. Contoh Lisonpril for Zestril", "BC" => "Substitusi terjadi atau diperbolehkan antara brand yang ekuivalen tetapi bukan generik. Contoh : Zestril dengan Prinivil", "G" => "Substitusi terjadi atau diperbolehkan antara generik yang ekuivalen tetapi bukan brand. Contoh : Lisnopril (Lupin Corp) dengan Lisnopril (Wockhardt Corp)", "TE" => "Substitusi terjadi atau diperbolehkan dengan produk lain yang memiliki tujuan terapetik dan profil keamanan yang sama. Contoh : ranitidine dengan or Tagamet", "TB" => "Substitusi terjadi atau diperbolehkan antara brand dengan efek terapeutik ekuivalen, tetapi bukan generik. Contoh : Zantac for Tagamet", "TG" => "Substitusi terjadi atau diperbolehkan antara generik dengan efek terapeutik ekuivalen, tetapi bukan brand. Contoh : Ranitidine for cimetidine", "F" => "Substitusi terjadi atau diperbolehkan berdasarkan pedoman formularium", "N" => "Substitusi tidak terjadi atau diperbolehkan"]
    ];

    public const MedicationRequestCourseOfTherapyCodes = [
        'system' => 'https://hl7.org/FHIR/codesystem-medicationrequest-course-of-therapy.html',
        'code' => ['continuous', 'acute', 'seasonal'],
        'display' => ["continuous" => "Continuing long term therapy", "acute" => "Short course (acute) therapy", "seasonal" => "Seasonal"],
        'definition' => ["continuous" => "Pengobatan yang diharapkan berlanjut hingga permintaan selanjutnya dan pasien harus diasumsikan mengonsumsinya kecuali jika dihentikan secara eksplisit", "acute" => "Pengobatan pasien yang diharapkan dikonsumsi pada durasi pemberian tertentu dan tidak diberikan lagi", "seasonal" => "Pengobatan yang diharapkan digunakan pada waktu tertentu pada waktu yang telah dijadwalkan dalam setahun"]
    ];

    public const v3ActReason = [
        'system' => 'https://terminology.hl7.org/3.1.0/CodeSystem-v3-ActReason.html',
        'code' => ['CT', 'FP', 'OS', 'RR'],
        'display' => ["CT" => "Continuing therapy", "FP" => "Formulary policy", "OS" => "Out of stock", "RR" => "Regulatory requirement"],
        'definition' => ["CT" => "Mengindikasikan bahwa keputusan untuk mengganti/tidak mengganti didasari oleh keinginan untuk menjaga konsistensi terapi pre-existing. pe", "FP" => "Mengindikasikan bahwa keputusan untuk mengganti/tidak mengganti didasari oleh kebijakan dalam formularium", "OS" => "Mengindikasikan penggantian terjadi karena persediaan obat yang diminta tidak ada atau tidak diganti apabila obat yang direncanakan sebagai pengganti tidak ada stok", "RR" => "Mengindikasikan keputusan untuk mengganti/tidak mengganti didasari oleh persyaratan regulasi yuridis yang mengamanatkan atau melarang substitusi"],
    ];

    public const RequestPriority = [
        'system' => 'http://hl7.org/fhir/request-priority',
        'code' => ['routine', 'urgent', 'asap', 'stat'],
        'display' => ["routine" => "Permintaan prioritas normal", "urgent" => "Permintaan yang harus dilakukan segera ditindaklanjuti/lebih prioritas daripada Routine", "asap" => "Permintaan yang harus dilakukan sesegera mungkin/lebih prioritas daripada Urgent", "stat" => "Permintaan yang harus dilakukan diberikan saat itu juga/lebih prioritas daripada ASAP"]
    ];

    public const MedicationRequestCategoryCodes = [
        'system' => 'http://terminology.hl7.org/CodeSystem/medicationrequest-category',
        'code' => ['inpatient', 'outpatient', 'community', 'discharge'],
        'display' => ["inpatient" => "Inpatient", "outpatient" => "Outpatient", "community" => "Community", "discharge" => "Discharge"],
        'definition' => ["inpatient" => "Peresepan untuk diadministrasikan atau dikonsumsi saat rawat inap", "outpatient" => "Peresepan untuk diadministrasikan atau dikonsumsi saat rawat jalan (cth. IGD, poliklinik rawat jalan, bedah rawat jalan, dll)", "community" => "Peresepan untuk diadministrasikan atau dikonsumsi di rumah (long term care atau nursing home, atau hospices)", "discharge" => "Peresepan obat yang dibuat ketika pasien dipulangkan dari fasilitas kesehatan"]
    ];

    public const MedicationRequestIntent = [
        'system' => 'http://hl7.org/fhir/CodeSystem/medicationrequest-intent',
        'code' => ['proposal', 'plan', 'order', 'original-order', 'reflex-order', 'filler-order', 'instance-order', 'unknown'],
        'display' => ["proposal" => "Permintaan yang diusulkan oleh seseorang yang bertujuan untuk menjamin pengobatan dilakukan tanpa memerlukan hak untuk bertindak", "plan" => "Permintaan yang menggambarkan tujuan untuk menjamin pengobatan dilakukan tanpa memberikan hak yang lain untuk bertindak", "order" => "Permintaan yang menunjukkan kebutuhan dan hak untuk bertindak", "original-order" => "Permintaan yang menggambarkan hak asli untuk meminta pengobatan", "reflex-order" => "Permintaan yang menggambarkan hak tambahan yang dibuat untuk tindakan berdasarkan otorisasi bersama dengan hasil awal tindakan yang merujuk pada otorisasi tersebut", "filler-order" => "Permintaan tersebut mewakili pandangan otorisasi yang dibuat oleh sistem pemenuhan yang mewakili rincian niat pemenuhan untuk bertindak atas permintaan yang diajukan", "instance-order" => "Permintaan yang menggambarkan contoh tertentu, misal catatan pemberian obat", "unknown" => "Permintaan yang menggambarkan opsi untuk RequestGroup"]
    ];

    public const MedicationRequestStatusReasonCodes = [
        'system' => 'http://terminology.hl7.org/CodeSystem/medicationrequest-status-reason',
        'code' => ['altchoice', 'clarif', 'drughigh', 'hospadm', 'labint', 'non-avail', 'preg', 'salg', 'sddi', 'sdupther', 'sintol', 'surg', 'washout'],
        'display' => ["altchoice" => "Try another treatment first", "clarif" => "Prescription requires clarification", "drughigh" => "Drug level too high", "hospadm" => "Admission to hospital", "labint" => "Lab interference issues", "non-avail" => "Patient not available", "preg" => "Parent is pregnant/breast feeding", "salg" => "Allergy", "sddi" => "Drug interacts with another drug", "sdupther" => "Duplicate therapy", "sintol" => "Suspected intolerance", "surg" => "Patient scheduled for surgery.", "washout" => "Waiting for old drug to wash out"]
    ];

    public const MedicationRequestStatus = [
        'system' => 'http://hl7.org/fhir/CodeSystem/medicationrequest-status',
        'code' => ['active', 'on-hold', 'cancelled', 'completed', 'entered-in-error', 'stopped', 'draft', 'unknown'],
        'display' => ["active" => "Aktif", "on-hold" => "Tertahan", "cancelled" => "Dibatalkan", "completed" => "Komplit", "entered-in-error" => "Salah", "stopped" => "Dihentikan", "draft" => "Draft/butuh verifikasi", "unknown" => "Tidak diketahui"]
    ];

    public const MedicationType = [
        'system' => 'http://terminology.kemkes.go.id/CodeSystem/medication-type',
        'code' => ['NC', 'SD', 'EP'],
        'display' => ["NC" => "Non-compound", "SD" => "Gives of such doses", "EP" => "Divide into equal parts"],
        'definition' => ["NC" => "Obat non-racikan", "SD" => "Obat racikan dengan instruksi berikan dalam dosis demikian/d.t.d", "EP" => "Obat racikan non-d.t.d"]
    ];

    public const MedicationForm = [
        'system' => 'http://terminology.kemkes.go.id/CodeSystem/medication-form',
        'code' => ['BS001', 'BS002', 'BS003', 'BS004', 'BS005', 'BS006', 'BS007', 'BS008', 'BS009', 'BS010', 'BS011', 'BS012', 'BS013', 'BS014', 'BS015', 'BS016', 'BS017', 'BS018', 'BS019', 'BS020', 'BS021', 'BS022', 'BS023', 'BS024', 'BS025', 'BS026', 'BS027', 'BS028', 'BS029', 'BS030', 'BS031', 'BS032', 'BS033', 'BS034', 'BS035', 'BS036', 'BS037', 'BS038', 'BS039', 'BS040', 'BS041', 'BS042', 'BS043', 'BS044', 'BS045', 'BS046', 'BS047', 'BS048', 'BS049', 'BS050', 'BS051', 'BS052', 'BS053', 'BS054', 'BS055', 'BS056', 'BS057', 'BS058', 'BS059', 'BS060', 'BS061', 'BS062', 'BS063', 'BS064', 'BS065', 'BS066', 'BS067', 'BS068', 'BS069', 'BS070', 'BS071', 'BS072', 'BS073', 'BS074', 'BS075', 'BS076', 'BS077', 'BS078', 'BS079', 'BS080', 'BS081', 'BS082', 'BS083', 'BS084', 'BS085', 'BS086', 'BS087', 'BS088', 'BS089', 'BS090', 'BS091', 'BS092', 'BS093', 'BS094', 'BS095', 'BS096', 'BS097'],
        'display' => ["BS001" => "Aerosol Foam", "BS002" => "Aerosol Metered Dose", "BS003" => "Aerosol Spray", "BS004" => "Oral Spray", "BS005" => "Buscal Spray", "BS006" => "Transdermal Spray", "BS007" => "Topical Spray", "BS008" => "Serbuk Spray", "BS009" => "Eliksir", "BS010" => "Emulsi", "BS011" => "Enema", "BS012" => "Gas", "BS013" => "Gel", "BS014" => "Gel Mata", "BS015" => "Granul Effervescent", "BS016" => "Granula", "BS017" => "Intra Uterine Device (IUD)", "BS018" => "Implant", "BS019" => "Kapsul", "BS020" => "Kapsul Lunak", "BS021" => "Kapsul Pelepasan Lambat", "BS022" => "Kaplet", "BS023" => "Kaplet Salut Selaput", "BS024" => "Kaplet Salut Enterik", "BS025" => "Kaplet Salut Gula", "BS026" => "Kaplet Pelepasan Lambat", "BS027" => "Kaplet Pelepasan Cepat", "BS028" => "Kaplet Kunyah", "BS029" => "Kaplet Kunyah Salut Selaput", "BS030" => "Krim", "BS031" => "Krim Lemak", "BS032" => "Larutan", "BS033" => "Larutan Inhalasi", "BS034" => "Larutan Injeksi", "BS035" => "Infus", "BS036" => "Obat Kumur", "BS037" => "Ovula", "BS038" => "Pasta", "BS039" => "Pil", "BS040" => "Patch", "BS041" => "Pessary", "BS042" => "Salep", "BS043" => "Salep Mata", "BS044" => "Sampo", "BS045" => "Semprot Hidung", "BS046" => "Serbuk Aerosol", "BS047" => "Serbuk Oral", "BS048" => "Serbuk Inhaler", "BS049" => "Serbuk Injeksi", "BS050" => "Serbuk Injeksi Liofilisasi", "BS051" => "Serbuk Infus", "BS052" => "Serbuk Obat Luar / Serbuk Tabur", "BS053" => "Serbuk Steril", "BS054" => "Serbuk Effervescent", "BS055" => "Sirup", "BS056" => "Sirup Kering", "BS057" => "Sirup Kering Pelepasan Lambat", "BS058" => "Subdermal Implants", "BS059" => "Supositoria", "BS060" => "Suspensi", "BS061" => "Suspensi Injeksi", "BS062" => "Suspensi / Cairan Obat Luar", "BS063" => "Cairan Steril", "BS064" => "Cairan Mata", "BS065" => "Cairan Diagnostik", "BS066" => "Tablet", "BS067" => "Tablet Effervescent", "BS068" => "Tablet Hisap", "BS069" => "Tablet Kunyah", "BS070" => "Tablet Pelepasan Cepat", "BS071" => "Tablet Pelepasan Lambat", "BS072" => "Tablet Disintegrasi Oral", "BS073" => "Tablet Dispersibel", "BS074" => "Tablet Cepat Larut", "BS075" => "Tablet Salut Gula", "BS076" => "Tablet Salut Enterik", "BS077" => "Tablet Salut Selaput", "BS078" => "Tablet Sublingual", "BS079" => "Tablet Sublingual Pelepasan Lambat", "BS080" => "Tablet Vaginal", "BS081" => "Tablet Lapis", "BS082" => "Tablet Lapis Lepas Lambat", "BS083" => "Chewing Gum", "BS084" => "Tetes Mata", "BS085" => "Tetes Hidung", "BS086" => "Tetes Telinga", "BS087" => "Tetes Oral (Oral Drops)", "BS088" => "Tetes Mata Dan Telinga", "BS089" => "Transdermal", "BS090" => "Transdermal Urethral", "BS091" => "Tulle/Plester Obat", "BS092" => "Vaginal Cream", "BS093" => "Vaginal Gel", "BS094" => "Vaginal Douche", "BS095" => "Vaginal Ring", "BS096" => "Vaginal Tissue", "BS097" => "Suspensi Inhalasi"]
    ];

    public const MedicationStatusCodes = [
        'system' => 'http://hl7.org/fhir/CodeSystem/medication-status',
        'code' => ['active', 'inactive', 'entered-in-error'],
        'display' => ["active" => "Obat tersedia untuk digunakan", "inactive" => "Obat tidak tersedia", "entered-in-error" => "Obat yang dimasukkan salah"]
    ];

    public const KFA = [
        'system' => 'http://sys-ids.kemkes.go.id/kfa',
        'url' => 'https://api-satusehat-dev.dto.kemkes.go.id/kfa-v2'
    ];

    public const ICD9CMProcedure = [
        'system' => 'http://hl7.org/fhir/sid/icd-9-cm',
        'table' => 'codesystem_icd9cmprocedure',
    ];

    public const EventStatus = [
        'system' => 'http://hl7.org/fhir/eventstatus',
        'code' => ['preparation', 'in-progress', 'not-done', 'on-hold', 'stopped', 'completed', 'entered-in-error', 'unknown'],
        'display' => ['preparation' => 'Persiapan', 'in-progress' => 'Berlangsung', 'not-done' => 'Tidak dilakukan', 'on-hold' => 'Tertahan', 'stopped' => 'Berhenti', 'completed' => 'Selesai', 'entered-in-error' => 'Salah masuk', 'unknown' => 'Tidak diketahui'],
    ];

    public const ObservationReferenceRangeMeaningCodes = [
        'system' => 'http://terminology.hl7.org/CodeSystem/referencerange-meaning',
        'code' => ['type', 'normal', 'recommended', 'treatment', 'therapeutic', 'pre', 'post', 'endocrine', 'pre-puberty', 'follicular', 'midcycle', 'luteal', 'postmenopausal'],
        'display' => ["type" => "Type", "normal" => "Normal Range", "recommended" => "Recommended Range", "treatment" => "Treatment Range", "therapeutic" => "Therapeutic Desired Level", "pre" => "Pre Therapeutic Desired Level", "post" => "Post Therapeutic Desired Level", "endocrine" => "Endocrine", "pre-puberty" => "Pre-Puberty", "follicular" => "Follicular Stage", "midcycle" => "MidCycle", "luteal" => "Luteal", "postmenopausal" => "Post-Menopause"],
        'definition' => ["type" => "Tipe", "normal" => "Rentang normal", "recommended" => "Rentang yang direkomendasi kan", "treatment" => "Rentang pengobatan", "therapeutic" => "Tingkatan luaran terapi yang diinginkan", "pre" => "Tingkatan rentang sebelum terapi", "post" => "Tingkatan rentang setelah terapi", "endocrine" => "Endokrin", "pre-puberty" => "Pra-pubertas", "follicular" => "Tahapan folikular", "midcycle" => "MidCycle", "luteal" => "Luteal", "postmenopausal" => "Post-Menopause"],
    ];

    public const MimeTypes = [
        'system' => 'urn:ietf:bcp:13',
        'table' => 'codesystem_bcp13',
    ];

    public const v20203 = [
        'system' => 'http://terminology.hl7.org/CodeSystem/v2-0203',
        'code' => ['AC', 'ACSN', 'AM', 'AMA', 'AN', 'ANC', 'AND', 'ANON', 'ANT', 'APRN', 'ASID', 'BA', 'BC', 'BCFN', 'BCT', 'BR', 'BRN', 'BSNR', 'CC', 'CONM', 'CY', 'CZ', 'DC', 'DCFN', 'DDS', 'DEA', 'DFN', 'DI', 'DL', 'DN', 'DO', 'DP', 'DPM', 'DR', 'DS', 'EI', 'EN', 'ESN', 'FDR', 'FDRFN', 'FI', 'FILL', 'GI', 'GL', 'GN', 'HC', 'IND', 'JHN', 'LACSN', 'LANR', 'LI', 'LN', 'LR', 'MA', 'MB', 'MC', 'MCD', 'MCN', 'MCR', 'MCT', 'MD', 'MI', 'MR', 'MRT', 'MS', 'NBSNR', 'NCT', 'NE', 'NH', 'NI', 'NII', 'NIIP', 'NNxxx', 'NP', 'NPI', 'OBI', 'OD', 'PA', 'PC', 'PCN', 'PE', 'PEN', 'PHC', 'PHE', 'PHO', 'PI', 'PLAC', 'PN', 'PNT', 'PPIN', 'PPN', 'PRC', 'PRN', 'PT', 'QA', 'RI', 'RN', 'RPH', 'RR', 'RRI', 'RRP', 'SB', 'SID', 'SL', 'SN', 'SNBSN', 'SNO', 'SP', 'SR', 'SS', 'STN', 'TAX', 'TN', 'TPR', 'TRL', 'U', 'UDI', 'UPIN', 'USID', 'VN', 'VP', 'VS', 'WC', 'WCN', 'WP', 'XV', 'XX'],
        'display' => ["AC" => "Accreditation/Certification Identifier", "ACSN" => "Accession ID", "AM" => "American Express", "AMA" => "American Medical Association Number", "AN" => "Account number", "ANC" => "Account number Creditor", "AND" => "Account number debitor", "ANON" => "Anonymous identifier", "ANT" => "Temporary Account Number", "APRN" => "Advanced Practice Registered Nurse number", "ASID" => "Ancestor Specimen ID", "BA" => "Bank Account Number", "BC" => "Bank Card Number", "BCFN" => "Birth Certificate File Number", "BCT" => "Birth Certificate", "BR" => "Birth registry number", "BRN" => "Breed Registry Number", "BSNR" => "Primary physician office number", "CC" => "Cost Center number", "CONM" => "Change of Name Document", "CY" => "County number", "CZ" => "Citizenship Card", "DC" => "Death Certificate ID", "DCFN" => "Death Certificate File Number", "DDS" => "Dentist license number", "DEA" => "Drug Enforcement Administration registration number", "DFN" => "Drug Furnishing or prescriptive authority Number", "DI" => "Diner's Club card", "DL" => "Driver's license number", "DN" => "Doctor number", "DO" => "Osteopathic License number", "DP" => "Diplomatic Passport", "DPM" => "Podiatrist license number", "DR" => "Donor Registration Number", "DS" => "Discover Card", "EI" => "Employee number", "EN" => "Employer number", "ESN" => "Staff Enterprise Number", "FDR" => "Fetal Death Report ID", "FDRFN" => "Fetal Death Report File Number", "FI" => "Facility ID", "FILL" => "Filler Identifier", "GI" => "Guarantor internal identifier", "GL" => "General ledger number", "GN" => "Guarantor external identifier", "HC" => "Health Card Number", "IND" => "Indigenous/Aboriginal", "JHN" => "Jurisdictional health number (Canada)", "LACSN" => "Laboratory Accession ID", "LANR" => "Lifelong physician number", "LI" => "Labor and industries number", "LN" => "License number", "LR" => "Local Registry ID", "MA" => "Patient Medicaid number", "MB" => "Member Number", "MC" => "Patient's Medicare number", "MCD" => "Practitioner Medicaid number", "MCN" => "Microchip Number", "MCR" => "Practitioner Medicare number", "MCT" => "Marriage Certificate", "MD" => "Medical License number", "MI" => "Military ID number", "MR" => "Medical record number", "MRT" => "Temporary Medical Record Number", "MS" => "MasterCard", "NBSNR" => "Secondary physician office number", "NCT" => "Naturalization Certificate", "NE" => "National employer identifier", "NH" => "National Health Plan Identifier", "NI" => "National unique individual identifier", "NII" => "National Insurance Organization Identifier", "NIIP" => "National Insurance Payor Identifier (Payor)", "NNxxx" => "National Person Identifier where the xxx is the ISO table 3166 3-character (alphabetic) country code", "NP" => "Nurse practitioner number", "NPI" => "National provider identifier", "OBI" => "Observation Instance Identifier", "OD" => "Optometrist license number", "PA" => "Physician Assistant number", "PC" => "Parole Card", "PCN" => "Penitentiary/correctional institution Number", "PE" => "Living Subject Enterprise Number", "PEN" => "Pension Number", "PHC" => "Public Health Case Identifier", "PHE" => "Public Health Event Identifier", "PHO" => "Public Health Official ID", "PI" => "Patient internal identifier", "PLAC" => "Placer Identifier", "PN" => "Person number", "PNT" => "Temporary Living Subject Number", "PPIN" => "Medicare/CMS Performing Provider Identification Number", "PPN" => "Passport number", "PRC" => "Permanent Resident Card Number", "PRN" => "Provider number", "PT" => "Patient external identifier", "QA" => "QA number", "RI" => "Resource identifier", "RN" => "Registered Nurse Number", "RPH" => "Pharmacist license number", "RR" => "Railroad Retirement number", "RRI" => "Regional registry ID", "RRP" => "Railroad Retirement Provider", "SB" => "Social Beneficiary Identifier", "SID" => "Specimen ID", "SL" => "State license", "SN" => "Subscriber Number", "SNBSN" => "State assigned NDBS card Identifier", "SNO" => "Serial Number", "SP" => "Study Permit", "SR" => "State registry ID", "SS" => "Social Security number", "STN" => "Shipment Tracking Number", "TAX" => "Tax ID number", "TN" => "Treaty Number/ (Canada)", "TPR" => "Temporary Permanent Resident (Canada)", "TRL" => "Training License Number", "U" => "Unspecified identifier", "UDI" => "Universal Device Identifier", "UPIN" => "Medicare/CMS (formerly HCFA)'s Universal Physician Identification numbers", "USID" => "Unique Specimen ID", "VN" => "Visit number", "VP" => "Visitor Permit", "VS" => "VISA", "WC" => "WIC identifier", "WCN" => "Workers' Comp Number", "WP" => "Work Permit", "XV" => "Health Plan Identifier", "XX" => "Organization identifier"],
    ];

    public const ResourceType = [
        'system' => 'http://hl7.org/fhir/resource-types',
        'table' => 'codesystem_resourcetype'
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
