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
        Schema::create('encounter_hospitalization', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('encounter_id')->foreign('encounter_id')->references('id')->on('encounter');
            $table->string('preadmission_identifier_system');
            $table->string('preadmission_identifier_use');
            $table->string('preadmission_identifier_value');
            $table->string('origin');
            $table->string('admit_source');
            $table->char('re_admission', 1);
            $table->string('destination');
            $table->string('discharge_disposition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounter_hospitalization');
    }
};
