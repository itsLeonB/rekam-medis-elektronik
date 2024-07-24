<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function show()
    {
        $connection = DB::connection('mongodb');
        $msg = "Success";
        try {
            $connection->command(['ping' => 1]);
            $data = Invoice::all();
        } catch (Exception $e) {
            $msg = "MongoDB is not accessible. Error: " . $e->getMessage();
        }

        return ['msg' => $msg, 'data' => $data];
    }
}
