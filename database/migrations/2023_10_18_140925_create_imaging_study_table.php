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
        Schema::create('imaging_study', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->enum('status', ['registered', 'available', 'cancelled', 'entered-in-error', 'unknown']);
            $table->string('subject');
            $table->string('encounter')->nullable();
            $table->dateTime('started')->nullable();
            $table->string('referrer')->nullable();
            $table->unsignedInteger('series_num')->nullable();
            $table->unsignedInteger('instances_num')->nullable();
            $table->string('procedure_reference')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imaging_study');
    }
};
