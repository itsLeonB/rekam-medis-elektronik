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
            $table->index('observation_id');
            $table->foreign('observation_id')->references('id')->on('observation')->onDelete('cascade');
            $table->string('value_string')->nullable();
            $table->boolean('value_boolean')->nullable();
            $table->integer('value_integer')->nullable();
            $table->time('value_time')->nullable();
            $table->dateTime('value_date_time')->nullable();
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
