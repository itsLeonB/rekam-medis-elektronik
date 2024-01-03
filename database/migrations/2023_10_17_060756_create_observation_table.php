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
        Schema::create('observation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->string('status');
            $table->dateTime('effective_date_time')->nullable();
            $table->dateTime('effective_instant')->nullable();
            $table->dateTime('issued')->nullable();
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
        Schema::dropIfExists('observation');
    }
};
