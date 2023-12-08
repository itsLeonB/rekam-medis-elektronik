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
        Schema::create('medication_dispense_performer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispense_id');
            $table->index('dispense_id');
            $table->foreign('dispense_id')->references('id')->on('medication_dispense')->onDelete('cascade');
            $table->string('function')->nullable();
            $table->string('actor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_dispense_performer');
    }
};
