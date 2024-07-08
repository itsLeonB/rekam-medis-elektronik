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
        Schema::create('codesystem_icd10', function (Blueprint $table) {
            $table->char('code', 6)->primary();
            $table->string('display_en');
            $table->string('display_id');
        });
        Schema::create('codesystem_loinc', function (Blueprint $table) {
            $table->string('code', 8)->primary();
            $table->string('display', 246);
            $table->string('scale_typ', 7);
        });
        Schema::create('codesystem_iso3166', function (Blueprint $table) {
            $table->char('code', 2)->primary();
            $table->string('display', 52);
        });
        Schema::create('codesystem_bcp47', function (Blueprint $table) {
            $table->string('code', 5)->primary();
            $table->string('display', 10);
            $table->string('definition', 55);
        });
        Schema::create('codesystem_clinicalspecialty', function (Blueprint $table) {
            $table->char('code', 4)->primary();
            $table->string('display', 79);
        });
        Schema::create('codesystem_ucum', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->index();
            $table->string('unit', 72);
        });
        Schema::create('codesystem_icd9cmprocedure', function (Blueprint $table) {
            $table->string('code', 5)->primary();
            $table->string('display', 24);
            $table->string('definition', 163);
        });
        Schema::create('codesystem_v3actcode', function (Blueprint $table) {
            $table->string('code', 42)->primary();
            $table->string('display', 89)->nullable();
            $table->string('definition', 2402)->nullable();
        });
        Schema::create('codesystem_bcp13', function (Blueprint $table) {
            $table->string('code', 73)->unique();
            $table->string('display', 78);
            $table->string('extension', 12);
        });
        Schema::create('codesystem_servicetype', function (Blueprint $table) {
            $table->unsignedBigInteger('code')->primary();
            $table->string('display');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codesystem_bcp13');
        Schema::dropIfExists('codesystem_bcp47');
        Schema::dropIfExists('codesystem_clinicalspecialty');
        Schema::dropIfExists('codesystem_icd9cmprocedure');
        Schema::dropIfExists('codesystem_icd10');
        Schema::dropIfExists('codesystem_iso3166');
        Schema::dropIfExists('codesystem_loinc');
        Schema::dropIfExists('codesystem_servicetype');
        Schema::dropIfExists('codesystem_ucum');
        Schema::dropIfExists('codesystem_v3actcode');
    }
};
