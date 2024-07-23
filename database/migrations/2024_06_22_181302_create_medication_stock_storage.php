<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('medicines', function (Blueprint $collection) {
            $collection->uuid();
            $collection->string('medicine_code');
            $collection->string('name');
            $collection->date('expiry_date');
            $collection->integer('quantity');
            $collection->string('package');
            $collection->string('uom');
            $collection->integer('amount_per_package');
            $collection->string('manufacturer');
            $collection->boolean('is_fast_moving');
            $collection->json('ingredients');
            $collection->integer('minimum_quantity');
            $collection->string('dosage_form');
            $collection->json('prices');
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
