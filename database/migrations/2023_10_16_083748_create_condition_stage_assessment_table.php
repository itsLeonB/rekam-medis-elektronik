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
        Schema::create('condition_stage_assessment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('condition_stage_id');
            $table->index('condition_stage_id');
            $table->foreign('condition_stage_id')->references('id')->on('condition_stage')->onDelete('cascade');
            $table->string('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('condition_stage_assessment');
    }
};
