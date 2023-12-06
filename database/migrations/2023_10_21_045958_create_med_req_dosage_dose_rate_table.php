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
        Schema::create('med_req_dosage_dose_rate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('med_req_dosage_id');
            $table->index('med_req_dosage_id');
            $table->foreign('med_req_dosage_id')->references('id')->on('medication_request_dosage')->onDelete('cascade');
            $table->enum('type', ['calculated', 'ordered'])->nullable();
            $table->json('dose')->nullable();
            $table->json('rate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('med_req_dosage_dose_rate');
    }
};
