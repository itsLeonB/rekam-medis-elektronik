<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use JeroenZwart\CsvSeeder\CsvSeeder;

class CodeSystemEncounterReasonSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->file = '/database/seeds/csvs/codesystem_encounterreason.csv';
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