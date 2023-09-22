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
        Schema::create('practitioner_address', function (Blueprint $table) {
            $table->id();
            $table->integer('practitioner_id')->unsigned()->foreign('practitioner_id')->references('id')->on('practitioner');
            $table->string('use');
            $table->string('line');
            $table->string('postal_code');
            $table->string('country');
            $table->integer('rt')->unsigned();
            $table->integer('rw')->unsigned();
            $table->integer('village')->unsigned();
            $table->integer('district')->unsigned();
            $table->integer('city')->unsigned();
            $table->integer('province')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practitioner_address');
    }
};
