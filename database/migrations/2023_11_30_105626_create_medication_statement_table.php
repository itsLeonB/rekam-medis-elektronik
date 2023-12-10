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
        Schema::create('medication_statement', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->json('based_on')->nullable();
            $table->json('part_of')->nullable();
            $table->enum('status', ['active', 'completed', 'entered-in-error', 'intended', 'stopped', 'on-hold', 'unknown', 'not-taken'])->nullable();
            $table->json('status_reason')->nullable();
            $table->enum('category', ['inpatient', 'outpatient', 'community', 'patientspecified'])->nullable();
            $table->json('medication');
            $table->string('subject');
            $table->string('context')->nullable();
            $table->json('effective')->nullable();
            $table->dateTime('date_asserted')->nullable();
            $table->string('information_source')->nullable();
            $table->json('derived_from')->nullable();
            $table->json('reason_reference')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_statement');
    }
};
