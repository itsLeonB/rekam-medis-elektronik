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
            $table->unsignedBigInteger('patient_id');
            $table->index('patient_id');
            $table->foreign('patient_id')->references('id')->on('patient')->onDelete('cascade');
            $table->enum('use', ['home', 'work', 'temp', 'old', 'billing']);
            $table->json('line');
            $table->string('country');
            $table->string('postal_code');
            $table->integer('province')->unsigned()->nullable();
            $table->integer('city')->unsigned()->nullable();
            $table->bigInteger('district')->unsigned()->nullable();
            $table->bigInteger('village')->unsigned()->nullable();
            $table->integer('rw')->unsigned()->nullable();
            $table->integer('rt')->unsigned()->nullable();
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
