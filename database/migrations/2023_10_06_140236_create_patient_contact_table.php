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
        Schema::create('patient_contact', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->index('patient_id');
            $table->foreign('patient_id')->references('id')->on('patient')->onDelete('cascade');
            $table->json('relationship');
            $table->string('name');
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'unknown']);
            $table->enum('address_use', ['home', 'work', 'temp', 'old', 'billing'])->nullable();
            $table->json('address_line')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
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
        Schema::dropIfExists('patient_contact');
    }
};
