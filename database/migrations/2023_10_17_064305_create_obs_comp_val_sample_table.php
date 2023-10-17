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
        Schema::create('obs_comp_val_sample', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('obs_comp_id');
            $table->foreign('obs_comp_id')->references('id')->on('observation_component')->onDelete('cascade');
            $table->decimal('origin_value');
            $table->string('origin_unit');
            $table->string('origin_system');
            $table->string('origin_code');
            $table->decimal('period');
            $table->decimal('factor');
            $table->decimal('lower_limit');
            $table->decimal('upper_limit');
            $table->integer('dimensions');
            $table->string('data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obs_comp_val_sample');
    }
};
