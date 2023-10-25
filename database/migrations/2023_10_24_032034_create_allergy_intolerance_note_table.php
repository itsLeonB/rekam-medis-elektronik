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
        Schema::create('allergy_intolerance_note', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allergy_id');
            $table->index('allergy_id');
            $table->foreign('allergy_id')->references('id')->on('allergy_intolerance')->onDelete('cascade');
            $table->json('author')->nullable();
            $table->dateTime('time')->nullable();
            $table->string('text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allergy_intolerance_note');
    }
};
