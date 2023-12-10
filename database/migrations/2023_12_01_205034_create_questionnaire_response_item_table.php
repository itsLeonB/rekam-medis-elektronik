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
        Schema::create('questionnaire_response_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('questionnaire_id');
            $table->index('questionnaire_id');
            $table->foreign('questionnaire_id')->references('id')->on('questionnaire_response')->onDelete('cascade');
            $table->string('link_id');
            $table->string('definition')->nullable();
            $table->string('text')->nullable();
            $table->json('item')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_response_item');
    }
};
