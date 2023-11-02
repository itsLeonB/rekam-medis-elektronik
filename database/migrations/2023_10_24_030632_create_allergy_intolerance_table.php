<?php

use App\Models\AllergyIntolerance;
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
        Schema::create('allergy_intolerance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->enum('clinical_status', AllergyIntolerance::CLINICAL_STATUS_CODE)->nullable();
            $table->enum('verification_status', AllergyIntolerance::VERIFICATION_STATUS_CODE)->nullable();
            $table->enum('type', AllergyIntolerance::TYPE_CODE)->nullable();
            $table->boolean('category_food');
            $table->boolean('category_medication');
            $table->boolean('category_environment');
            $table->boolean('category_biologic');
            $table->enum('criticality', AllergyIntolerance::CRITICALITY_CODE)->nullable();
            $table->string('code_system')->nullable();
            $table->string('code_code');
            $table->string('code_display')->nullable();
            $table->string('patient');
            $table->string('encounter')->nullable();
            $table->json('onset')->nullable();
            $table->dateTime('recorded_date')->nullable();
            $table->string('recorder')->nullable();
            $table->string('asserter')->nullable();
            $table->dateTime('last_occurence')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allergy_intolerance');
    }
};
