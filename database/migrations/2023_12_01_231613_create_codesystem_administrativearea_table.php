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
        Schema::create('codesystem_administrativearea', function (Blueprint $table) {
            $table->id();
            $table->char('kode_kelurahan', 10)->unique()->index();
            $table->string('nama_kelurahan', 37)->nullable();
            $table->char('kode_kecamatan', 6)->index();
            $table->string('nama_kecamatan', 31)->nullable();
            $table->char('kode_kabko', 4)->index();
            $table->string('nama_kabko', 33)->nullable();
            $table->char('kode_provinsi', 2)->index();
            $table->string('nama_provinsi', 25)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codesystem_administrativearea');
    }
};
