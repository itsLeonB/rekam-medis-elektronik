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
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->string('status');
            $table->string('code');
            $table->string('subject');
            $table->string('encounter');
            $table->dateTime('effective_datetime');
            $table->dateTime('issued_datetime');
            $table->string('value_string');
            $table->boolean('value_boolean');
            $table->integer('value_integer');
            $table->time('value_time');
            $table->dateTime('value_datetime');
            $table->dateTime('value_start');
            $table->dateTime('value_end');
            $table->string('data_absent_reason');
            $table->bigInteger('body_site');
            $table->bigInteger('method');
            $table->string('specimen');
            $table->string('device');
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
