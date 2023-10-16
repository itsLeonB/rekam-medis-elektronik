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
            $table->id()->unsigned();
            $table->unsignedBigInteger('practitioner_id');
            $table->foreign('practitioner_id')->references('id')->on('practitioner')->onDelete('cascade');
             $table->string('use');
            $table->string('line');
            $table->string('postal_code');
            $table->string('country');
            $table->integer('province')->unsigned();
            $table->integer('city')->unsigned();
            $table->bigInteger('district')->unsigned();
            $table->bigInteger('village')->unsigned();
            $table->integer('rw')->unsigned();
            $table->integer('rt')->unsigned();
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
