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
        Schema::create('valueset_allergy_reactionsubstance', function (Blueprint $table) {
            $table->string('code', 15)->primary();
            $table->string('display', 238);
            $table->string('system', 56);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valueset_allergy_reactionsubstance');
    }
};
