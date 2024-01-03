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
        Schema::create('dose_and_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dosage_id');
            $table->index('dosage_id');
            $table->foreign('dosage_id')->references('id')->on('dosages');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dose_and_rates');
    }
};
