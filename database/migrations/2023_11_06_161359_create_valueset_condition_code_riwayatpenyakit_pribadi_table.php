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
        Schema::create('valueset_condition_code_riwayatpenyakit_pribadi', function (Blueprint $table) {
            $table->unsignedBigInteger('code')->primary();
            $table->string('display');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valueset_condition_code_riwayatpenyakit_pribadi');
    }
};
