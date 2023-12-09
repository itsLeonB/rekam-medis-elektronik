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
        Schema::create('valueset_observation_refrangeappliesto', function (Blueprint $table) {
            $table->string('code', 9)->primary();
            $table->string('system', 45);
            $table->string('display', 43);
            $table->string('definition', 122)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valueset_observation_refrangeappliesto');
    }
};
