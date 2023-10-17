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
        Schema::create('observation_component', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('observation_id');
            $table->foreign('observation_id')->references('id')->on('observation')->onDelete('cascade');
            $table->string('code');
            $table->string('value_string');
            $table->boolean('value_boolean');
            $table->integer('value_integer');
            $table->time('value_time');
            $table->dateTime('value_datetime');
            $table->dateTime('value_start');
            $table->dateTime('value_end');
            $table->string('data_absent_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observation_component');
    }
};
