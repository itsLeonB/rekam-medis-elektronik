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
        Schema::create('questionnaire_response', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->string('identifier_system')->nullable();
            $table->enum('identifier_use', ['usual', 'official', 'temp', 'secondary', 'old'])->nullable();
            $table->string('identifier_value')->nullable();
            $table->json('based_on')->nullable();
            $table->json('part_of')->nullable();
            $table->string('questionnaire')->nullable();
            $table->string('status')->nullable();
            $table->string('subject')->nullable();
            $table->string('encounter')->nullable();
            $table->dateTime('authored')->nullable();
            $table->string('author')->nullable();
            $table->string('source')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_response');
    }
};
