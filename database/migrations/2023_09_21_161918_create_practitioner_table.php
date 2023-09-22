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
        Schema::create('practitioner', function (Blueprint $table) {
            $table->id();
            $table->integer('res_id')->unsigned()->foreign('res_id')->references('res_id')->on('resource');
            $table->bigInteger('nik')->unsigned();
            $table->string('ihs_number');
            $table->boolean('active');
            $table->string('name');
            $table->string('prefix');
            $table->string('suffix');
            $table->string('gender');
            $table->date('birth_date');
            $table->string('photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practitioner');
    }
};
