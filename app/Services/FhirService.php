<?php

namespace App\Services;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FhirService
{
    public function insertData(callable $callback)
    {
        DB::beginTransaction();

        try {
            $result = $callback();

            DB::commit();

            return $result;
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Database error: ' . $e->getMessage());

            return response()->json(['error' => 'Database error dalam input data pasien baru.'], 500);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage());

            return response()->json(['error' => 'Server error dalam input data pasien baru.'], 500);
        }
    }
}
