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
            $$table->unsignedBigInteger('allergy_id');
            $table->index('allergy_id');
            $table->foreign('allergy_id')->references('id')->on('allergy_intolerance')->onDelete('cascade');
            $table->string('substance_system')->nullable();
            $table->string('substance_code')->nullable();
            $table->string('substance_display')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('onset')->nullable();
            $table->enum('severity', ['mild', 'moderate', 'severe'])->nullable();
            $table->string('exposure_route_system')->nullable();
            $table->string('exposure_route_code')->nullable();
            $table->string('exposure_route_display')->nullable();
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
