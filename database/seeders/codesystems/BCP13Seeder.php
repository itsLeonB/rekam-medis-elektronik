<?php

namespace Database\Seeders\Codesystems;

use Illuminate\Support\Facades\DB;
use JeroenZwart\CsvSeeder\CsvSeeder;

class Bcp13Seeder extends CsvSeeder
{
    public function __construct()
    {
        $this->file = '/database/seeders/csvs/codesystems/bcp13.csv';
        $this->tablename = 'codesystem_bcp13';
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
