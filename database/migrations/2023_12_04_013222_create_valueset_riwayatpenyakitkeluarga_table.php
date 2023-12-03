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
        Schema::create('valueset_riwayatpenyakitkeluarga', function (Blueprint $table) {
            $table->string('code', 18)->primary();
            $table->string('display', 110);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valueset_riwayatpenyakitkeluarga');
    }
};
