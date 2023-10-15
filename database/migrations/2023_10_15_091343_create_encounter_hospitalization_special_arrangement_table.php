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
        Schema::create('encounter_hospitalization_special_arrangement', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('encounter_hospitalization_id')->foreign('encounter_hospitalization_id')->references('id')->on('encounter_hospitalization');
            $table->string('special_arrangement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounter_hospitalization_special_arrangement');
    }
};
