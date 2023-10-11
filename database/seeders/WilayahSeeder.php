<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
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
        $file = Storage::url('app/public/Lookup Wilayah.csv');
        $row = 1;
        if (($handle = fopen('.' . $file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $wilayah = $data[1];
                switch ($wilayah) {
                    case 'Provinsi':
                        Provinsi::create(
                            [
                                'kode_wilayah' => $data[2],
                                'nama_wilayah' => $data[3]
                            ]
                        );
                        break;
                    case 'Kota Kabupaten':
                        KotaKabupaten::create(
                            [
                                'kode_wilayah' => $data[2],
                                'nama_wilayah' => $data[3]
                            ]
                        );
                        break;
                    // case 'Kecamatan':
                    //     Kecamatan::create(
                    //         [
                    //             'kode_wilayah' => $data[2],
                    //             'nama_wilayah' => $data[3]
                    //         ]
                    //     );
                    //     break;
                    // case 'Kelurahan':
                    //     Kelurahan::create(
                    //         [
                    //             'kode_wilayah' => $data[2],
                    //             'nama_wilayah' => $data[3]
                    //         ]
                    //     );
                    //     break;
                }
                $row++;
            }
            fclose($handle);
        }
    }
}
