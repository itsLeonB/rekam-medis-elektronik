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
        Schema::create('img_study_series_specimen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('img_series_id');
            $table->index('img_series_id');
            $table->foreign('img_series_id')->references('id')->on('imaging_study_series')->onDelete('cascade');
            $table->string('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('img_study_series_specimen');
    }
};
