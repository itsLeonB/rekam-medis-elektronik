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
        Schema::create('obs_comp_ref_range', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('obs_comp_id');
            $table->index('obs_comp_id');
            $table->foreign('obs_comp_id')->references('id')->on('observation_component')->onDelete('cascade');
            $table->decimal('low_value')->nullable();
            $table->string('low_unit')->nullable();
            $table->string('low_system')->nullable();
            $table->string('low_code')->nullable();
            $table->decimal('high_value')->nullable();
            $table->string('high_unit')->nullable();
            $table->string('high_system')->nullable();
            $table->string('high_code')->nullable();
            $table->string('type')->nullable();
            $table->json('applies_to')->nullable();
            $table->unsignedInteger('age_low')->nullable();
            $table->unsignedInteger('age_high')->nullable();
            $table->text('text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obs_comp_ref_range');
    }
};
