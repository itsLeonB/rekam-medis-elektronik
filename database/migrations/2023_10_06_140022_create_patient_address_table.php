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
        Schema::create('patient_address', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->index('patient_id');
            $table->foreign('patient_id')->references('id')->on('patient')->onDelete('cascade');
            $table->enum('use', ['home', 'work', 'temp', 'old', 'billing'])->nullable();
            $table->enum('type', ['postal', 'physical', 'both'])->nullable();
            $table->json('line')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->unsignedInteger('province')->nullable();
            $table->unsignedInteger('city')->nullable();
            $table->unsignedBigInteger('district')->nullable();
            $table->unsignedBigInteger('village')->nullable();
            $table->unsignedInteger('rw')->nullable();
            $table->unsignedInteger('rt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_address');
    }
};
