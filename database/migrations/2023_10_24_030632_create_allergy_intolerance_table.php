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
        Schema::create('allergy_intolerance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->enum('type', ['allergy', 'intolerance'])->nullable();
            $table->json('category');
            $table->enum('criticality', ['low', 'high', 'unable-to-assess'])->nullable();
            $table->dateTime('onset_date_time')->nullable();
            $table->string('onset_string')->nullable();
            $table->dateTime('recorded_date')->nullable();
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
