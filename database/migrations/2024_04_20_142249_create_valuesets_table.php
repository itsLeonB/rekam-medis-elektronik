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
        Schema::create('valueset_condition_stagetype', function (Blueprint $table) {
            $table->string('code', 16)->primary();
            $table->string('display', 201);
        });
        Schema::create('valueset_encounter_reasoncode', function (Blueprint $table) {
            $table->char('code', 9)->primary();
            $table->string('display', 198);
        });
        Schema::create('valueset_medication_ingredientstrengthdenominator', function (Blueprint $table) {
            $table->char('code', 10)->primary();
            $table->string('system', 58);
            $table->string('display', 45);
            $table->string('definition', 352)->nullable();
        });
        Schema::create('valueset_observation_refrangeappliesto', function (Blueprint $table) {
            $table->string('code', 9)->primary();
            $table->string('system', 45);
            $table->string('display', 43);
            $table->string('definition', 122)->nullable();
        });
        Schema::create('valueset_participantroles', function (Blueprint $table) {
            $table->string('code', 18);
            $table->string('display', 74);
        });
        Schema::create('valueset_proceduredeviceactioncodes', function (Blueprint $table) {
            $table->char('code', 15)->primary();
            $table->string('display', 86);
        });
        Schema::create('valueset_procedurenotperformedreason', function (Blueprint $table) {
            $table->char('code', 17)->primary();
            $table->string('display', 124);
        });
        Schema::create('valueset_procedureperformerrolecodes', function (Blueprint $table) {
            $table->char('code', 18)->primary();
            $table->string('display');
            $table->string('definition');
        });
        Schema::create('valueset_riwayatpenyakitkeluarga', function (Blueprint $table) {
            $table->string('code', 18)->primary();
            $table->string('display', 110);
        });
        Schema::create('valueset_riwayatpenyakitpribadi', function (Blueprint $table) {
            $table->string('code', 18)->primary();
            $table->string('display', 98);
        });
        Schema::create('valueset_snomedctbodysite', function (Blueprint $table) {
            $table->string('code', 17)->primary();
            $table->string('display', 143);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valueset_allergy_reactionsubstance');
        Schema::dropIfExists('valueset_condition_stagetype');
        Schema::dropIfExists('valueset_encounter_reasoncode');
        Schema::dropIfExists('valueset_medication_ingredientstrengthdenominator');
        Schema::dropIfExists('valueset_observation_refrangeappliesto');
        Schema::dropIfExists('valueset_participantroles');
        Schema::dropIfExists('valueset_proceduredeviceactioncodes');
        Schema::dropIfExists('valueset_procedurenotperformedreason');
        Schema::dropIfExists('valueset_procedureperformerrolecodes');
        Schema::dropIfExists('valueset_riwayatpenyakitkeluarga');
        Schema::dropIfExists('valueset_riwayatpenyakitpribadi');
        Schema::dropIfExists('valueset_snomedctbodysite');
    }
};
