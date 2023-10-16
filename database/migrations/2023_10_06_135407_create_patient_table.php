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
            $table->id()->unsigned();
            $table->unsignedBigInteger('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->boolean('active');
            $table->string('name');
            $table->string('prefix');
            $table->string('suffix');
            $table->string('gender');
            $table->date('birth_date');
            $table->integer('birth_place')->unsigned();
            $table->datetime('deceased')->nullable();
            $table->char('marital_status', 1);
            $table->boolean('multiple_birth');
            $table->string('language');
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
