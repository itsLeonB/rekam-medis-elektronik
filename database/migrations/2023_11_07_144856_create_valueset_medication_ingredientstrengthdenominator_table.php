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
        Schema::create('valueset_medication_ingredientstrengthdenominator', function (Blueprint $table) {
            $table->char('code', 10)->primary();
            $table->string('system', 58);
            $table->string('display', 45);
            $table->string('definition', 352)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valueset_medication_ingredientstrengthdenominator');
    }
};
