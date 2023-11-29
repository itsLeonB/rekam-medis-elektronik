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
        Schema::create('location_operation_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id');
            $table->index('location_id');
            $table->foreign('location_id')->references('id')->on('location')->onDelete('cascade');
            $table->json('days_of_week')->nullable();
            $table->boolean('all_day')->default(false);
            $table->time('opening_time');
            $table->time('closing_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_operation_hours');
    }
};
