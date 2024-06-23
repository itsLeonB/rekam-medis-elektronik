<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class MedicationSeeder extends Seeder
{
    
    public function run()
    {

        // Create a sample medicine price
        $medicinePriceId = DB::table('medicine_prices')->insertGetId([
            'base_price' => 100,
            'purchase_price' => 80,
            'treatment_price_1' => 120,
            // Add other fields as necessary
        ]);

        // Create sample medicines
        DB::table('medicines')->insert([
            'medicine_code' => 'MED001',
            'name' => 'Paracetamol',
            'expiry_date' => now()->addMonths(6),
            'quantity' => 50,
            'package' => 'Bottle',
            'uom' => 'mg',
            'amount_per_package' => 20,
            'manufacturer' => 'Generic Pharma',
            'is_fast_moving' => true,
            'ingredients' => json_encode(['Paracetamol']),
            'minimum_quantity' => 10,
            'dosage_form' => 'Tablet',
            'medicine_prices_id' => $medicinePriceId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}