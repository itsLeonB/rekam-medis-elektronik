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
        Schema::create('medication_request_dosage', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('med_req_id');
            $table->index('med_req_id');
            $table->foreign('med_req_id')->references('id')->on('medication_request')->onDelete('cascade');
            $table->integer('sequence')->nullable();
            $table->string('text')->nullable();
            $table->json('additional_instruction')->nullable();
            $table->string('patient_instruction')->nullable();
            $table->json('timing_event')->nullable();
            $table->json('timing_repeat')->nullable();
            $table->string('timing_code')->nullable();
            $table->string('site')->nullable();
            $table->string('route')->nullable();
            $table->string('method')->nullable();
            $table->decimal('max_dose_per_period_numerator_value')->nullable();
            $table->enum('max_dose_per_period_numerator_comparator', ['<', '<=', '>=', '>'])->nullable();
            $table->string('max_dose_per_period_numerator_unit')->nullable();
            $table->string('max_dose_per_period_numerator_system')->nullable();
            $table->string('max_dose_per_period_numerator_code')->nullable();
            $table->decimal('max_dose_per_period_denominator_value')->nullable();
            $table->enum('max_dose_per_period_denominator_comparator', ['<', '<=', '>=', '>'])->nullable();
            $table->string('max_dose_per_period_denominator_unit')->nullable();
            $table->string('max_dose_per_period_denominator_system')->nullable();
            $table->string('max_dose_per_period_denominator_code')->nullable();
            $table->decimal('max_dose_per_administration_value')->nullable();
            $table->string('max_dose_per_administration_unit')->nullable();
            $table->string('max_dose_per_administration_system')->nullable();
            $table->string('max_dose_per_administration_code')->nullable();
            $table->decimal('max_dose_per_lifetime_value')->nullable();
            $table->string('max_dose_per_lifetime_unit')->nullable();
            $table->string('max_dose_per_lifetime_system')->nullable();
            $table->string('max_dose_per_lifetime_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_request_dosage');
    }
};
