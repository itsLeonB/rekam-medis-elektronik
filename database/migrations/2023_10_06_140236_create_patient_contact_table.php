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
        Schema::create('patient_contact', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->index('patient_id');
            $table->foreign('patient_id')->references('id')->on('patient')->onDelete('cascade');
            $table->json('relationship');
            $table->string('name_text')->nullable();
            $table->string('name_family')->nullable();
            $table->json('name_given')->nullable();
            $table->json('name_prefix')->nullable();
            $table->json('name_suffix')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'unknown'])->nullable();
            $table->enum('address_use', ['home', 'work', 'temp', 'old', 'billing'])->nullable();
            $table->enum('address_type', ['postal', 'physical', 'both'])->nullable();
            $table->json('address_line')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->unsignedInteger('province')->nullable();
            $table->unsignedInteger('city')->nullable();
            $table->unsignedBigInteger('district')->nullable();
            $table->unsignedBigInteger('village')->nullable();
            $table->unsignedInteger('rw')->nullable();
            $table->unsignedInteger('rt')->nullable();
            $table->string('organization')->nullable();
            $table->dateTime('period_start')->nullable();
            $table->dateTime('period_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_contact');
    }
};
