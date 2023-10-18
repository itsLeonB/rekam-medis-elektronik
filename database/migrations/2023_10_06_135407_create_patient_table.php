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
        Schema::create('patient', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->boolean('active');
            $table->string('name');
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'unknown']);
            $table->date('birth_date')->nullable();
            $table->integer('birth_place')->unsigned()->nullable();
            $table->json('deceased')->nullable();
            $table->enum('marital_status', ['A', 'D', 'I', 'L', 'M', 'P', 'S', 'T', 'U', 'W'])->nullable();
            $table->json('multiple_birth');
            $table->string('language')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient');
    }
};
