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
        Schema::create('medication_ingredient', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medication_id');
            $table->index('medication_id');
            $table->foreign('medication_id')->references('id')->on('medication')->onDelete('cascade');
            $table->boolean('is_active')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_ingredient');
    }
};
