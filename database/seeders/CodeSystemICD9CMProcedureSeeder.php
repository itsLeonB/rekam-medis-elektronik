<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use JeroenZwart\CsvSeeder\CsvSeeder;

class CodeSystemICD9CMProcedureSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->file = '/database/seeders/csvs/codesystem_icd9cm_procedure.csv';
        $this->timestamps = false;
        $this->delimiter = ';';
    }

    public function run()
    {
        // Recommended when importing larger CSVs
        DB::disableQueryLog();
        parent::run();
    }
}
