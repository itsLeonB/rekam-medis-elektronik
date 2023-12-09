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
        Schema::create('codesystem_icd9cmprocedure', function (Blueprint $table) {
            $table->string('code', 5)->primary();
            $table->string('display', 24);
            $table->string('definition', 163);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codesystem_icd9cmprocedure');
    }
};
