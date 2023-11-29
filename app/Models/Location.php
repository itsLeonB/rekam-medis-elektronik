<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($location) {
            $orgId = config('app.organization_id');

            $identifier = new LocationIdentifier();
            $identifier->system = 'http://sys-ids.kemkes.go.id/location/' . $orgId;
            $identifier->use = 'official';
            $identifier->value = $location->identifier()->max('value') + 1;

            // Save the identifier through the relationship
            $location->identifier()->save($identifier);
        });
    }

    public const STATUS_SYSTEM = 'http://hl7.org/fhir/location-status';
    public const STATUS_CODE = ['active', 'suspended', 'inactive'];
    public const STATUS_DISPLAY = ['active' => 'Lokasi sedang beroperasi', 'suspended' => 'Lokasi ditutup sementara', 'inactive' => 'Lokasi tidak lagi digunakan'];

    public const OPERATIONAL_STATUS_SYSTEM = 'http://terminology.hl7.org/CodeSystem/v2-0116';
    public const OPERATIONAL_STATUS_CODE = ['C', 'H', 'I', 'K', 'O', 'U'];
    public const OPERATIONAL_STATUS_DISPLAY = ['C' => 'Closed', 'H' => 'Housekeeping', 'I' => 'Isolated', 'K' => 'Contaminated', 'O' => 'Occupied', 'U' => 'Unoccupied'];
    public const OPERATIONAL_STATUS_DEFINITION = ['C' => 'Tutup', 'H' => 'Dalam pembersihan', 'I' => 'Isolasi', 'K' => 'Terkontaminasi', 'O' => 'Terisi', 'U' => 'Tidak terisi'];

    public const MODE_SYSTEM = 'http://hl7.org/fhir/location-mode';
    public const MODE_CODE = ['instance', 'kind'];
    public const MODE_DISPLAY = ['instance' => 'Merepresentasikan lokasi spesifik', 'kind' => 'Merepresentasikan kelompok/kelas lokasi'];

    public const PHYSICAL_TYPE_SYSTEM = ['si' => 'http://terminology.hl7.org/CodeSystem/location-physical-type', 'bu' => 'http://terminology.hl7.org/CodeSystem/location-physical-type', 'wi' => 'http://terminology.hl7.org/CodeSystem/location-physical-type', 'wa' => 'http://terminology.hl7.org/CodeSystem/location-physical-type', 'lvl' => 'http://terminology.hl7.org/CodeSystem/location-physical-type', 'co' => 'http://terminology.hl7.org/CodeSystem/location-physical-type', 'ro' => 'http://terminology.hl7.org/CodeSystem/location-physical-type', 'bd' => 'http://terminology.hl7.org/CodeSystem/location-physical-type', 've' => 'http://terminology.hl7.org/CodeSystem/location-physical-type', 'ho' => 'http://terminology.hl7.org/CodeSystem/location-physical-type', 'ca' => 'http://terminology.hl7.org/CodeSystem/location-physical-type', 'rd' => 'http://terminology.hl7.org/CodeSystem/location-physical-type', 'area' => 'http://terminology.hl7.org/CodeSystem/location-physical-type', 'jdn' => 'http://terminology.hl7.org/CodeSystem/location-physical-type', 'vir' => 'http://terminology.kemkes.go.id/CodeSystem/location-physical-type'];
    public const PHYSICAL_TYPE_CODE = ['si', 'bu', 'wi', 'wa', 'lvl', 'co', 'ro', 'bd', 've', 'ho', 'ca', 'rd', 'area', 'jdn', 'vir'];
    public const PHYSICAL_TYPE_DISPLAY = ['si' => 'Site', 'bu' => 'Building', 'wi' => 'Wing', 'wa' => 'Ward', 'lvl' => 'Level', 'co' => 'Corridor', 'ro' => 'Room', 'bd' => 'Bed', 've' => 'Vehicle', 'ho' => 'House', 'ca' => 'Cabinet', 'rd' => 'Road', 'area' => 'Area', 'jdn' => 'Jurisdiction', 'vir' => 'Virtual'];
    public const PHYSICAL_TYPE_DEFINITION = ['si' => 'Kumpulan bangunan atau lokasi lain seperti kompleks atau kampus.', 'bu' => 'Setiap Bangunan atau struktur.', 'wi' => 'Sayap di dalam Gedung, sering berisi lantai, kamar, dan koridor.', 'wa' => 'Bangsal adalah bagian dari fasilitas medis yang mungkin berisi kamar dan jenis lokasi lainnya', 'lvl' => 'Lantai di Gedung/Struktur', 'co' => 'Setiap koridor di dalam Gedung, yang dapat menghubungkan kamar-kamar', 'ro' => 'Sebuah ruang yang dialokasikan sebagai ruangan', 'bd' => 'Tempat tidur yang dapat ditempati', 've' => 'Alat transportasi', 'ho' => 'Rumah', 'ca' => 'Wadah yang dapat menyimpan barang, peralatan, obat-obatan atau barang lainnya.', 'rd' => 'Jalan', 'area' => 'Area (contoh : zona risiko banjir, wilayah, wilayah kodepos)', 'jdn' => 'Negara, Provinsi', 'vir' => 'Virtual'];

    public const SERVICE_CLASS_SYSTEM = ['1' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient', '2' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient', '3' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient', 'vip' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient', 'vvip' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient', 'reguler' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Outpatient', 'eksekutif' => 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Outpatient'];
    public const SERVICE_CLASS_CODE = ['1', '2', '3', 'vip', 'vvip', 'reguler', 'eksekutif'];
    public const SERVICE_CLASS_DISPLAY = ['1' => 'Kelas 1', '2' => 'Kelas 2', '3' => 'Kelas 3', 'vip' => 'Kelas VIP', 'vvip' => 'Kelas VVIP', 'reguler' => 'Kelas Reguler', 'eksekutif' => 'Kelas Eksekutif'];
    public const SERVICE_CLASS_DEFINITION = ['1' => 'Perawatan Kelas 1', '2' => 'Perawatan Kelas 2', '3' => 'Perawatan Kelas 3', 'vip' => 'Perawatan Kelas VIP', 'vvip' => 'Perawatan Kelas VVIP', 'reguler' => 'Perawatan Kelas Reguler', 'eksekutif' => 'Perawatan Kelas Eksekutif'];

    public const TYPE_SYSTEM = 'http://terminology.kemkes.go.id/CodeSystem/location-type';
    public const TYPE_CODE = ['RT0001', 'RT0002', 'RT0003', 'RT0004', 'RT0005', 'RT0006', 'RT0007', 'RT0008', 'RT0009', 'RT0010', 'RT0011', 'RT0012', 'RT0013', 'RT0014', 'RT0015', 'RT0016', 'RT0017', 'RT0018', 'RT0019', 'RT0020', 'RT0021', 'RT0022', 'RT0023', 'RT0024', 'RT0025', 'RT0026', 'RT0027', 'RT0028', 'RT0029', 'RT0030', 'RT0031', 'RT0032', 'RT0033'];
    public const TYPE_DISPLAY = ['RT0001' => 'Wahana PIDI', 'RT0002' => 'Wahana PIDGI', 'RT0003' => 'RS Pendidikan', 'RT0004' => 'Tempat Tidur', 'RT0005' => 'Bank Darah', 'RT0006' => 'Instalasi Gawat Darurat', 'RT0007' => 'Ruang Perawatan Intensif Umum (ICU)', 'RT0008' => 'Ruangan Persalinan', 'RT0009' => 'Ruang Perawatan Intensif', 'RT0010' => 'Daerah Rawat Pasien ICU/ICCU/HCU/ PICU', 'RT0011' => 'Ruangan Perawatan Intensif Pediatrik (PICU)', 'RT0012' => 'Ruangan Perawatan Intensif Neonatus(NICU)', 'RT0013' => 'High Care Unit (HCU)', 'RT0014' => 'Intensive Cardiology Care Unit (ICCU)', 'RT0015' => 'Respiratory Intensive Care Unit (RICU)', 'RT0016' => 'Ruang Rawat Inap', 'RT0017' => 'Ruangan Perawatan (Post Partum)', 'RT0018' => 'Ruangan Perawatan Isolasi', 'RT0019' => 'Ruangan Perawatan Neonatus Infeksius/ Isolasi', 'RT0020' => 'Ruangan Perawatan Neonatus Non Infeksius', 'RT0021' => 'Ruangan Perawatan Pasien Paska Terapi', 'RT0022' => 'Ruangan Rawat Pasca Persalinan', 'RT0023' => 'Ruangan/ Daerah Rawat Pasien Isolasi', 'RT0024' => 'Ruangan/ Daerah Rawat Pasien Non Isolasi', 'RT0025' => 'Ruang Operasi', 'RT0026' => 'Ruangan Observasi', 'RT0027' => 'Ruangan Resusitasi', 'RT0028' => 'Ruangan Tindakan Anak', 'RT0029' => 'Ruangan Tindakan Bedah', 'RT0030' => 'Ruangan Tindakan Kebidanan', 'RT0031' => 'Ruangan Tindakan Non-Bedah', 'RT0032' => 'Ruangan Triase', 'RT0033' => 'Ruangan Ultra Sonografi (USG)'];

    protected $table = 'location';
    protected $casts = [
        'type' => 'array',
        'address_line' => 'array',
        'longitude' => 'double',
        'latitude' => 'double',
        'altitude' => 'double',
        'endpoint' => 'array'
    ];
    public $timestamps = false;

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function identifier(): HasMany
    {
        return $this->hasMany(LocationIdentifier::class);
    }

    public function telecom(): HasMany
    {
        return $this->hasMany(LocationTelecom::class);
    }

    public function operationHours(): HasMany
    {
        return $this->hasMany(LocationOperationHours::class);
    }
}
