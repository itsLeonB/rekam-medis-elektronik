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
        Schema::create('encounter_hospitalization_spc_arr', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enc_hosp_id');
            $table->index('enc_hosp_id');
            $table->foreign('enc_hosp_id')->references('id')->on('encounter_hospitalization')->onDelete('cascade');
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
