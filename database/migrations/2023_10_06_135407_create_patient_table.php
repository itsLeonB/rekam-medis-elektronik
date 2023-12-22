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
        Schema::create('patient', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->boolean('active');
            $table->enum('gender', ['male', 'female', 'other', 'unknown']);
            $table->date('birth_date')->nullable();
            $table->boolean('deceased_boolean')->nullable();
            $table->dateTime('deceased_date_time')->nullable();
            $table->boolean('multiple_birth_boolean')->nullable();
            $table->integer('multiple_birth_integer')->nullable();
            // $table->string('marital_status')->nullable();
            // $table->json('multiple_birth');
            // $table->json('general_practitioner')->nullable();
            // $table->string('managing_organization')->nullable();
            // $table->string('birth_city')->nullable();
            // $table->string('birth_country')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient');
    }
};
