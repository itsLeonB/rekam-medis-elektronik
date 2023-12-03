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
        Schema::create('encounter_location', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('encounter_id');
            $table->index('encounter_id');
            $table->foreign('encounter_id')->references('id')->on('encounter')->onDelete('cascade');
            $table->string('location');
            $table->enum('service_class', ['1', '2', '3', 'vip', 'vvip', 'reguler', 'eksekutif'])->nullable();
            $table->enum('upgrade_class', ['kelas-tetap', 'naik-kelas', 'turun-kelas', 'titip-rawat'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounter_location');
    }
};
