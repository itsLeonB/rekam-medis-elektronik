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
        Schema::create('encounter', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->string('status');
            $table->string('class');
            $table->unsignedInteger('service_type');
            $table->char('priority', 3);
            $table->string('subject');
            $table->string('episode_of_care');
            $table->string('based_on');
            $table->dateTime('period_start');
            $table->dateTime('period_end');
            $table->string('account');
            $table->string('location');
            $table->string('service_provider');
            $table->string('part_of');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounter');
    }
};
