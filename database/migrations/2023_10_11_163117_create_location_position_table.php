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
        Schema::create('location_position', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id');
            $table->index('location_id');
            $table->foreign('location_id')->references('id')->on('location')->onDelete('cascade');
            $table->double('longitude');
            $table->double('latitude');
            $table->double('altitude')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_position');
    }
};
