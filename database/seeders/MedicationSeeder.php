<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;
use Illuminate\Support\Str;


class MedicationSeeder extends Seeder
{
    
    public function run()
    {

        // Create a sample medicine price
        Medicine::create([
            'medicine_code' => 'MED123',
            'name' => 'Paracetamol',
            'expiry_date' => now()->addYear(),
            'quantity' => 100,
            'package' => 'Box',
            'uom' => 'Tablet',
            'amount_per_package' => 10,
            'manufacturer' => 'Pharma Inc.',
            'is_fast_moving' => true,
            'ingredients' => [
                (object)[
                    'ingredient_id' => Str::uuid(),
                    'ingredient_name' => 'Ingredient 1'
                ],
                (object)[
                    'ingredient_id' => Str::uuid(),
                    'ingredient_name' => 'Ingredient 2'
                ],
            ],
            'minimum_quantity' => 10,
            'dosage_form' => 'Tablet',
            'prices' => (array)[
                'base_price' => 122,
                'purchase_price' => 213,
                'treatment_prices' => [
                    'treatment_price_1' => 143,
                    'treatment_price_2' => 534,
                    'treatment_price_3' => 354,
                    'treatment_price_4' => 465,
                    'treatment_price_5' => 567,
                    'treatment_price_6' => 243,
                    'treatment_price_7' => 456,
                    'treatment_price_8' => 235,
                    'treatment_price_9' => 312,
                ]
            ],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}