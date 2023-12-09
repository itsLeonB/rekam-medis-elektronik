<?php

namespace Database\Seeders\Valuesets;

use Illuminate\Support\Facades\DB;
use JeroenZwart\CsvSeeder\CsvSeeder;

class ProcedurePerformerRoleCodesSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->file = '/database/seeders/csvs/valuesets/procedureperformerrolecodes.csv';
        $this->tablename = 'valueset_procedureperformerrolecodes';
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
