<?php

namespace Database\Seeders\Codesystems;

use Illuminate\Support\Facades\DB;
use JeroenZwart\CsvSeeder\CsvSeeder;

class ResourceTypeSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->file = '/database/seeders/csvs/codesystems/resourcetype.csv';
        $this->tablename = 'codesystem_servicetype';
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
