<?php

namespace Database\Seeders\Valuesets;

use Illuminate\Support\Facades\DB;
use JeroenZwart\CsvSeeder\CsvSeeder;

class RiwayatPenyakitPribadiSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->file = '/database/seeders/csvs/valuesets/riwayatpenyakitpribadi.csv';
        $this->tablename = 'valueset_riwayatpenyakitpribadi';
        $this->timestamps = false;
        $this->delimiter = ',';
    }

    public function run()
    {
        // Recommended when importing larger CSVs
        DB::disableQueryLog();
        parent::run();
    }
}
