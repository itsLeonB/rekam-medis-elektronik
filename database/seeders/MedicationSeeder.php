<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;
use Illuminate\Support\Str;
use Faker\Factory as Faker;


class MedicationSeeder extends Seeder
{
    
    public function run()
    {
        $faker = Faker::create();

        // Create a sample medicine price
        for ($i = 1; $i <= 1000; $i++) {
            Medicine::create([
                '_id' => (string) Str::uuid(),
                'medicine_code' => 'MED' . $i,
                'name' => 'Medicine ' . $i,
                'expiry_date' => now()->addDays(rand(-100, 500)),
                'quantity' => rand(50, 200),
                'package' => $faker->randomElement(['Box', 'Bottle', 'Pack']),
                'uom' => $faker->randomElement([ "Krim", "Salep", "Gel", "Suntikan", "Tetes Mata", "Tetes Telinga", "Inhaler", "Suppositoria", "Larutan", "Serbuk", "Suspensi", "Transdermal Patch", "Lozenges", "Granul", "Lioserat"]),
                'amount_per_package' => 10,
                'manufacturer' => 'Pharma Inc.',
                'is_fast_moving' => rand(0, 1) == 1,
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
                'minimum_quantity' => rand(5, 100),
                'dosage_form' => $faker->randomElement(['Oral', 'Topical', 'Injection']),
                'prices' => [
                    'base_price' => rand(100, 500),
                    'purchase_price' => rand(150, 600),
                    'treatment_price_1' => rand(100, 1000),
                    'treatment_price_2' => rand(100, 1000),
                    'treatment_price_3' => rand(100, 1000),
                    'treatment_price_4' => rand(100, 1000),
                    'treatment_price_5' => rand(100, 1000),
                    'treatment_price_6' => rand(100, 1000),
                    'treatment_price_7' => rand(100, 1000),
                    'treatment_price_8' => rand(100, 1000),
                    'treatment_price_9' => rand(100, 1000),
                ],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}