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
        Schema::create('patient_address', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id')->unsigned()->foreign('patient_id')->references('id')->on('patient');
            $table->string('use');
            $table->string('line');
            $table->string('country');
            $table->string('postal_code');
            $table->integer('province');
            $table->integer('city');
            $table->integer('district');
            $table->integer('village');
            $table->integer('rw');
            $table->integer('rt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_address');
    }
};
