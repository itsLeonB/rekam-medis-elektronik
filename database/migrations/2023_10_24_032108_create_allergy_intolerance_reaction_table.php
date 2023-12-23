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
        Schema::create('allergy_intolerance_reaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allergy_id');
            $table->index('allergy_id');
            $table->foreign('allergy_id')->references('id')->on('allergy_intolerance')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->dateTime('onset')->nullable();
            $table->enum('severity', ['mild', 'moderate', 'severe'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allergy_intolerance_reaction');
    }
};
