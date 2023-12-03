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
        Schema::create('condition_stage', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('condition_id');
            $table->index('condition_id');
            $table->foreign('condition_id')->references('id')->on('condition')->onDelete('cascade');
            $table->string('summary')->nullable();
            $table->json('assessment')->nullable();
            $table->string('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('condition_stage');
    }
};
