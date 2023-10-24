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
        Schema::create('allergy_intolerance_reaction_manifestation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allergy_reaction_id');
            $table->index('allergy_reaction_id');
            $table->foreign('allergy_reaction_id')->references('id')->on('allergy_intolerance_reaction')->onDelete('cascade');
            $table->string('system')->nullable();
            $table->string('code');
            $table->string('display')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allergy_intolerance_reaction_manifestation');
    }
};
