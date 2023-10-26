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
        Schema::create('med_disp_dosage_dose_rate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('med_disp_dose_id');
            $table->index('med_disp_dose_id');
            $table->foreign('med_disp_dose_id')->references('id')->on('medication_dispense_dosage')->onDelete('cascade');
            $table->string('system')->nullable();
            $table->string('code')->nullable();
            $table->string('display')->nullable();
            $table->json('dose')->nullable();
            $table->json('rate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('med_disp_dosage_dose_rate');
    }
};
