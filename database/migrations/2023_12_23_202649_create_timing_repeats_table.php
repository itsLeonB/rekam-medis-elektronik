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
        Schema::create('timing_repeats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('timing_id');
            $table->index('timing_id');
            $table->foreign('timing_id')->references('id')->on('timings');
            $table->unsignedInteger('count')->nullable();
            $table->unsignedInteger('count_max')->nullable();
            $table->decimal('duration')->nullable();
            $table->decimal('duration_max')->nullable();
            $table->string('duration_unit')->nullable();
            $table->unsignedInteger('frequency')->nullable();
            $table->unsignedInteger('frequency_max')->nullable();
            $table->decimal('period')->nullable();
            $table->decimal('period_max')->nullable();
            $table->string('period_unit')->nullable();
            $table->json('day_of_week')->nullable();
            $table->json('time_of_day')->nullable();
            $table->json('when')->nullable();
            $table->unsignedInteger('offset')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timing_repeats');
    }
};
