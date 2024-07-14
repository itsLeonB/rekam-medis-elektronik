<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\MedicineTransaction;
use App\Models\Medicine;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicineTransaction>
 */
class MedicineTransactionFactory extends Factory
{
    protected $model = MedicineTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $medicine = Medicine::all()->random();
        $createdAt = now()->addDays(rand(-700, 0));
        return [
            '_id' => (string) Str::uuid(),
            'id_transaction' => (string) Str::uuid(),
            'id_medicine' => $medicine ? $medicine->id : (string) Str::uuid(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'note' => 'coba catatan',
            'created_at' => $createdAt, 
            'updated_at' => $createdAt,
        ];
    }
}
