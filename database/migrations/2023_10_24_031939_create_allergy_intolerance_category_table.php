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
        Schema::create('allergy_intolerance_category', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('allergy_id');
            $table->index('allergy_id');
            $table->foreign('allergy_id')->references('id')->on('allergy_intolerance')->onDelete('cascade');
            $table->enum('category', ['food', 'medication', 'environment', 'biologic']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allergy_intolerance_category');
    }
};
