<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\KodeWilayah;
use App\Models\KotaKabupaten;
use App\Models\Provinsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $file = Storage::url('app/public/Lookup Wilayah.csv'); // Data 92k++ rows (provinsi, kota, kecamatan, kelurahan)
        $file = Storage::url('app/public/Lookup Kota.csv'); // Data hanya provinsi dan kota saja
        if (($handle = fopen('.' . $file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                KodeWilayah::create(
                    [
                        'kode' => $data[0],
                        'kategori' => $data[1],
                        'nama' => $data[2]
                    ]
                );
            }
            fclose($handle);
        }
    }
}
