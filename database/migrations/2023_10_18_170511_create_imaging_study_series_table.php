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
        Schema::create('imaging_study_series', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('imaging_id');
            $table->index('imaging_id');
            $table->foreign('imaging_id')->references('id')->on('imaging')->onDelete('cascade');
            $table->string('uid');
            $table->unsignedInteger('number')->nullable();
            $table->string('modality_system');
            $table->string('modality_code');
            $table->string('modality_display');
            $table->text('description')->nullable();
            $table->unsignedInteger('num_instances')->nullable();
            $table->string('body_site')->nullable();
            $table->enum('laterality', [419161000, 419465000, 51440002])->nullable();
            $table->dateTime('started')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imaging_study_series');
    }
};
