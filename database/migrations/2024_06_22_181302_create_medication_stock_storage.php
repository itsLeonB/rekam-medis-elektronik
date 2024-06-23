<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medicine', function (Blueprint $table) {
            $table->string('medicine_code')->primary()->unique();
            $table->string('name');
            $table->date('expiry_date');
            $table->integer('quantity');
            $table->string('package');
            $table->string('uom');
            $table->integer('amount_per_package');
            $table->string('manufacturer');
            $table->boolean('is_fast_moving')->nullable();
            $table->json('ingridients');
            $table->integer('minimum_quantity');
            $table->string('dosage_form');
            $table->foreignId('medicine_prices_id')->constrained('medicine_prices')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('medicine_prices', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->integer('base_price');
            $table->integer('purchase_price');
            $table->integer('treatment_price_1')->nullable();
            $table->integer('treatment_price_2')->nullable();
            $table->integer('treatment_price_3')->nullable();
            $table->integer('treatment_price_4')->nullable();
            $table->integer('treatment_price_5')->nullable();
            $table->integer('treatment_price_6')->nullable();
            $table->integer('treatment_price_7')->nullable();
            $table->integer('treatment_price_8')->nullable();
            $table->integer('treatment_price_9')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine');
        Schema::dropIfExists('medicine_prices');
    }
};
