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
            $table->decimal('value_low')->nullable();
            $table->decimal('value_high')->nullable();
            $table->string('unit')->nullable();
            $table->string('system')->nullable();
            $table->string('code')->nullable();
            $table->string('type')->nullable();
            $table->json('applies_to')->nullable();
            $table->integer('age_low')->nullable();
            $table->integer('age_high')->nullable();
            $table->string('text')->nullable();
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
